<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require '../src/vendor/autoload.php';

$app = new \Slim\App;

//user registration
$app->post('/user/reg', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody());
    $user = $data->username;
    $pass = $data->password;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO users (username, password) VALUES ('".$user."','".hash('SHA256',$pass)."')";
        $conn->exec($sql);
        $response->getBody()->write(json_encode(array("status"=>"success", "data"=>null)));
    } catch(PDOException $e) {
        $response->getBody()->write(json_encode(array("status"=>"fail","data"=>array("title"=>$e->getMessage()))));
    }
    return $response;
});

//user authentication
$app->post('/user/auth', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody());
    $user = $data->username;
    $pass = $data->password;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "SELECT * FROM users WHERE username = '".$user."' AND password = '".hash('SHA256', $pass)."'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $data = $stmt->fetchAll();

        if(count($data)== 1){
            $key = 'keyless';
            $iat = time();
            $payload = [
                'iss'=> 'http://library.org',
                'aud'=> 'http://library.com',
                'iat'=> $iat,
                'exp'=> $iat + 300,
                'data'=> array ("userid"=> $data[0]['userid'])];
            $jwt=JWT::encode($payload, $key, 'HS256');

            $token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'available')";
            $stmt = $conn->prepare($token);
            $stmt->execute();

            $response->getBody()->write(json_encode(array("status"=>"success","token"=> $jwt, "data"=>null)));
        }else{
            $response->getBody()-> write(json_encode(array("status"=>"fail","data"=>array("title"=>"Authentication Failed!"))));
        }
    }catch(PDOException $e){
        $response->getBody()->write(json_encode(array("status"=>"fail","data"=>array("title"=>$e->getMessage()))));
    }
    return $response;
});

//get all users
$app->post('/all-users', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());
    $jwt = $data -> token;
    $key = 'keyless';
    $iat = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "available"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $token_update = "UPDATE token SET status='unavailable' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($token_update);
            $stmt -> execute();

            $user = "SELECT * FROM users";
            $stmt = $conn -> prepare($user);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $payload = [
                'iss' => 'http://library.org',
                'aud' => 'http://library.com',
                'iat' => $iat,
                'exp' => $iat + 300,
                'data' => array(
                    "status" => $token_status,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');
            $token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'available')";
            $stmt = $conn -> prepare($token);
            $stmt -> execute();
            
            $response -> getBody() -> write(json_encode(array("status" => "Successfully viewed the users", "data" => array("users" => $users, "new_token" => $jwt))));
        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("message" => "Token already unavailable."))));
        }
    }catch (PDOException $e){
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e ->getMessage()))));
    }
    return $response;
});

//get specific user
$app->post('/user', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());
    $jwt = $data -> token;
    $userid = $data -> userid;
    $key = 'keyless';
    $iat = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "available"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $token_update = "UPDATE token SET status='unavailable' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($token_update);
            $stmt -> execute();

            $user = "SELECT * FROM users WHERE userid = '".$userid."'";
            $stmt = $conn -> prepare($user);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $payload = [
                'iss' => 'http://library.org',
                'aud' => 'http://library.com',
                'iat' => $iat,
                'exp' => $iat + 300,
                'data' => array(
                    "status" => $token_status,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');
            $token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'available')";
            $stmt = $conn -> prepare($token);
            $stmt -> execute();
            
            $response -> getBody() -> write(json_encode(array("status" => "Successfully viewed the user.", "data" => array("user" => $user,"new_token" => $jwt))));
        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("message" => "Token already unavailable."))));
        }
    }catch (PDOException $e){
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }
    return $response;
});

//delete a user
$app->post('/user-delete', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());
    $jwt = $data -> token;
    $userid = $data -> userid;
    $key = 'keyless';
    $iat = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "available"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $token_update = "UPDATE token SET status='unavailable' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($token_update);
            $stmt -> execute();

            $user = "DELETE FROM users WHERE userid = '".$userid."'";
            $stmt = $conn -> prepare($user);
            $stmt->execute();

            $payload = [
                'iss' => 'http://library.org',
                'aud' => 'http://library.com',
                'iat' => $iat,
                'exp' => $iat + 300,
                'data' => array(
                    "status" => $token_status,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');
            $token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'available')";
            $stmt = $conn -> prepare($token);
            $stmt -> execute();
            
            $response -> getBody() -> write(json_encode(array("status" => "Successfully deleted a user.", "data" => array("new_token" => $jwt))));
        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("message" => "Token already unavailable."))));
        }
    }catch (PDOException $e){
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }
    return $response;
});

