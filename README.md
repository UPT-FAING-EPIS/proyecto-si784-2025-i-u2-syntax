# 🎓 Sistema de Mentoría Académica – AMS

**AMS-UPT** es una plataforma web integral para la gestión de mentorías universitarias, creada para mejorar el rendimiento académico, automatizar procesos y fomentar el acompañamiento personalizado.

---

## 📘 Descripción General

Este sistema fue desarrollado por estudiantes de Ingeniería de Sistemas de la **Universidad Privada de Tacna**, con el propósito de:

- Reducir la deserción universitaria.
- Aumentar el rendimiento académico.
- Optimizar la carga docente.
- Conectar estudiantes con mentores de forma efectiva.

Incluye módulos para la gestión de usuarios, programación de clases, registro de asistencia, evaluación y generación de reportes académicos.

---

## 🧪 Tecnologías Utilizadas

| Categoría       | Tecnologías                           |
|------------------|----------------------------------------|
| Lenguaje         | PHP 8                                  |
| Base de Datos    | MySQL 8 (HeidiSQL)                     |
| Frontend         | HTML5, CSS3, Bootstrap 5               |
| Backend          | Apache + PHP-FPM                       |
| Local Dev        | XAMPP                                  |
| DevOps           | Git, GitHub, Terraform, Infracost      |
| Gestión Ágil     | Jira (Scrum)                           |
| UI/UX            | Figma, Balsamiq                        |

---

## ⚙️ Instalación y Ejecución Local
- Clona el repositorio:
   ```bash
   git clone https://github.com/usuario/proyecto-ams.git
    ```
- Importa la base de datos desde el archivo `ams_db.sql` a tu servidor MySQL.
- Configura el archivo `config/Conexion.php` con tus credenciales locales.
- Asegúrate de tener habilitadas las extensiones `mysqli` y `openssl` en tu `php.ini`.
- Copia el proyecto en htdocs y abre en navegador:

   http://localhost/web_asm/public/index.php

---

## 🚀 Funcionalidades Principales

### 🔐 Login y Roles
- Acceso diferenciado para administrador, estudiante y mentor.

### 📅 Gestión Académica
- Registro y asignación de clases, aulas, horarios y ciclos.
- Emparejamiento mentor–estudiante.

### 🎯 Seguimiento y Evaluación
- Registro de asistencia.
- Calificaciones y observaciones.
- Reportes de rendimiento.

### 📊 Administración y Reportes
- Panel de control.
- Visualización de métricas.
- Notificaciones y alertas internas.

---

## 📷 Capturas de Pantalla

### 🔑 Pantalla de Login
![Login](ruta/a/captura_login.png)

### 🏠 Dashboard del Mentor
![Dashboard Mentor](ruta/a/captura_dashboard_mentor.png)

### 📚 Vista de Clases Asignadas
![Clases Asignadas](ruta/a/captura_clases_asignadas.png)

### 📝 Calificación de Estudiantes
![Calificaciones](ruta/a/captura_calificaciones.png)

### 📈 Vista de Reportes de Clases
![Reportes](ruta/a/captura_reportes.png)

### 👨‍🎓 Vista del Estudiante – Clases Asignadas
![Clases Estudiante](ruta/a/captura_estudiante_clases.png)

### 📆 Programación de Mentorías
![Programación](ruta/a/captura_programacion.png)

---

## 👥 Autores

- 👨‍💻 Gregory Brandon Huanca Merma – Full Stack Developer  
- 👨‍💻 Joan Cristian Medina Quispe – Backend Developer  
- 🎨 Rodrigo Samael Adonai Lira Álvarez – UI/UX Specialist  

---

## 📜 Licencia

Proyecto académico desarrollado como parte del curso  
**Calidad y Pruebas de Software – Universidad Privada de Tacna.**  
**Uso exclusivo con fines educativos.**

---

## 🛣️ Roadmap del Proyecto

- ✅ Registro y autenticación de usuarios
- ✅ Gestión de clases y asignación de mentorías
- ✅ Evaluación y seguimiento académico
- 🔄 Implementación de análisis predictivo *(en desarrollo)*
- 🔄 Versión móvil multiplataforma *(planeado)*
- 🔄 Integración con sistemas académicos UPT *(planeado)*

---

Desarrollado con ❤️ por estudiantes de Ingeniería de Sistemas – UPT
