#!/bin/bash

# Crear carpeta para respaldos
mkdir -p respaldos
FECHA=$(date +"%Y-%m-%d_%H-%M-%S")

echo "Haciendo respaldo de la base de datos..."
sudo mysqldump -u root -padmin club_leon > "respaldos/db_club_leon_$FECHA.sql" 2>/dev/null || sudo mysqldump club_leon > "respaldos/db_club_leon_$FECHA.sql"

echo "Haciendo respaldo de los archivos web..."
sudo tar -czf "respaldos/archivos_$FECHA.tar.gz" -C /var/www/html .

echo "Respaldo completado en la carpeta respaldos/"
ls -lh respaldos