//edit a user
$app->post('/user-edit', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());
    $jwt = $data -> token;
    $userid = $data -> userid;
    $usr = $data -> username;
    $pass = $data -> password;
    $key = 'keyless';
    $iat = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "available"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $token_update = "UPDATE token SET status='unavailable' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($token_update);
            $stmt -> execute();

            $user = "UPDATE users SET username = '".$usr."', password = '".hash('SHA256', $pass)."' WHERE userid = '".$userid."'";
            $stmt = $conn -> prepare($user);
            $stmt->execute();

            $payload = [
                'iss' => 'http://library.org',
                'aud' => 'http://library.com',
                'iat' => $iat,
                'exp' => $iat + 300,
                'data' => array(
                    "status" => $token_status,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            $token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'available')";
            $stmt = $conn -> prepare($token);
            $stmt -> execute();
            
            $response -> getBody() -> write(json_encode(array("status" => "Successfully edited a user.", "data" => array("new_token" => $jwt))));
        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("message" => "Token already unavailable."))));
        }
    }catch (PDOException $e){
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }
    return $response;
});

//add book details
$app->post('/books-add', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());
    $author = $data -> name;
    $title = $data -> title;
    $jwt = $data -> token;
    $key = 'keyless';
    $iat = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "available"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $token_update = "UPDATE token SET status='unavailable' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($token_update);
            $stmt -> execute();

            $sql_author = "INSERT INTO authors(name) VALUES ('".$author."')";
            $stmt = $conn -> prepare($sql_author);
            $stmt->execute();
            $author_id = $conn -> lastInsertId();

            $sql_book = "INSERT INTO books(title, authorid) VALUES ('".$title."', '".$author_id."')";
            $stmt = $conn -> prepare($sql_book);
            $stmt -> execute();
            $book_id = $conn -> lastInsertId();

            $sql_collection = "INSERT INTO books_authors(bookid, authorid) VALUES ('".$book_id."', '".$author_id."')";
            $stmt = $conn -> prepare($sql_collection);
            $stmt -> execute();

            $payload = [
                'iss' => 'http://library.org',
                'aud' => 'http://library.com',
                'iat' => $iat,
                'exp' => $iat + 300,
                'data' => array(
                    "status" => $token_status,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            $token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'available')";
            $stmt = $conn -> prepare($token);
            $stmt -> execute();

            $response -> getBody() -> write(json_encode(array("status" => "Successfully Added a book and author to the Library.", "data" => array("new_token" => $jwt))));
        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("message" => "Token already unavailable."))));
        }
    }catch (PDOException $e){
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }
    return $response;
});

//edit book details
$app->post('/books-edit', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());
    $authorid = $data -> authorid;
    $name = $data -> name;
    $bookid = $data -> bookid;
    $title = $data -> title;
    $jwt = $data -> token;
    $key = 'keyless';
    $iat = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "available"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $token_update = "UPDATE token SET status='unavailable' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($token_update);
            $stmt -> execute();

            $sql_author = "UPDATE authors SET name = '".$name."' WHERE authorid = '".$authorid."'";
            $stmt = $conn -> prepare($sql_author);
            $stmt->execute();

            $sql_book = "UPDATE books SET title = '".$title."' WHERE bookid = '".$bookid."'";
            $stmt = $conn -> prepare($sql_book);
            $stmt -> execute();

            $payload = [
                'iss' => 'http://library.org',
                'aud' => 'http://library.com',
                'iat' => $iat,
                'exp' => $iat + 300,
                'data' => array(
                    "status" => $token_status,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            $token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'available')";
            $stmt = $conn -> prepare($token);
            $stmt -> execute();

            $response -> getBody() -> write(json_encode(array("status" => "Successfully Edited a book and author to the Library.", "data" => array("new_token" => $jwt))));
        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("message" => "Token already unavailable."))));
        }
    }catch (PDOException $e){
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }
    return $response;
});

