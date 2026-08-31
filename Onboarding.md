# Guía de Onboarding del Proyecto

Estamos haciendo un sistema web para administrar una liga de fut local. Lleva el control de equipos, jugadores, roles de juego, tablas de posiciones y todo el tema de pagos e inscripciones.

---

## El Stack (Con el que trabajamos)

Cero frameworks pesados, todo es muy directo:

* **Front:** HTML, CSS y JS puro (Vanilla).
* **Back:** PHP puro que usamos como si fuera una API.
* **BD:** MySQL.
* **Entorno local:** Docker o XAMPP.

---

## Estructura rápida del repo

* Los archivos `.html` son las vistas.
* Las carpetas `/css` y `/js` traen los estilos y la lógica del front.
* Los archivos `.php` en la raíz son nuestros scripts del backend.
* `conexion.php` centraliza la conexión a la base de datos.
* `init.sql` trae el script con las tablas y datos de prueba.

---

## Cómo correrlo en tu laptop

### Si usas Docker (te lo recomiendo para no batallar):

1. Abre tu terminal en la carpeta del proyecto.
2. Corre: `docker-compose up -d`
3. Entra a `http://localhost:8080`. La base de datos queda en el puerto 3307.

### Si usas XAMPP:

1. Mete la carpeta del proyecto en `htdocs`.
2. Abre phpMyAdmin, crea una base de datos que se llame `club_leon` y córrerle el script `init.sql` a mano.

---

## Cómo fluye la información (Front y Back)

Acá la regla principal es que no mezclamos cosas. El front y el back están separados.

1. El usuario interactúa con la interfaz (el HTML).
2. La página detecta el clic o acción del usuario y le avisa al servidor en PHP.
3. El PHP recibe los datos, Se comunica con la base de datos de forma segura usando un conector estándar (PDO), ejecuta su lógica y nos regresa una respuesta en formato JSON.

Si quieres ver esto en acción, revisa los archivos `login_futbolero.html` y `validar_login.php`. Ahí está el ejemplo más claro de cómo nos estamos comunicando.

---

## Reglas de desarrollo

Para mantener el código limpio y seguro, todos seguimos estas reglas:

* **Prevención de inyecciones SQL:** Queda prohibida la concatenación directa de variables en las consultas. El estándar del proyecto es utilizar PDO con sentencias preparadas (`prepare` y `execute`).
* **Estándar de respuestas:** Todo endpoint de PHP debe retornar siempre un JSON con esta estructura exacta: `{"success": true/false, "mensaje": "..."}`.
* **Seguridad de contraseñas:** Las contraseñas no se almacenan en texto plano bajo ninguna circunstancia. Es obligatorio encriptarlas utilizando `password_hash()` y validarlas con `password_verify()`.
* **Manejo de errores (Try-catch):** Si un archivo PHP realiza una transacción o consulta compleja en la base de datos, debe ir dentro de un bloque try-catch. Así, si ocurre un error en el servidor, se puede devolver un mensaje de error controlado en el JSON evitando que la aplicación falle visualmente.

## Mapa del Proyecto: Para qué sirve cada archivo y carpeta
Toda la aplicación está organizada de forma directa en el directorio raíz para facilitar la comunicación entre la vista y los endpoints:

