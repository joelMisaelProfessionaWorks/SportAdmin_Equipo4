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
2. La página detecta el clic o acción del usuario y le avisa al servidor en PHP mediante un `fetch()` en JS.
3. El PHP recibe los datos, se comunica con la base de datos de forma segura usando un conector estándar (PDO), ejecuta su lógica y nos regresa una respuesta en formato JSON.

---

## Reglas de desarrollo

Para mantener el código limpio y seguro, todos seguimos estas reglas:

* **Prevención de inyecciones SQL:** Queda prohibida la concatenación directa de variables en las consultas. El estándar del proyecto es utilizar PDO con sentencias preparadas (`prepare` y `execute`).
* **Estándar de respuestas:** Todo endpoint de PHP debe retornar siempre un JSON con esta estructura exacta: `{"success": true/false, "mensaje": "..."}`.
* **Seguridad de contraseñas:** Las contraseñas no se almacenan en texto plano bajo ninguna circunstancia. Es obligatorio encriptarlas utilizando `password_hash()` y validarlas con `password_verify()`.
* **Manejo de errores (Try-catch):** Si un archivo PHP realiza una transacción o consulta compleja en la base de datos, debe ir dentro de un bloque try-catch. Así, si ocurre un error en el servidor, se puede devolver un mensaje de error controlado en el JSON evitando que la aplicación falle visualmente.

---

## Mapa Detallado del Proyecto: Para qué sirve cada archivo

A continuación, se detalla el uso exacto de cada directorio y archivo en el sistema para comprender la arquitectura completa:

### Carpetas Frontend

* `/css/`: Almacena exclusivamente las hojas de estilo del proyecto. Si modificas el diseño de una pantalla, debes hacerlo aquí.
* `/js/`: Contiene toda la lógica del cliente. Aquí viven los scripts que toman los datos de los formularios HTML, aplican validaciones visuales y disparan las peticiones asíncronas (`fetch`) hacia los endpoints de PHP.

### Archivos de Configuración e Infraestructura

* `conexion.php`: Es el script más importante del backend. Contiene las credenciales y el objeto PDO que abre la conexión a MySQL. Todos los demás endpoints deben requerir este archivo.
* `init.sql`: Contiene las consultas DDL (Data Definition Language) para crear la base de datos desde cero, junto con los datos de prueba.
* `Dockerfile` y `docker-compose.yml`: Archivos de orquestación que construyen el servidor Apache con PHP 8.2 y el motor MySQL en contenedores aislados.

### Módulo 1: Autenticación y Seguridad

Maneja el acceso, los logins y los cifrados.

* `login_futbolero.html`: Interfaz de inicio de sesión.
* `validar_login.php`: Recibe credenciales, busca el correo en la base de datos y verifica el hash de la contraseña.
* `verificar_sesion.php`: Script de validación que se incluye en las páginas protegidas para asegurar que el usuario tenga sesión activa.
* `cerrar_sesion.php`: Destruye las variables de sesión y redirige al login.

### Módulo 2: Panel de Administración

* `paginaS.html`: El Dashboard principal o menú de inicio una vez que el usuario ingresa al sistema.
* `menu-opciones.html`: Vista de navegación secundaria.

### Módulo 3: Equipos y Jugadores

Gestión del registro deportivo.

* **Vistas:** `registro-equipos.html`, `registro-jugadores.html`.
* **Endpoints de creación:** `guardar_equipo.php`, `guardar_jugador.php`.
* **Endpoints de consulta:** `obtener_equipos.php` (devuelve el catálogo de equipos).
* **Endpoints de eliminación:** `eliminar_equipo.php`.

### Módulo 4: Torneo, Partidos y Resultados

Administra el transcurso de la liga y la tabla de posiciones.

* **Vistas de gestión:** `agregar-partido.html`, `rol-juego.html`, `roles-juego-tabla.html`.
* **Vistas de resultados:** `cargar-resultados.html`, `tabla-posiciones.html`.
* **Endpoints de guardado:** `guardar_partido.php`, `guardar_resultado.php`.
* **Endpoints de consulta:** `obtener_partidos.php`, `obtener_partidos_pendientes.php`, `obtener_posiciones.php`.
* **Endpoints de eliminación:** `eliminar_partido.php`.

### Módulo 5: Finanzas e Ingresos

Control económico del club.

* **Vistas:** `pago-abonos.html`, `pago-arbitraje.html`, `pago-inscripcion.html`, `registro-pagos.html`.
* **Endpoints de guardado:** `guardar_abono.php`, `guardar_arbitraje.php`, `guardar_inscripcion.php`.

### Módulo 6: Administración de Usuarios (Staff)

Manejo de los administradores y encargados de la liga.

* **Vistas:** `agregar-usuario.html`, `gestionar-usuarios.html`.
* **Endpoints de base de datos:** `guardar_usuario.php`, `obtener_usuarios.php`, `eliminar_usuario.php`, `obtener_roles.php`.

---

## Guía Técnica: ¿Cómo crear un Endpoint nuevo?

Si el sistema necesita crecer y te asignan crear una nueva función (por ejemplo, "Actualizar un equipo"), debes seguir exactamente este flujo para no romper la arquitectura:

1. **Crear el Archivo:** Crea un archivo `.php` nuevo en la carpeta raíz, siguiendo la convención de nombres de acción (ej. `actualizar_equipo.php`).
2. **Configurar la cabecera JSON:** La primera línea de tu PHP debe ser `header('Content-Type: application/json');`.
3. **Leer los datos del Frontend:** Utiliza `$data = json_decode(file_get_contents('php://input'), true);` para atrapar lo que envió JavaScript.
4. **Importar Conexión:** Incluye obligatoriamente el archivo `require_once 'conexion.php';`.
5. **Estructura Segura:** Abre un bloque `try { ... } catch(Exception $e) { ... }`.
6. **Consultas con PDO:** Adentro del `try`, escribe tu query usando PDO. Por ejemplo:
   ```php
   $stmt =$pdo->prepare("UPDATE equipos SET nombre = ? WHERE id = ?");
   $stmt->execute([$nombre,$id]);
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
