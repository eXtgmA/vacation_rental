# Ferienhausvermietung
## Description
This project is a web application that allows you to manage and rent holiday homes.</br>
It was created as part of the __Web42__ module at AKAD.

## Installation
### 1. Install PHP 8.1 
#### Windows
1. Download PHP Version from https://windows.php.net/download#php-8.2  
2. Extract to _C:\php_
3. Add _C:\php_ to __PATH__
4. Rename _php.ini-development_ to _php.ini_
5. Edit _php.ini_ and uncomment '__extension=mysqli__'

### 2. clone repository
```
git clone git@github.com:eXtgmA/vacation_rental.git
```

### 3. Setup Database
```
docker run -d -e MYSQL_ROOT_PASSWORD=password -e MARIADB_USER=shop -e MARIADB_PASSWORD=1234 -e MARIADB_DATABASE=shop -v .\database\init.sql:/docker-entrypoint-initdb.d/init.sql -p 3306:3306 --name web42_mariadb mariadb:lts
```

### 4. Start PHP Server
```
# go to public folder
cd public

# start php server
php -S localhost:8000
```
