# API de Gestión de Tareas (Task Manager)

## video de app funcionando

https://drive.google.com/file/d/1GWwzTWgdFRuBuPM8ApJetTqgRArtQ6au/view?usp=drive_link

Esta es una API RESTful construida con Laravel para gestionar tareas de usuarios. Permite a los usuarios registrarse, iniciar sesión y realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre sus propias tareas de forma segura.

## Características Principales

-   **Autenticación:** Sistema de registro, login y logout basado en tokens con Laravel Sanctum.
-   **Gestión de Tareas:** CRUD completo para las tareas (`Task`).
-   **Autorización:** Los usuarios solo pueden ver, actualizar o eliminar las tareas que ellos mismos han creado.
-   **Validación:** Uso de Form Requests para validar los datos de entrada de forma limpia y reutilizable.
-   **Transformación de Datos:** Uso de API Resources para formatear las respuestas JSON de manera consistente.
-   **Endpoint de Salud:** Una ruta `/health` para verificar el estado de la aplicación y la conexión a la base de datos.

---

## Instalación y Configuración

Sigue estos pasos para poner en marcha el proyecto en tu entorno local.

0. **Clona el repositorio:** se puede acceder a la API a traves de la URL https://pruebatecnica.moisescorchodev.com/api/v1

1. **Clona el repositorio:**

    ```bash
    git clone [https://tu-repositorio.git](https://tu-repositorio.git)
    cd tu-proyecto
    ```

2. **Instala las dependencias:**

    ```bash
    composer install
    ```

3. **Configura el entorno:**
   Copia el archivo de ejemplo `.env.example` a `.env` y edítalo con tus credenciales de base de datos.

    ```bash
    cp .env.example .env
    ```

4. **Genera la clave de la aplicación:**

    ```bash
    php artisan key:generate
    ```

5. **Ejecuta las migraciones:**
   Esto creará las tablas `users` y `tasks` en tu base de datos.

    ```bash
    php artisan migrate
    ```

6. **Inicia el servidor:**
    ```bash
    php artisan serve
    ```

---

## Endpoints de la API

La URL base para todos los endpoints es `/api/v1`.

| Método        | URI             | Descripción                                      | Autenticación |
| :------------ | :-------------- | :----------------------------------------------- | :------------ |
| **POST**      | `/register`     | Registra un nuevo usuario y devuelve un token.   | No requerida  |
| **POST**      | `/login`        | Autentica un usuario y devuelve un token.        | No requerida  |
| **POST**      | `/logout`       | Invalida el token del usuario autenticado.       | Requerida     |
| **GET**       | `/user`         | Obtiene la información del usuario autenticado.  | Requerida     |
| **GET**       | `/tasks`        | Lista todas las tareas del usuario autenticado.  | Requerida     |
| **POST**      | `/tasks`        | Crea una nueva tarea.                            | Requerida     |
| **GET**       | `/tasks/{task}` | Muestra los detalles de una tarea específica.    | Requerida     |
| **PUT/PATCH** | `/tasks/{task}` | Actualiza una tarea específica.                  | Requerida     |
| **DELETE**    | `/tasks/{task}` | Elimina una tarea específica.                    | Requerida     |
| **GET**       | `/health`       | Verifica el estado de la API y la base de datos. | No requerida  |

---

## Arquitectura y Principios de Diseño

El código de esta API ha sido estructurado siguiendo las mejores prácticas de la industria, como **Clean Code** y los principios **SOLID**, para garantizar que sea mantenible, escalable y fácil de entender.

### Clean Code

El concepto de "Código Limpio" se refiere a escribir código que sea fácil de leer y de modificar.

-   **Nombres Descriptivos:** Las clases, métodos y variables tienen nombres que revelan su intención.

    -   `TaskController`: Claramente maneja las peticiones HTTP para el recurso `Task`.
    -   `SaveTaskRequest`: Su nombre indica que se usa para validar la petición de guardado de una tarea.
    -   `LoginController`: Se dedica exclusivamente a la lógica de inicio de sesión.

-   **Métodos Cortos y Enfocados:** Cada método tiene una única y clara responsabilidad.

    -   En `TaskController`, el método `store()` solo se encarga de crear la tarea. Delega la validación a `SaveTaskRequest` y la autorización a `TaskPolicy`.
    -   Los controladores de autenticación (`LoginController`, `RegisterController`, `LogoutController`) utilizan el método mágico `__invoke()`, lo que los convierte en _controladores de acción única_. Esto refuerza la idea de que cada clase hace una sola cosa.

-   **Organización del Código:** Laravel, por defecto, promueve una estructura de directorios limpia. Este proyecto la aprovecha separando las responsabilidades:
    -   `app/Http/Controllers`: Lógica para manejar peticiones HTTP.
    -   `app/Models`: Representación de los datos y sus relaciones (Eloquent).
    -   `app/Http/Requests`: Clases para la validación de peticiones.
    -   `app/Http/Resources`: Clases para transformar los modelos a formato JSON.
    -   `app/Policies`: Lógica de autorización para proteger los modelos.

### Principios SOLID

Los principios SOLID son un conjunto de cinco principios de diseño que ayudan a crear software más robusto y mantenible.

#### S - Principio de Responsabilidad Única (Single Responsibility Principle)

_Un componente debe tener una, y solo una, razón para cambiar._

Este es el principio más visible en la estructura del proyecto. Cada clase tiene una única responsabilidad bien definida:

-   **`TaskController`**: Su única responsabilidad es recibir peticiones HTTP relacionadas con tareas y devolver respuestas HTTP. No valida datos, no define reglas de negocio ni formatea la salida.
-   **`SaveTaskRequest`**: Su única responsabilidad es definir las reglas de validación para crear o actualizar una tarea.
-   **`TaskPolicy`**: Su única responsabilidad es definir quién puede y quién no puede realizar acciones sobre un modelo `Task`.
-   **`TaskResource`**: Su única responsabilidad es transformar un modelo `Task` en un array para la respuesta JSON, controlando qué datos se exponen.

## los demas principios no son tratados de manera especifica, pues la Api para la prueba es pequeña y no se da tiene la oportunidad para abstraer u optimizar mas.
