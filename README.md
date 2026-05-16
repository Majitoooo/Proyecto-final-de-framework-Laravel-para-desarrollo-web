# 🚀 CoWorkly

<div align="center">

<img width="140" alt="CoWorkly Logo" src="https://img.icons8.com/fluency/240/office.png"/>

### Plataforma inteligente para la gestión y reserva de espacios de coworking

Sistema web desarrollado con **Laravel + Vue + Inertia.js** para administrar espacios de coworking, reservas, usuarios y disponibilidad en tiempo real.

![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge&logo=laravel)
![Vue](https://img.shields.io/badge/Vue-3-42b883?style=for-the-badge&logo=vue.js)
![Inertia](https://img.shields.io/badge/Inertia.js-9553E9?style=for-the-badge)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-336791?style=for-the-badge&logo=postgresql)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-38BDF8?style=for-the-badge&logo=tailwindcss)
![Brevo](https://img.shields.io/badge/Brevo-Email-blue?style=for-the-badge)

</div>

---

# 📌 Descripción

**CoWorkly** es una plataforma web desarrollada para optimizar la administración y reserva de espacios de coworking.

El sistema permite a los usuarios consultar espacios disponibles, realizar reservas y gestionar sus horarios, mientras que los administradores pueden controlar usuarios, espacios, disponibilidad, bloqueos horarios y estadísticas generales del sistema.

El objetivo del proyecto es modernizar el proceso de administración de espacios colaborativos mediante una experiencia intuitiva, rápida y visualmente atractiva.

---

# ✨ Características principales

## 👤 Funcionalidades para usuarios

✅ Registro e inicio de sesión seguro  
✅ Consulta de espacios disponibles  
✅ Reserva de espacios coworking  
✅ Visualización de disponibilidad en calendario  
✅ Gestión de reservas personales  
✅ Confirmación de reservas  
✅ Interfaz intuitiva y moderna  

---

## 🛠️ Funcionalidades administrativas

✅ Dashboard administrativo con estadísticas  
✅ CRUD completo de espacios  
✅ CRUD de usuarios  
✅ Activación / desactivación de espacios  
✅ Eliminación segura de espacios  
✅ Gestión de disponibilidad por días y horarios  
✅ Bloqueo de franjas horarias  
✅ Calendario administrativo de reservas  
✅ Gestión de estados de reservas  
✅ Control de permisos por roles (admin/user)

---

# 🧠 Módulos principales

### 📅 Gestión de reservas

Permite a los usuarios reservar espacios según disponibilidad horaria y restricciones definidas por el administrador.

### 🏢 Gestión de espacios

Administración de:

- Oficinas privadas
- Salas de reuniones
- Cabinas
- Espacios coworking
- Auditorios
- Terrazas

Cada espacio cuenta con:

- Nombre
- Tipo
- Imagen
- Capacidad
- Precio por hora
- Reglas de uso
- Disponibilidad personalizada

### 👥 Gestión de usuarios

Administración de usuarios registrados:

- Crear usuarios
- Editar usuarios
- Cambiar roles
- Eliminar usuarios
- Validaciones de seguridad

### 📊 Dashboard administrativo

Visualización de métricas importantes:

- Reservas pendientes
- Reservas aprobadas
- Espacios activos
- Usuarios registrados
- Estadísticas visuales

---

# 🛠️ Tecnologías utilizadas

| Tecnología | Uso |
|------------|-----|
| Laravel 13 | Backend |
| Vue 3 | Frontend |
| Inertia.js | Comunicación SPA |
| Jetstream | Autenticación |
| PostgreSQL | Base de datos |
| TailwindCSS | Diseño UI |
| Vite | Build Tool |
| Brevo SMTP | Envío de correos |
| Ziggy | Rutas frontend |

---

# 🏗️ Arquitectura del sistema

El proyecto implementa una arquitectura basada en:

```text
Laravel MVC
│
├── Models
├── Controllers
├── Middleware
├── Policies
│
Vue 3 + Inertia
│
├── Pages
├── Components
├── Layouts

Se emplea Inertia.js para ofrecer una experiencia tipo SPA sin necesidad de API REST tradicional.

🔐 Roles del sistema
Usuario

Puede:

Ver espacios
Reservar espacios
Gestionar reservas
Consultar disponibilidad
Administrador

Puede:

Gestionar usuarios
Gestionar espacios
Editar disponibilidad
Bloquear horarios
Aprobar reservas
Visualizar estadísticas
📩 Sistema de correos

El proyecto utiliza Brevo SMTP para el envío de correos automáticos relacionados con:

Confirmación de reservas
Notificaciones del sistema
Mensajes transaccionales

Configuración mediante variables de entorno:

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=TU_USUARIO
MAIL_PASSWORD=TU_SMTP_KEY
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@coworkly.com
MAIL_FROM_NAME="CoWorkly"
🗄️ Base de datos

Motor utilizado:

PostgreSQL

El sistema utiliza migraciones y seeders para estructurar:

Usuarios
Espacios
Reservas
Disponibilidad
Bloqueos horarios
Reseñas
⚙️ Instalación local
1. Clonar repositorio
git clone https://github.com/Majitoooo/Proyecto-final-de-framework-Laravel-para-desarrollo-web.git

Entrar al proyecto:

cd Proyecto-final-de-framework-Laravel-para-desarrollo-web
2. Instalar dependencias
Backend
composer install
Frontend
npm install
3. Configurar entorno

Copiar archivo:

cp .env.example .env

Configurar:

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=coworkly
DB_USERNAME=postgres
DB_PASSWORD=tu_password
4. Generar key
php artisan key:generate
5. Ejecutar migraciones
php artisan migrate
6. Crear enlace storage
php artisan storage:link
7. Ejecutar proyecto

Backend:

php artisan serve

Frontend:

npm run dev
📸 Capturas del sistema
Landing Page

Agregar screenshot

Login / Register

Agregar screenshot

Dashboard administrativo

Agregar screenshot

Gestión de espacios

Agregar screenshot

Calendario de reservas

Agregar screenshot

🚀 Futuras mejoras
Integración de pagos online
Sistema de membresías
Reservas recurrentes
Estadísticas avanzadas
Aplicación móvil
Notificaciones en tiempo real
👩‍💻 Autora
María José Cordón Vasco

Proyecto desarrollado como trabajo final de Framework Laravel para Desarrollo Web.

GitHub:

Repositorio del proyecto

<div align="center">
✨ CoWorkly — Reserva espacios inteligentes
</div> ```