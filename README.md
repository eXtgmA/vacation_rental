# Ferienhausvermietung
## Description
This project is a web application that allows you to manage and rent holiday homes.</br>
It was created as part of the __Web42__ module at AKAD.

## Installation
### 1. Install PHP 8.1 
#### Windows
1. Download PHP Version from https://windows.php.net/download#php-8.1
2. Extract to _C:\php_
3. Add _C:\php_ to __PATH__
4. Copy _php/php.ini_ from this repository to _C:\php_

### 2. Clone Repository
```
git clone git@github.com:eXtgmA/vacation_rental.git
```

### 3. Move Images
Move all images one folder up  
from `/public/images/sample`  
to   `/public/images`  
(otherwise you will not be able to see any images of the preview houses)

### 4. Setup Database
```
docker run -d -e MYSQL_ROOT_PASSWORD=password -e MARIADB_USER=shop -e MARIADB_PASSWORD=1234 -e MARIADB_DATABASE=vacation_rental_db -v .\database\init.sql:/docker-entrypoint-initdb.d/init.sql -p 3306:3306 --name web42_mariadb mariadb:lts
```

### 5. Start PHP Server
```
# go to public folder
cd public

# start php server
php -S localhost:8000
```

## Run local Code Analysis
### PHPStan
1.  install PHPStan
```
wget https://github.com/phpstan/phpstan/releases/latest/download/phpstan.phar
chmod +x phpstan.phar
```
2. run PHPStan
```
./phpstan.phar analyse --configuration=phpstan.neon --level=max --no-progress .
```

### PHP CodeSniffer
1. install PHP CodeSniffer
```
wget https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
chmod +x phpcs.phar   
```
2. run PHP CodeSniffer
```
./phpcs.phar --standard=.phpcs.xml .
```

### PHP Code Beautifier and Fixer
This is a tool to automatically fix PHP CodeSniffer errors.
1. install PHP Code Beautifier and Fixer
```
wget https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar
chmod +x phpcbf.phar
```
2. run PHP Code Beautifier and Fixer
```
./phpcbf.phar --standard=.phpcs.xml .
```

## Icon usage
  - create italic element <i></i>
  - add class to element <i class=""></i>
  - add "fa" to class <i class="fa"></i> *important to activate fontawesome icons*
  - choose desired icon an add this to class aswell <i class="fa fa-heart"></i>

for more information follow [Fontawesome](https://fontawesome.com/search?)

## Configure GIT to use LF
git config --global core.autocrlf false
git config --global core.eol lf  


## Deployment with Docker-Compose
Run the following command to start the application with the official images, based on the current main branch.
```
docker-compose -f compose-official.yaml -p web42-vacation_rental up -d
```
If you want to start the application with your local changes, use the following command.
```
docker-compose -f compose-local.yaml -p web42-vacation_rental up -d --build
```