CLUB_LEON/
│
├── INFRAESTRUCTURA Y CONFIGURACIÓN
│   ├── Dockerfile                # Define la imagen de PHP 8.2 con Apache y extensiones PDO activas.
│   ├── docker-compose.yml        # Configura y levanta los servicios Web (puerto 8080) y Base de Datos (puerto 3307).
│   ├── init.sql                  # Estructura completa de la base de datos (tablas) y datos iniciales de prueba.
│   └── conexion.php              # Archivo maestro de conexión a MySQL mediante PDO con manejo de errores.
│
├── MÓDULO 1: AUTENTICACIÓN Y SESIONES
│   ├── login_futbolero.html     # Interfaz gráfica de inicio de sesión con estilo deportivo.
│   ├── validar_login.php        # Endpoint: Comprueba usuario/contraseña con BCrypt e inicia la sesión PHP.
│   ├── verificar_sesion.php     # Endpoint: Verifica si el usuario tiene permiso para estar dentro del sistema.
│   ├── cerrar_sesion.php        # Endpoint: Destruye la sesión activa y redirige al login.
│   └── menu-opciones.html       # Panel de control principal (Dashboard) con acceso a todos los módulos.
│
├── MÓDULO 2: GESTIÓN DE USUARIOS Y ROLES
│   ├── gestionar-usuarios.html  # Pantalla que lista los usuarios existentes y permite eliminarlos.
│   ├── agregar-usuario.html     # Formulario para registrar administradores o encargados de área.
│   ├── guardar_usuario.php      # Endpoint: Encripta la contraseña e inserta el nuevo usuario en la BD.
│   ├── obtener_usuarios.php     # Endpoint: Retorna la lista de usuarios junto con el nombre de su rol.
│   ├── obtener_roles.php        # Endpoint: Retorna los roles disponibles para llenar listas desplegables.
│   └── eliminar_usuario.php     # Endpoint: Elimina a un usuario por su identificador único (ID).
│
├── MÓDULO 3: EQUIPOS Y JUGADORES
│   ├── registro-equipos.html    # Formulario para dar de alta clubes y ver la lista de participantes.
│   ├── registro-jugadores.html  # Formulario para registrar deportistas vinculados a su respectivo equipo.
│   ├── guardar_equipo.php       # Endpoint: Inserta un nuevo equipo en la base de datos.
│   ├── obtener_equipos.php      # Endpoint: Retorna el listado de todos los equipos registrados.
│   ├── eliminar_equipo.php      # Endpoint: Elimina un equipo del sistema.
│   └── guardar_jugador.php      # Endpoint: Registra los datos de un jugador (Nombre, CURP, Teléfono).
│
├── MÓDULO 4: CALENDARIZACIÓN Y ROLES DE JUEGO
│   ├── agregar-partido.html     # Formulario para programar partidos (Local vs Visitante, Cancha, Horario).
│   ├── rol-juego.html           # Vista ejecutiva del calendario de partidos.
│   ├── roles-juego-tabla.html   # Tabla oficial de partidos programados con botón de exportación a PDF.
│   ├── guardar_partido.php      # Endpoint: Registra un nuevo partido en el fixture oficial.
│   ├── obtener_partidos.php     # Endpoint: Retorna los partidos con los nombres de los equipos competidores.
│   └── eliminar_partido.php     # Endpoint: Cancela y elimina un partido programado.
│
├── MÓDULO 5: MARCADORES Y TABLA DE POSICIONES
│   ├── cargar-resultados.html   # Pantalla para registrar los goles finales de cada encuentro.
│   ├── tabla-posiciones.html    # Tabla de clasificación general (JJ, JG, JE, JP, GF, GC, DIF, PTS) descargable en PDF.
│   ├── guardar_resultado.php    # Endpoint: Calcula el ganador/perdedor e inserta las estadísticas del partido.
│   ├── obtener_posiciones.php   # Endpoint: Agrupa y calcula los puntajes acumulados de todos los equipos.
│   └── obtener_partidos_pendientes.php # Endpoint: Lista únicamente los partidos que faltan por jugar.
│
├── MÓDULO 6: CAJA, PAGOS Y RECIBOS
│   ├── registro-pagos.html      # Menú principal de selección de pagos (Inscripción, Arbitraje, Abonos).
│   ├── pago-inscripcion.html    # Registro de pago de inscripción ($1,500) + Generación de ticket con jsPDF.
│   ├── pago-arbitraje.html      # Registro de pago de arbitraje por jornada ($350) + Generación de ticket con jsPDF.
│   ├── pago-abonos.html         # Registro de abonos parciales + Generación de comprobante con jsPDF.
│   ├── guardar_inscripcion.php  # Endpoint: Guarda el pago de inscripción en la base de datos.
│   ├── guardar_arbitraje.php    # Endpoint: Guarda el pago de arbitraje.
│   └── guardar_abono.php        # Endpoint: Guarda una parcialidad de abono.
│
└── RECURSOS DE ESTILO Y SCRIPTS
    ├── css/login.css            # Estilos visuales del login (cancha de fútbol, animaciones y tarjetas).
    └── js/login.js              # Lógica de validación, animación y visibilidad de contraseñas.
    
