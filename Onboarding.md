Básicamente estamos haciendo un sistema web para administrar una liga de fut local. Lleva el control de equipos, jugadores, roles de juego, tablas de posiciones y todo el tema de pagos e inscripciones.

El Stack (Con el qué trabajamos)
Cero frameworks pesados, todo es muy directo:

Front: HTML, CSS y JS puro (Vanilla).
Back: PHP puro que usamos como si fuera una API.
BD: MySQL.
Entorno local: Docker o XAMPP.
Estructura rápida del repo
Los archivos .html son las vistas.
Las carpetas /css y /js traen los estilos y la lógica del front.
Los archivos .php en la raíz son nuestros endpoints.
conexion.php centraliza la conexión a la base de datos.
init.sql trae el script con las tablas y datos de prueba.
Cómo correrlo en tu compu
Si usas Docker (te lo recomiendo para no batallar):

Abre tu terminal en la carpeta del proyecto.
Corre: docker-compose up -d
Entra a http://localhost:8080. La base de datos queda en el puerto 3307 por si te quieres conectar con DBeaver o algo así.
Si usas XAMPP (vieja escuela):

Mete la carpeta del proyecto en htdocs.
Abre phpMyAdmin, crea una base de datos que se llame club_leon y córrerle el script init.sql a mano.
Cómo fluye la información (Front y Back)
Acá la regla principal es que no mezclamos cosas. El front y el back están separados.

El usuario interactúa con la interfaz (el HTML).
Un archivo JS detecta el evento y hace una petición con fetch() hacia un endpoint en PHP.
El PHP recibe los datos, se conecta a la BD usando PDO, ejecuta su lógica y nos regresa una respuesta en formato JSON.
Si quieres ver esto en acción, revisa los archivos login_futbolero.html y validar_login.php. Ahí está el ejemplo más claro de cómo nos estamos comunicando.

Reglas de oro del equipo
Para mantener el código limpio y seguro, todos seguimos estas reglas:

Cero inyecciones SQL: Nunca concatenes variables directo en los queries. Todos usamos PDO con consultas preparadas (prepare y execute).
Estándar de respuestas: Todo endpoint de PHP tiene que escupir un JSON con esta estructura exacta: {"success": true/false, "mensaje": "..."}.
Passwords seguros: Las contraseñas en PHP no se guardan en texto plano. Se encriptan con password_hash() y se validan con password_verify().
Try-catch en todo: Si tu PHP hace una transacción o query complejo en la base de datos, mételo en un bloque try-catch para que si algo truena, podamos mandar un mensaje de error limpio en el JSON y no se caiga el front.
