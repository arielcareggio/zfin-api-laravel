FROM docker.io/library/php:8.2.4

RUN docker-php-ext-install pdo_mysql

# Esto instalará las bibliotecas de desarrollo necesarias para la extensión zip.
RUN apt-get update && apt-get install -y libzip-dev

# Instala las dependencias zip
RUN docker-php-ext-install zip

# Instala también los paquetes relacionados con unzip
RUN apt-get update && apt-get install -y unzip

# Copiar el archivo xdebug-3.2.1.tgz al contenedor
#COPY ./xdebug-3.2.1.tgz /xdebug-3.2.1.tgz

# Extraer y compilar Xdebug
RUN curl -O https://xdebug.org/files/xdebug-3.2.1.tgz && \
    tar -xf xdebug-3.2.1.tgz && \
    rm xdebug-3.2.1.tgz && \
    cd xdebug-3.2.1 && \
    phpize && \
    ./configure --enable-xdebug && \
    make && \
    make install && \
	mkdir -p /usr/local/lib/php/extensions/no-debug-non-zts-20200930/ && \
    cp modules/xdebug.so /usr/local/lib/php/extensions/no-debug-non-zts-20200930/ && \
    docker-php-ext-enable xdebug

# Instalar la extensión PHP Debug
RUN pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug

# Configurar Xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar el cliente de MySQL
RUN apt-get update && apt-get install -y default-mysql-client

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias de Laravel
COPY ./composer.json /var/www/html
COPY ./composer.lock /var/www/html
RUN composer install --no-scripts --no-autoloader

RUN apt-get update && apt-get install -y inotify-tools

# Instalar Node.js y npm
RUN apt-get update && apt-get install -y curl
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

# Instalar MariaDB
#RUN apt-get install -y mariadb-server

# Que MariaDB se ejecute automaticamente al ejecutar el contenedor
#RUN systemctl enable mariadb

# Copiar el archivo de configuración de MariaDB en el contenedor
#COPY my.cnf /etc/mysql/my.cnf

# Habilitar el servicio de MariaDB
#RUN /etc/init.d/mariadb start && \
#    mysql -e "CREATE DATABASE laravel;" && \
#    mysql -e "GRANT ALL ON laravel.* TO 'root'@'%' IDENTIFIED BY 'contrasenia';" && \
#    mysql -e "FLUSH PRIVILEGES;"

# Instalar Laravel Mix
RUN npm install --global cross-env laravel-mix

# Copiar el resto de los archivos de la aplicación Laravel
COPY . /var/www/html

# Crear el archivo webpack.mix.js
RUN echo "const mix = require('laravel-mix');\n\nmix.js('resources/js/app.js', 'public/js').vue();\n" > /var/www/html/webpack.mix.js

COPY artisan /var/www/html/artisan

# Verificar la presencia del archivo "artisan"
RUN ls /var/www/html/artisan

# Generar el archivo "artisan"
RUN php artisan key:generate

# Generar el autoload de Composer
RUN composer dump-autoload

# Crear el directorio en caso de que no exista
RUN mkdir -p /var/www/html/storage

# Configurar permisos adecuados
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html/storage

# Exponer el puerto 8000 para acceder a la aplicación Laravel
EXPOSE 8000

# Exponer el puerto 3306 para acceder a MariaDB
#EXPOSE 3306

# Copiar el script de inicio
#COPY init.sh /usr/local/bin/init.sh

# Dar permisos de ejecución al script de inicio
#RUN chmod +x /usr/local/bin/init.sh

#CMD npm run watch -- --unsafe-perm
#CMD php artisan serve --host=0.0.0.0 --port=8000
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=8000 & while inotifywait -r -e modify,create,delete,move /var/www/html; do php artisan optimize; done"]