//delete book details
$app->post('/books-delete', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());
    $authorid = $data -> authorid;
    $bookid = $data -> bookid;
    $jwt = $data -> token;
    $key = 'keyless';
    $iat = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "available"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $token_update = "UPDATE token SET status='unavailable' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($token_update);
            $stmt -> execute();

            $sql_author = "DELETE FROM authors WHERE authorid = '".$authorid."'";
            $stmt = $conn -> prepare($sql_author);
            $stmt->execute();

            $sql_book = "DELETE FROM books WHERE bookid = '".$bookid."'";
            $stmt = $conn -> prepare($sql_book);
            $stmt -> execute();

            $sql_delete_books_authors = "DELETE FROM books_authors WHERE authorid = '".$authorid."' AND bookid = '".$bookid."'";
            $stmt = $conn->prepare($sql_delete_books_authors);
            $stmt->execute();

            $payload = [
                'iss' => 'http://library.org',
                'aud' => 'http://library.com',
                'iat' => $iat,
                'exp' => $iat + 300,
                'data' => array(
                    "status" => $token_status,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            $token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'available')";
            $stmt = $conn -> prepare($token);
            $stmt -> execute();

            $response -> getBody() -> write(json_encode(array("status" => "Successfully Deleted a book and author in the Library.", "data" => array("new_token" => $jwt))));
        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("message" => "Token already unavailable."))));
        }
    }catch (PDOException $e){
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }
    return $response;
});

//view book details
$app->post('/books-view', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());
    $authorid = $data -> authorid;
    $bookid = $data -> bookid;
    $jwt = $data -> token;
    $key = 'keyless';
    $iat = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "available"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $token_update = "UPDATE token SET status='unavailable' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($token_update);
            $stmt -> execute();

            $sql_author = "SELECT books.title AS book_title, authors.name AS author_name
                            FROM books_authors
                            INNER JOIN books ON books.bookid = books_authors.bookid
                            INNER JOIN authors ON authors.authorid = books_authors.authorid";

            $stmt = $conn -> prepare($sql_author);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $payload = [
                'iss' => 'http://library.org',
                'aud' => 'http://library.com',
                'iat' => $iat,
                'exp' => $iat + 300,
                'data' => array(
                    "status" => $token_status,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');
            $token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'available')";
            $stmt = $conn -> prepare($token);
            $stmt -> execute();

            $response -> getBody() -> write(json_encode(array("status" => "Successfully Viewed a book and author in the Library.", "data" => array("user" => $user,"new_token" => $jwt))));
        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("message" => "Token already unavailable."))));
        }
    }catch (PDOException $e){
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }
    return $response;
});

//view all book details
$app->post('/books-all', function(Request $request, Response $response, array $args){
    $data = json_decode($request->getBody());
    $jwt = $data -> token;
    $key = 'keyless';
    $iat = time();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $token = "SELECT * FROM token WHERE token = '".$jwt."'";
        $stmt = $conn -> prepare($token);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $token_status = $result[0]["status"];

        if(count($result) == 1 && $token_status === "available"){

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $token_update = "UPDATE token SET status='unavailable' WHERE token='".$jwt."'" ;
            $stmt = $conn -> prepare($token_update);
            $stmt -> execute();


            $user = "SELECT books.title AS book_title, authors.name AS author_name
                        FROM books_authors
                        INNER JOIN books ON books.bookid = books_authors.bookid
                        INNER JOIN authors ON authors.authorid = books_authors.authorid";

            $stmt = $conn -> prepare($user);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $payload = [
                'iss' => 'http://library.org',
                'aud' => 'http://library.com',
                'iat' => $iat,
                'exp' => $iat + 300,
                'data' => array(
                    "status" => $token_status,
                )
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            $token = "INSERT INTO token(token, status) VALUES ('".$jwt."', 'available')";
            $stmt = $conn -> prepare($token);
            $stmt -> execute();
            
            $response -> getBody() -> write(json_encode(array("status" => "Successfully Viewed all books and authors in the Library.", "data" => array("users" => $users,"new_token" => $jwt))));
        }else{
            $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("message" => "Token already unavailable."))));
        }
    }catch (PDOException $e){
        $response -> getBody() -> write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
    }
    return $response;
});

$app->run();
?>