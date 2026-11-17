#!/bin/bash

echo "Instalando paquetes necesarios..."
sudo apt update -y
sudo apt install -y apache2 php php-mysql libapache2-mod-php mysql-server

# Detener posibles procesos colgados en el puerto 80 e iniciar servicios
sudo service nginx stop 2>/dev/null
sudo pkill -f apache2 2>/dev/null
sudo service apache2 start
sudo service mysql start

echo "Configurando la base de datos..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS club_leon;"
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'admin'; FLUSH PRIVILEGES;" 2>/dev/null

if [ -f "init.sql" ]; then
    sudo mysql -u root -padmin club_leon < init.sql 2>/dev/null || sudo mysql club_leon < init.sql
elif [ -f "../init.sql" ]; then
    sudo mysql -u root -padmin club_leon < ../init.sql 2>/dev/null || sudo mysql club_leon < ../init.sql
fi

echo "Copiando archivos al servidor..."
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
sudo cp -r "$DIR"/* /var/www/html/
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/

echo "Listo, la aplicacion esta en http://localhost"
