#!/bin/bash

echo "Deteniendo servicios..."
sudo service apache2 stop
sudo service mysql stop
echo "Servicios detenidos."
