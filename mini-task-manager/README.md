# Mini Task Manager (Proyecto de Gestión de Tareas)

Este es un proyecto simple de gestión de tareas construido con Laravel y Livewire, que demuestra funcionalidades básicas como listar, añadir, editar, eliminar, completar y reordenar tareas, incluyendo soporte para categorías, exportación a PDF/CSV y un tema oscuro.

## Características Principales

* Gestión completa de tareas (CRUD).
* Marcado de tareas como completadas/pendientes.
* Filtrado de tareas por estado (Todas, Pendientes, Completadas).
* Reordenamiento de tareas mediante "Drag and Drop" (arrastrar y soltar).
* Soporte para categorías con colores asociados.
* Exportación de la lista de tareas actual (con filtro aplicado) a formato PDF.
* Exportación de la lista de tareas actual (con filtro aplicado) a formato CSV.
* Alternancia entre modo claro y oscuro con persistencia de la preferencia del usuario.
* Notificaciones "Toast" para confirmaciones de acciones.

## Requisitos

Para ejecutar este proyecto localmente, necesitarás tener instalado:

* PHP >= 8.1
* Composer
* Node.js y npm
* Un sistema de base de datos.

## Instalación

Sigue estos pasos para poner el proyecto en funcionamiento en tu máquina:

1.  **Clonar el Repositorio:**
    ```bash
    git clone https://github.com/Jesusguz/upworTest.git
    cd mini-task-manager # Cambia al directorio del proyecto
    ```


2.  **Instalar Dependencias de Composer (Backend):**
    ```bash
    composer install
    ```

3.  **Configurar el Archivo `.env`:**
    * Copia el archivo de ejemplo:
        ```bash
        cp .env.example .env
        ```
    * Abre el archivo `.env` y configura las variables de entorno necesarias. La más importante es la configuración de la base de datos.

    * **Configuración de Base de Datos:** Este proyecto utiliza una base de datos llamada `minitodo`. Asegúrate de tener una base de datos con ese nombre creada en tu sistema de base de datos y configura las credenciales en el archivo `.env`:

        ```dotenv
        DB_CONNECTION=mysql # O tu conexión de BD (pgsql, sqlite, etc.)
        DB_HOST=127.0.0.1
        DB_PORT=3306 # O tu puerto de BD
        DB_DATABASE=minitodo # <--- ¡ASEGÚRATE DE QUE ESTE ES EL NOMBRE DE TU BASE DE DATOS!
        DB_USERNAME=tu_usuario_de_bd
        DB_PASSWORD=tu_contraseña_de_bd
        ```
      _(Reemplaza `tu_usuario_de_bd` y `tu_contraseña_de_bd` con tus credenciales.)_

    * Genera la clave de aplicación:
        ```bash
        php artisan key:generate
        ```

4.  **Configurar Base de Datos y Datos de Prueba (Opcional):**
    * Ejecuta las migraciones para crear las tablas en la base de datos:
        ```bash
        php artisan migrate
        ```

5.  **Instalar Dependencias de Node.js (Frontend):**
    ```bash
    npm install
    ```

6.  **Compilar Assets de Frontend:**
    * Para desarrollo
        ```bash
        npm run dev
        ```
    * Para producción (una sola vez):
        ```bash
        npm run build
        ```


7.  **Iniciar el Servidor de Desarrollo de Laravel:**
    ```bash
    php artisan serve
    ```

## Uso

Una vez que el servidor de desarrollo esté corriendo (normalmente en `http://127.0.0.1:8000`), puedes acceder a la aplicación en tu navegador web.

Deberías ver la interfaz de gestión de tareas donde puedes empezar a interactuar con las funcionalidades implementadas.

## Tecnologías Utilizadas

* Laravel
* Livewire
* Alpine.js (con plugin Persist)
* Tailwind CSS
* Sortable.js (para Drag and Drop)
* Dompdf (para exportación a PDF)
* SweetAlert2 (para notificaciones Toast)

## Contribuir

Las contribuciones son bienvenidas. Por favor, abre un "issue" o envía un "pull request" con tus mejoras.
