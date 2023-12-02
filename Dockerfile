FROM php:8.1

#WORKDIR /app

# Install mysqli extension for PHP
#RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install mysqli

# Copy all PHP files into the container
# for production use, we may want to copy only the files needed for our app
COPY . /app

# Expose port 80 to the Docker host, so we can access it
EXPOSE 80

# Set working directory
WORKDIR /app/public

# Start PHP server using the built-in server command
CMD ["php", "-S", "0.0.0.0:80"]

