Estamos haciendo un sistema web para administrar una liga de fut local. Lleva el control de equipos, jugadores, roles de juego, tablas de posiciones y todo el tema de pagos e inscripciones.

El Stack (Con el qué trabajamos)
Cero frameworks pesados, todo es muy directo:

Front: HTML, CSS y JS puro (Vanilla).
Back: PHP puro que usamos como si fuera una API.
BD: MySQL.
Entorno local: Docker o XAMPP.
Estructura rápida del repo
Los archivos .html son las vistas.
Las carpetas /css y /js traen los estilos y la lógica del front.
Los archivos .php en la raíz son nuestros scripts del backend.
conexion.php centraliza la conexión a la base de datos.
init.sql trae el script con las tablas y datos de prueba.
Cómo correrlo en tu laptop
Si usas Docker (te lo recomiendo para no batallar):

Abre tu terminal en la carpeta del proyecto.
Corre: docker-compose up -d
Entra a http://localhost:8080. La base de datos queda en el puerto 3307.
Si usas XAMPP:

Mete la carpeta del proyecto en htdocs.
Abre phpMyAdmin, crea una base de datos que se llame club_leon y córrerle el script init.sql a mano.
Cómo fluye la información (Front y Back)
Acá la regla principal es que no mezclamos cosas. El front y el back están separados.

El usuario interactúa con la interfaz (el HTML).
La página detecta el clic o acción del usuario y le avisa al servidor en PHP.
El PHP recibe los datos, Se comunica con la base de datos de forma segura usando un conector estándar (PDO), ejecuta su lógica y nos regresa una respuesta en formato JSON.
Si quieres ver esto en acción, revisa los archivos login_futbolero.html y validar_login.php. Ahí está el ejemplo más claro de cómo nos estamos comunicando.

Para mantener el código limpio y seguro, todos seguimos estas reglas:

Prevención de inyecciones SQL: Queda prohibida la concatenación directa de variables en las consultas. El estándar del proyecto es utilizar PDO con sentencias preparadas (prepare y execute).
Estándar de respuestas: Todo endpoint de PHP debe retornar siempre un JSON con esta estructura exacta: {"success": true/false, "mensaje": "..."}.
Seguridad de contraseñas: Las contraseñas no se almacenan en texto plano bajo ninguna circunstancia. Es obligatorio encriptarlas utilizando password_hash() y validarlas con password_verify().
Manejo de errores (Try-catch): Si un archivo PHP realiza una transacción o consulta compleja en la base de datos, debe ir dentro de un bloque try-catch. Así, si ocurre un error en el servidor, se puede devolver un mensaje de error controlado en el JSON evitando que la aplicación falle visualmente.
