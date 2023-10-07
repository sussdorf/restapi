# Lumen with JWT



## Installation Guide:

1. Create DB as *maiksudorf-rest-api*

   Set DB credentials in *.env* file
<code>

   DB_CONNECTION=mysql

   DB_HOST=127.0.0.1

   DB_PORT=3306

   DB_DATABASE=maiksudorf-rest-api

   DB_USERNAME=root

   DB_PASSWORD=
   </code>


2. run following commands:

   php artisan migrate

   php artisan db:seed

    
3. Open URL in Postman tool

   http://localhost/PROJECT_DIRECTORY/public/api/login
   with following credentials:
   email: admin@admin.com
   password: 123456

   Then you will recieved a token


4. Open a URL again in Postman tool

   http://localhost/PROJECT_DIRECTORY/public/api/users
   And add a Bearer token in it then you will get results.
