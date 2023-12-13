FROM mariadb:lts

ENV MYSQL_ROOT_PASSWORD=password
ENV MARIADB_USER=shop
ENV MARIADB_PASSWORD=1234
ENV MARIADB_DATABASE=vacation_rental_db

COPY ./db_dump.sql /docker-entrypoint-initdb.d/init.sql

EXPOSE 3306
