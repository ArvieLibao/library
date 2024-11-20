# Slim Framework API with Firebase JWT Authentication

This project is a RESTful API built with the Slim framework and Firebase JWT for secure user authentication and other functionalities.

---

## Applications Used

- **Backend Framework**: [Slim Framework](https://www.slimframework.com/)
- **JWT Library**: [Firebase JWT](https://github.com/firebase/php-jwt)
- **Database**: MySQL
- **HTTP Client Testing**: Thunder Client
- **PHP Autoloading**: Composer

---

## Library API Features

1. **User Authentication**:
   - Register a new user.
   - Authenticate and generate JWT tokens.
   - Validate tokens for secure endpoints.

2. **CRUD Operations**:
   - Perform Create, Read, Update, and Delete operations for books and users.

3. **Middleware**:
   - Protect routes using JWT middleware.

---

## Endpoints
### Register User
   - **Method**: `POST`
   - **Endpoint**: `https://127.0.0.1/LIBRARY/public/user/reg`
   - **Request**:
      ```json
      {
      "username" : "Law",
      "password" : "Renz"
      }
      ```
   - **Response**:
      - **Success**
         ```json
         {
             "status": "success",
             "data": null
         }
         ```
      - **Failure**
         ```json
         {
          "status": "fail",
             "data": {
             "title": "Username already exists"
             }
         }
         ```
---
### Authenticate User
   - **Method**: `POST`
   - **Endpoint**: `http://127.0.0.1/library/public/user/auth`
   - **Request**:
      ```json
      {
         "username" : "Law",
         "password" : "Renz"
      }
      ```
   - **Response**:
     - **Success**
         ```json
         {
            "status": "success",
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjM3NTIsImV4cCI6MTczMjEyNDA1MiwiZGF0YSI6eyJ1c2VyaWQiOjU1fX0.na0JHDPIGomwkwEIx60YXo2znC4sr4eR5cB8eufS0EU",
            "data": null
         }
         ```
      - **Failure**
         ```json
         {
            "status": "fail",
            "data": {
               "title": "Authentication Failed!"
            }
         }
         ```
---
### See All Users
   - **Method**: `POST`
   - **Endpoint**: `http://127.0.0.1/LIBRARY/public/all-users`
   - **Request**:
     ```json
      {
         "token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjM5MjcsImV4cCI6MTczMjEyNDIyNywiZGF0YSI6eyJ1c2VyaWQiOjU2fX0.EDWQH41ODvW48wsFy4uN_sjoNAIbMCMRf-5xX7hZTj0"
      }
     ```
   - **Response**:
     - **Success**
         ```json
         {
            "status": "Successfully viewed the users",
            "data": {
               "users": [
                  {
                  "userid": 30,
                  "username": "Niccolo",
                  "password": "7b5f0efda0022f3cc05bd0fcb1e0bc6890e7fee792a1c220d9351e0914e3aa79"
                  },
                  {
                  "userid": 31,
                  "username": "Marcus",
                  "password": "03ccf2f9fdfa285e6c5e9a57c9d022d0d986364532749dac357b723c129424af"
                  },
                  {
                  "userid": 32,
                  "username": "Magnus",
                  "password": "0a00079dcd09ed203129d3a15331cc4bcb9c03d94b22a83222e7aaaf9c1bac3b"
                  }
               ],
               "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQwMzcsImV4cCI6MTczMjEyNDMzNywiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.U4g6vxd3He2BTu_FjmvlcIcSt7lOcuDAjD7q7OkL37g"
            }
         }
         ```
      - **Failure**
         ```json
         {
            "status": "fail",
            "data": {
               "message": "Token already unavailable."
            }
         }
         ```
---
### See a User
   - **Method**: `POST`
   - **Endpoint**: `http://127.0.0.1/LIBRARY/public/user`
   - **Request**:
      ```json
      {
         "userid" : 32,
         "token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQyMjIsImV4cCI6MTczMjEyNDUyMiwiZGF0YSI6eyJ1c2VyaWQiOjU5fX0.DQULZIx4qryGYBZy2AiKrgsn7Qj5nf7HJqv8z1uMjr4"
      }
      ```
   - **Response**:
      - **Success**
         ```json
         {
            "status": "Successfully viewed the user.",
            "data": {
               "user": {
                  "userid": 32,
                  "username": "Magnus",
                  "password": "0a00079dcd09ed203129d3a15331cc4bcb9c03d94b22a83222e7aaaf9c1bac3b"
               },
               "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQyNDUsImV4cCI6MTczMjEyNDU0NSwiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.PNlldNYCYGE7-xNdD8m2R9xHLbhQzgMPLVkBCq6p428"
            }
         }
         ```
      - **Failure**
         ```json
         {
            "status": "fail",
            "data": {
               "message": "Token already unavailable."
            }
         }
         ```
---
### Delete a User
   - **Method**: `POST`
   - **Endpoint**: `http://127.0.0.1/LIBRARY/public/user-delete`
   - **Request**:
      ```json
      {
         "userid" : 32,
         "token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQzNTksImV4cCI6MTczMjEyNDY1OSwiZGF0YSI6eyJ1c2VyaWQiOjYyfX0.q4fRBJ5Vnrg8FKHiH1KP5sQjNDmYCmH8MIsNTid1Hag"
      }
      ```
   - **Response**:
      - **Success**
         ```json
         {
            "status": "Successfully deleted a user.",
            "data": {
               "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQzODYsImV4cCI6MTczMjEyNDY4NiwiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.35s1pwho-Lb-wb2vl1_nVs7X4NMjvXtdhtnE-7sGTAo"
            }
         }
         ```
      - **Failure**
         ```json
         {
            "status": "fail",
            "data": {
               "message": "Token already unavailable."
            }
         }
         ```
---
### Edit a User
   - **Method**: `POST`
   - **Endpoint**: `http://127.0.0.1/library/public/books_author/add`
   - **Request**:
     ```json
      {
         "userid" : 30,
         "username" : "Libao",
         "password" : "Arvie",
         "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQzODYsImV4cCI6MTczMjEyNDY4NiwiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.35s1pwho-Lb-wb2vl1_nVs7X4NMjvXtdhtnE-7sGTAo"
      }
     ```
   - **Response**:
      - **Success**
         ```json
         {
            "status": "Successfully edited a user.",
            "data": {
               "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ0NjcsImV4cCI6MTczMjEyNDc2NywiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.65jIKDbO2q7GI0w9LVVzLU75soPnFf2PglikuAyco4g"
            }
         }
            ```
      - **Failure**
         ```json
         {
            "status": "fail",
            "data": {
               "message": "Token already unavailable."
            }
         }
         ```
---
### Add Book and Details
   - **Method**: `POST`
   - **Endpoint**: `https://127.0.0.1/LIBRARY/public/books-add`
   - **Request**:
     ```json
      {
         "name": "Niccolo Machiavelli",
         "title": "The Prince",
         "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ0NjcsImV4cCI6MTczMjEyNDc2NywiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.65jIKDbO2q7GI0w9LVVzLU75soPnFf2PglikuAyco4g"
      }
     ```
   - **Response**:
     - **Success**
         ```json
         {
            "status": "Successfully Added a book and author to the Library.",
            "data": {
               "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ1MzAsImV4cCI6MTczMjEyNDgzMCwiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.2JE026jiDADbRM9RlPzIdMdPKKIZSVA-N0OtmbL7I84"
            }
         }
         ```
     - **Failure**
         ```json
         {
            "status": "fail",
            "data": {
               "message": "Token already unavailable."
            }
         }
         ```
---
### Edit Book and Details
   - **Method**: `POST`
   - **Endpoint**: `https://127.0.0.1/LIBRARY/public/books-edit`
   - **Request**:
     ```json
      {
      "bookid" : 26,
      "name" : "El FIlibusterismo",
      "authorid": 31,
      "title": "Jose Rizal",
      "token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ1MzAsImV4cCI6MTczMjEyNDgzMCwiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.2JE026jiDADbRM9RlPzIdMdPKKIZSVA-N0OtmbL7I84"
      }
     ```
   - **Response**:
     - **Success**
         ```json
         {
            "status": "Successfully Edited a book and author to the Library.",
            "data": {
               "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ2NTMsImV4cCI6MTczMjEyNDk1MywiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.toa_fy7KcfTIX_f2tnUfla0TGsBQDh_ij4NcgCwy1JU"
            }
         }
         ```
     - **Failure**
         ```json
         {
            "status": "fail",
            "data": {
               "message": "Token already unavailable."
            }
         }
         ```
---
### Delete Book and Details
   - **Method**: `POST`
   - **Endpoint**: `http://127.0.0.1/LIBRARY/public/books-delete`
   - **Request**:
     ```json
      {
         "bookid": 26,
         "authorid": 31,
         "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ2NTMsImV4cCI6MTczMjEyNDk1MywiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.toa_fy7KcfTIX_f2tnUfla0TGsBQDh_ij4NcgCwy1JU"
      }
     ```
   - **Response**:
      - **Success**
         ```json
         {
         "status": "Successfully Deleted a book and author in the Library.",
         "data": {
            "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ3MzQsImV4cCI6MTczMjEyNTAzNCwiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.HD4NF3R4eXudMEX6jG5bZSMKFgsbgEDmNydKy1kH0h4"
         }
         }
         ```
      - **Failure**
         ```json
         {
            "status": "fail",
            "data": {
               "message": "Token already unavailable."
            }
         }
         ```
---
### View Book Details
   - **Method**: `POST`
   - **Endpoint**: `https://127.0.0.1/LIBRARY/public/books-view`
   - **Request**:
     ```json
      {
      "bookid" : 28,
      "authorid" : 31,
      "token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ3MzQsImV4cCI6MTczMjEyNTAzNCwiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.HD4NF3R4eXudMEX6jG5bZSMKFgsbgEDmNydKy1kH0h4"
      }
     ```
   - **Response**:
      - **Success**
         ```json
         {
            "status": "Successfully Viewed a book and author in the Library.",
            "data": {
               "user": {
                  "book_title": "Meditation",
                  "author_name": "Marcus Aurellius"
               },
               "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ4MTAsImV4cCI6MTczMjEyNTExMCwiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.fwQ69bf0NrxE-RxNRfgl5QGHJt9ORlTZHeLCc_v2Kag"
            }
         }
         ```
      - **Failure**
         ```json
         {
            "status": "fail",
            "data": {
               "message": "Token already unavailable."
            }
         }
         ```
---
### View All Books 
   - **Method**: `POST`
   - **Endpoint**: `https://127.0.0.1/LIBRARY/public/books-all`
   - **Request**:
     ```json
   {
      "token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ4MTAsImV4cCI6MTczMjEyNTExMCwiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.fwQ69bf0NrxE-RxNRfgl5QGHJt9ORlTZHeLCc_v2Kag"
   }
     ```
   - **Response**:
      - **Success**
         ```json
         {
            "status": "Successfully Viewed all books and authors in the Library.",
            "data": {
               "users": [
                  {
                  "book_title": "Meditation",
                  "author_name": "Marcus Aurellius"
                  },
                  {
                  "book_title": "Daily Stoic",
                  "author_name": "Ryan Holiday"
                  },
                  {
                  "book_title": "12 Rules for Life",
                  "author_name": "Jordan Peterson"
                  },
                  {
                  "book_title": "Cant Hurt Me",
                  "author_name": "David Goggins"
                  },
                  {
                  "book_title": "The Prince",
                  "author_name": "Niccolo Machiavelli"
                  }
               ],
               "new_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzIxMjQ4NzMsImV4cCI6MTczMjEyNTE3MywiZGF0YSI6eyJzdGF0dXMiOiJhdmFpbGFibGUifX0.zfWtWQK-4B7XnHSbc2aRD8E2EMlMqGvdmPenLsqnSVI"
            }
         }
         ```
      - **Failure**
         ```json
         {
            "status": "fail",
            "data": {
               "message": "Token already unavailable."
            }
         }
         ```
---