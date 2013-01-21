RIBBIT a twitter clone
======================

powered by PHP, Symfony, Silex and Doctrine ORM
-----------------------------------------------

## Ported to Silex by M.PARAISO : mparaiso@online.fr

## Love Demo : http://mpmedia.alwaysdata.net/ribbit/admin/profile

### INSTALLATION

#### Requirements :

+ Apache Server : http://httpd.apache.org/
+ PHP > 5.3 : http://php.net/
+ Mysql : http://www.mysql.com/
+ Composer : http://getcomposer.org

#### Setup :

+ Install composer, install composer packages with command : <code>composer install</code>
+ create a apache server virtual host , the web root folder is /web
+ create these environment variables , on the system level if you want to be able to use the console cli : 
    + RIBBIT_HOST
    + RIBBIT_DATABASE
    + RIBBIT_USERNAME
    + RIBBIT_PASSWORD
    + RIBBIT_DRIVER : should be "pdo_mysql"
    + RIBBIT_ENVIRONMENT development or production
+ change the index page of the htaccess in /web folder , 
    to index_dev.php for development , or index.php for producion
    in development error will be displayed and you can check application logs in /log/silex.log
+ create the database in mysql
+ go the project root. use the console utility to create tables in the database :
    + <code>console orm:schema-tool:create</code>
    + if it doesnt work , use the sql script file located in /database/ribbit.sql to create the database.
+ chmod the following folders to +w : /cache , /log
+ start or restart the server

#### Help : 

check Ribbit tutorial on nettutsplus : 
+ http://net.tutsplus.com/tutorials/building-ribbit-in-rails/
+ http://net.tutsplus.com/tutorials/php/building-ribbit-in-php/


#### Why ?

+ help learn Silex
+ help learn Symfony framework
+ help learn Doctrine ORM
+ help learn PHP
