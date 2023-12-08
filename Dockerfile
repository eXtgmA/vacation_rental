FROM php:8.1

#WORKDIR /app

# Install mysqli extension for PHP
#RUN docker-php-ext-install pdo_sqlite
# Install mysqli extension for PHP
RUN docker-php-ext-install mysqli
# Install fileinfo extension for PHP
RUN docker-php-ext-install fileinfo

# Copy all PHP files into the container
# for production use, we may want to copy only the files needed for our app
COPY ./public /app/public
COPY ./src /app/src

# Change upload limit to 100MB
RUN echo "upload_max_filesize = 100M" > /usr/local/etc/php/conf.d/uploads.ini

## create folder images in /app/public
#RUN mkdir /app/public/images

# Expose port 80 to the Docker host, so we can access it
EXPOSE 80

# Set working directory
WORKDIR /app/public

# Start PHP server using the built-in server command
CMD ["php", "-S", "0.0.0.0:80"]
