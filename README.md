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

**CoWorkly** es una plataforma web diseñada para optimizar la gestión y reserva de espacios de coworking, permitiendo administrar puestos de trabajo, salas de reuniones y disponibilidad de horarios de forma digital.

El sistema permite a los usuarios consultar espacios disponibles, visualizar horarios, reservar espacios y administrar sus reservas. Por otro lado, los administradores tienen control completo sobre usuarios, espacios, disponibilidad, bloqueos horarios, calendario y estadísticas del sistema.

Este proyecto fue desarrollado como **proyecto final de Framework Laravel para Desarrollo Web**, implementando buenas prácticas de desarrollo web moderno con Laravel, Vue e Inertia.js.

---

## ✨ Características principales

### 👤 Funcionalidades para usuarios

✅ Registro e inicio de sesión seguro  
✅ Consulta de espacios disponibles  
✅ Visualización de disponibilidad por fechas  
✅ Reserva de espacios coworking  
✅ Gestión de reservas personales  
✅ Visualización de calendario de reservas  
✅ Confirmación de acciones mediante correo electrónico  
✅ Diseño moderno e intuitivo  

---

### 🛠️ Funcionalidades administrativas

✅ Dashboard administrativo con estadísticas  
✅ CRUD completo de espacios  
✅ CRUD completo de usuarios  
✅ Activar / desactivar espacios  
✅ Eliminación segura de espacios  
✅ Gestión de disponibilidad por día y horario  
✅ Bloqueo de franjas horarias  
✅ Calendario administrativo de reservas  
✅ Gestión de roles (`admin` / `user`)  
✅ Visualización de métricas del sistema  

---

# 🧠 ¿Cómo funciona el sistema?

## Flujo del usuario

1. El usuario se registra o inicia sesión.
2. Ingresa al catálogo de espacios disponibles.
3. Selecciona el espacio deseado.
4. Consulta horarios disponibles.
5. Realiza la reserva.
6. El sistema registra la reserva y actualiza disponibilidad.

---

## Flujo del administrador

El administrador puede:

- Crear espacios
- Editar espacios
- Activar o desactivar espacios
- Eliminar espacios sin reservas asociadas
- Administrar usuarios
- Cambiar roles de usuarios
- Configurar horarios de disponibilidad
- Bloquear franjas horarias
- Visualizar reservas desde calendario
- Ver estadísticas del sistema

---

# 🏢 Gestión de espacios

Cada espacio dentro del sistema puede contener:

- Nombre
- Tipo de espacio
- Imagen
- Capacidad de personas
- Descripción
- Reglas de uso
- Precio por hora
- Disponibilidad personalizada
- Estado (activo/inactivo)

Ejemplos de espacios:

- Puestos coworking
- Oficinas privadas
- Salas de reuniones
- Cabinas de videollamadas
- Auditorios
- Terrazas coworking

---

# 🔐 Roles del sistema

## Usuario

Puede:

- Ver espacios
- Realizar reservas
- Consultar disponibilidad
- Gestionar sus reservas

## Administrador

Puede:

- Gestionar espacios
- Gestionar usuarios
- Gestionar disponibilidad
- Gestionar reservas
- Bloquear horarios
- Ver estadísticas

---

# 👤 Usuario administrador por defecto

El proyecto incluye un usuario administrador inicial para pruebas locales:

```txt
Correo:
admin@gmail.com

Contraseña:
admin1234
```

También es posible cambiar el rol de cualquier usuario desde el **CRUD de usuarios**.

---

# 🛠️ Tecnologías utilizadas

| Tecnología | Uso |
|------------|-----|
| Laravel 13 | Backend |
| Vue 3 | Frontend |
| Inertia.js | Comunicación SPA |
| Jetstream | Autenticación |
| Fortify | Seguridad y autenticación |
| Sanctum | Manejo de autenticación |
| PostgreSQL | Base de datos |
| TailwindCSS | Diseño UI |
| Vite | Compilación frontend |
| Axios | Peticiones HTTP |
| Chart.js | Estadísticas |
| Vue ChartJS | Integración de gráficos |
| Ziggy | Rutas frontend |
| Brevo SMTP | Envío de correos |

---

# 📦 Dependencias principales

## Backend (Composer)

```text
Laravel Framework
Jetstream
Fortify
Sanctum
Inertia Laravel
Tinker
Pint
PHPUnit
Google2FA
Ziggy
```

