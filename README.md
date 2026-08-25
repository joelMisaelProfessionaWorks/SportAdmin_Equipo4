# SportAdmin_Equipo4
Trabajo de Software
# ⚽ SportAdmin - Club Deportivo León Saltillo

---

## Descripción del Proyecto

Este proyecto tiene como finalidad el desarrollo de una aplicación de software para el **Club Deportivo León Saltillo**, orientada a automatizar y optimizar los procesos administrativos y deportivos de la liga.

Después de realizar el análisis y la investigación de la organización, se identificaron diversas áreas de oportunidad relacionadas con:

- Registro de equipos y jugadores  
- Elaboración del rol de juegos  
- Control de pagos  
- Actualización de la tabla de posiciones  

A partir de esta etapa, el proyecto entra en la fase de **diseño y construcción**, donde se comenzará con la creación y programación del sistema, tomando como base los requerimientos definidos y los procesos reales del club.

La aplicación permitirá:

- Centralizar la información  
- Reducir errores derivados de la gestión manual  
- Mejorar la comunicación entre los responsables de la liga y los equipos  

El desarrollo del sistema se realizará siguiendo la **metodología RUP**, permitiendo avanzar de manera iterativa e incremental, asegurando que cada módulo cumpla con las necesidades del club.


## Tecnologías Utilizadas

El sistema está basado en una arquitectura de 3 capas:

### Capa 1 — Frontend / Presentación
- HTML5  
- CSS3  
- JavaScript  

### Capa 2 — Backend
- PHP  
- Lógica de negocio  
- API REST (comunicación con frontend)  

### Capa 3 — Base de Datos
- MySQL  

### Servidor / Infraestructura
- Apache / Nginx  
- Protocolo HTTPS  

---

## Funcionalidades Principales

- Autenticación de usuarios  
- Registro de equipos y jugadores  
- Generación de rol de juegos  
- Cálculo de tabla de posiciones  
- Gestión de pagos e inscripciones  
- Control de abonos  
- Gestión de árbitros  
- CRUD completo por módulos  

---

## Integrantes
 
- Jesús de Santiago Martínez
- Joel Misael Lejia de la Rosa
- Michelle Peña Flores

## 5. Resumen de la Pila Tecnológica (Tech Stack)

### Ficha Técnica del Proyecto

| Componente | Herramienta / Tecnología | Versión | Propósito en el Proyecto |
| :--- | :--- | :--- | :--- |
| **Backend** | PHP | `8.2` | Lógica de negocio, consumo de APIs JSON, sesiones y autenticación segura con BCrypt |
| **Base de Datos** | MySQL | `8.0` (`8.0.46`) | Almacenamiento relacional (InnoDB, utf8mb4) para usuarios, equipos, roles, partidos y pagos |
| **Servidor Web** | Apache HTTP Server | `2.4` | Servidor web con módulo `mod_rewrite` habilitado |
| **Contenedores** | Docker & Docker Compose | `Compose 3.8` | Orquestación del entorno web y base de datos con volúmenes persistentes |
| **Exportación PDF** | jsPDF | `2.5.1` | Generación e impresión de tickets de pago y recibos de inscripción |
| **Exportación PDF** | html2pdf.js | `0.10.1` | Conversión y descarga de tablas HTML a documentos PDF (Roles y Posiciones) |
| **Tipografías** | Google Fonts | Web API | Fuentes deportivas: *Bebas Neue* (títulos/marcadores) y *Rajdhani* (datos/tablas) |
| **Frontend** | HTML5 / CSS3 / JavaScript | ES6+ | Interfaz reactiva personalizada (Glassmorphism, SVG vectorial) y consumo asíncrono vía Fetch API |
| **Sistema Operativo** | Windows / Linux Container | Win64 / Alpine | Entorno de desarrollo nativo en Windows 64-bit y despliegue en contenedores Linux |

### Detalle de Módulos y Arquitectura

* **Patrón de Arquitectura:** Cliente-Servidor desacoplado mediante APIs RESTful en JSON.
* **Seguridad:** Encriptación de credenciales mediante `password_hash()` (algoritmo BCRYPT) y sentencias preparadas PDO contra inyecciones SQL.
* **Persistencia:** Base de datos relacional con integridad referencial (`CASCADE` / `RESTRICT`).
