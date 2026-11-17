#!/bin/bash

echo "Selecciona una opcion:"
echo "1. Borrar aplicacion y base de datos"
echo "2. Borrar respaldos"
echo "3. Borrar todo"
echo "4. Salir"
read -p "Opcion: " OPCION

if [ "$OPCION" == "1" ]; then
    sudo mysql -u root -padmin -e "DROP DATABASE IF EXISTS club_leon;" 2>/dev/null || sudo mysql -e "DROP DATABASE IF EXISTS club_leon;"
    sudo rm -rf /var/www/html/*
    sudo service apache2 stop
    sudo service mysql stop
    echo "Aplicacion y base de datos borradas."

elif [ "$OPCION" == "2" ]; then
    rm -rf respaldos/*
    echo "Respaldos borrados."

elif [ "$OPCION" == "3" ]; then
    sudo mysql -u root -padmin -e "DROP DATABASE IF EXISTS club_leon;" 2>/dev/null || sudo mysql -e "DROP DATABASE IF EXISTS club_leon;"
    sudo rm -rf /var/www/html/*
    sudo service apache2 stop
    sudo service mysql stop
    rm -rf respaldos
    echo "Todo ha sido borrado."

else
    echo "Saliendo..."
fi
