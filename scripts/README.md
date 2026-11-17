# Scripts de Automatización - DevOps (Unidad 2: Linux)

Este directorio contiene los scripts en Bash para gestionar el despliegue, respaldo y limpieza del proyecto **SportAdmin Club León** sin utilizar Docker.

---

## 📁 Contenido

| Script | Descripción |
| :--- | :--- |
| `levantar.sh` | Instala dependencias (Apache, PHP, MySQL), inicia servicios, importa la base de datos `init.sql` y copia los archivos a `/var/www/html/`. |
| `respaldo.sh` | Genera una copia de seguridad de la base de datos (`.sql`) y empaqueta los archivos web (`.tar.gz`) en la carpeta `./respaldos`. |
| `borrar.sh` | Menú interactivo para desinstalar la app y su base de datos, o para purgar respaldos. |

---

## 🚀 Cómo utilizarlos en Linux (Ubuntu / Debian / WSL)

### 1. Dar permisos de ejecución
Antes de ejecutarlos por primera vez, asegúrate de otorgarles permisos de ejecución:
```bash
chmod +x scripts/*.sh
```

### 2. Levantar la aplicación
Ejecuta el script de despliegue con permisos de administrador:
```bash
sudo ./scripts/levantar.sh
```
Una vez terminado, abre tu navegador web en:
`http://localhost`

### 3. Crear un respaldo
Cuando quieras respaldar el estado de la base de datos y archivos:
```bash
./scripts/respaldo.sh
```
Los respaldos se guardarán automáticamente en la carpeta `./respaldos` con fecha y hora.

### 4. Borrar / Limpiar el entorno
Si necesitas desinstalar la base de datos y archivos web, o borrar respaldos:
```bash
./scripts/borrar.sh
```
El script te mostrará un menú con 4 opciones sencillas para elegir qué deseas limpiar.
