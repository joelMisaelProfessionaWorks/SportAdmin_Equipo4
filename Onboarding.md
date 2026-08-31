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

###  Grafo Visual de Archivos y Dependencias

```mermaid
graph LR
    subgraph S_AUTH [Autenticación]
        L_HTML["[[login_futbolero.html]]"] --> V_LOGIN["[[validar_login.php]]"]
        M_HTML["[[menu-opciones.html]]"] --> V_SESION["[[verificar_sesion.php]]"]
        M_HTML --> C_SESION["[[cerrar_sesion.php]]"]
    end

    subgraph S_USERS [Usuarios]
        U_HTML["[[gestionar-usuarios.html]]"] --> O_USER["[[obtener_usuarios.php]]"]
        U_HTML --> E_USER["[[eliminar_usuario.php]]"]
        AU_HTML["[[agregar-usuario.html]]"] --> G_USER["[[guardar_usuario.php]]"]
        AU_HTML --> O_ROLES["[[obtener_roles.php]]"]
    end

    subgraph S_TEAMS [Equipos y Jugadores]
        E_HTML["[[registro-equipos.html]]"] --> G_TEAM["[[guardar_equipo.php]]"]
        E_HTML --> O_TEAM["[[obtener_equipos.php]]"]
        E_HTML --> EL_TEAM["[[eliminar_equipo.php]]"]
        J_HTML["[[registro-jugadores.html]]"] --> G_JUG["[[guardar_jugador.php]]"]
        J_HTML --> O_TEAM
    end

    subgraph S_MATCHES [Fixture y Posiciones]
        AP_HTML["[[agregar-partido.html]]"] --> G_PART["[[guardar_partido.php]]"]
        RJ_HTML["[[roles-juego-tabla.html]]"] --> O_PART["[[obtener_partidos.php]]"]
        RJ_HTML --> E_PART["[[eliminar_partido.php]]"]
        CR_HTML["[[cargar-resultados.html]]"] --> G_RES["[[guardar_resultado.php]]"]
        CR_HTML --> O_PEND["[[obtener_partidos_pendientes.php]]"]
        TP_HTML["[[tabla-posiciones.html]]"] --> O_POS["[[obtener_posiciones.php]]"]
    end

    subgraph S_PAYMENTS [Finanzas y Caja]
        P_INS["[[pago-inscripcion.html]]"] --> G_INS["[[guardar_inscripcion.php]]"]
        P_ARB["[[pago-arbitraje.html]]"] --> G_ARB["[[guardar_arbitraje.php]]"]
        P_ABO["[[pago-abonos.html]]"] --> G_ABO["[[guardar_abono.php]]"]
    end

    subgraph S_CORE [Núcleo de Datos]
        CONEX["[[conexion.php]]"]
        INIT["[[init.sql]]"]
    end

    V_LOGIN --> CONEX
    G_USER --> CONEX
    O_USER --> CONEX
    G_TEAM --> CONEX
    O_TEAM --> CONEX
    G_JUG --> CONEX
    G_PART --> CONEX
    O_PART --> CONEX
    G_RES --> CONEX
    O_POS --> CONEX
    G_INS --> CONEX
    G_ARB --> CONEX
    G_ABO --> CONEX
    CONEX --> INIT