## Frontend (NPM)

```text
Vue 3
Inertia Vue 3
TailwindCSS
Axios
Chart.js
Vue ChartJS
Vite
Concurrently
Tailwind Forms
Tailwind Typography
```

---

# 🏗️ Arquitectura del sistema

El proyecto implementa arquitectura:

```text
Laravel MVC
│
├── Models
├── Controllers
├── Middleware
├── Policies
│
Vue 3 + Inertia.js
│
├── Pages
├── Components
├── Layouts
```

Se utiliza **Inertia.js** para brindar experiencia tipo SPA sin necesidad de desarrollar una API REST separada.

---

# 🗄️ Base de datos

Motor utilizado:

```text
PostgreSQL
```

El sistema administra información relacionada con:

- Usuarios
- Espacios
- Reservas
- Disponibilidad
- Bloqueos horarios
- Reseñas

---

# 📩 Sistema de correos (Brevo SMTP)

El proyecto utiliza **Brevo SMTP** para envío de correos automáticos.

## Crear cuenta en Brevo

1. Registrarse en:

https://www.brevo.com/

2. Ir a:

```text
SMTP & API
```

3. Crear una **SMTP Key**.

4. Copiar la key generada.

---

## Configuración `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo_brevo
MAIL_PASSWORD=tu_smtp_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@coworkly.com
MAIL_FROM_NAME="CoWorkly"
```

Después ejecutar:

```bash
php artisan optimize:clear
```

---

# ⚙️ Instalación local

## 1. Clonar proyecto

```bash
git clone https://github.com/Majitoooo/Proyecto-final-de-framework-Laravel-para-desarrollo-web.git
```

Entrar al proyecto:

```bash
cd Proyecto-final-de-framework-Laravel-para-desarrollo-web
```

---

## 2. Instalar dependencias backend

```bash
composer install
```

---

## 3. Instalar dependencias frontend

```bash
npm install
```

---

## 4. Configurar entorno

Copiar `.env`

```bash
cp .env.example .env
```

---

## 5. Configurar PostgreSQL

Editar `.env`

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=coworkly
DB_USERNAME=postgres
DB_PASSWORD=tu_password
```

---

## 6. Generar APP KEY

```bash
php artisan key:generate
```

---

## 7. Ejecutar migraciones

```bash
php artisan migrate
```

---

## 8. Crear enlace de imágenes

```bash
php artisan storage:link
```

---

## 9. Ejecutar aplicación

### Método recomendado

Ejecuta todo junto:

```bash
composer run dev
```

Esto inicia:

- Laravel Server
- Queue Listener
- Logs
- Vite

---

### O manualmente

Backend:

```bash
php artisan serve
```

Frontend:

```bash
npm run dev
```

---

---

## 10. Ejecutar tareas automáticas (Scheduler)

CoWorkly utiliza tareas programadas de Laravel para automatizar procesos internos, como:

- Finalizar reservas vencidas automáticamente
- Actualizar estados de reservas
- Ejecutar procesos periódicos del sistema

Para que esto funcione correctamente en entorno local, debes ejecutar el scheduler de Laravel en una terminal adicional:

```bash
php artisan schedule:work
```

Este proceso debe permanecer ejecutándose mientras la aplicación esté activa.

### ¿Qué hace?

El sistema revisa constantemente las reservas activas y, cuando la hora final de una reserva ya ha pasado, automáticamente:

✅ Cambia el estado a `finished`  
✅ Finaliza la reserva  
✅ Envía correo de finalización al usuario mediante Brevo SMTP

### Comando utilizado internamente

```bash
php artisan reservations:finish
```

Este comando es ejecutado automáticamente cada minuto por el scheduler de Laravel.

# 📊 Dashboard administrativo

El sistema cuenta con un dashboard que permite visualizar:

- Espacios activos
- Usuarios registrados
- Reservas
- Estadísticas visuales
- Gráficos del sistema

# 🚀 Futuras mejoras

- Sistema de pagos online
- Reservas recurrentes
- Notificaciones en tiempo real
- Membresías premium
- Aplicación móvil
- Reportes avanzados

---

# 👩‍💻 Autora

### María José Cordón Vasco

Proyecto desarrollado para la asignatura:
        
**Framework Laravel para Desarrollo Web**

---

<div align="center">

### ✨ CoWorkly — Reserva espacios inteligentes

</div>
