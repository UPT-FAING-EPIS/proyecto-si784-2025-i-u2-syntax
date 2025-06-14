# Sistema de Mentoría Académica - AMS
Una plataforma integral para la gestión de mentorías universitarias y la mejora del rendimiento académico.

## 📝 Descripción General
El Sistema de Mentoría Académica (AMS) es una plataforma web diseñada para automatizar la gestión de tutorías y sesiones de refuerzo académico en universidades, con un enfoque inicial en la Escuela Profesional de Ingeniería de Sistemas de la Universidad Privada de Tacna.

Resuelve problemas clave como la alta tasa de deserción, el bajo rendimiento estudiantil, la sobrecarga de docentes y la falta de acompañamiento personalizado. Facilita el emparejamiento entre mentores y estudiantes, la programación de clases, el seguimiento del progreso y la generación de informes.

## 🔧 Tecnologías Utilizadas

- PHP 8 (nativo)
- MySQL 8 (gestión con HeidiSQL)
- HTML5 + CSS3
- Bootstrap 5
- Apache + PHP-FPM
- XAMPP (modo local)
- Git y GitHub
- Terraform + Infracost (para estimación de infraestructura)
- Jira (gestión ágil con Scrum)
- Figma y Balsamiq (UI/UX)

## ⚙️ Instalación / Deploy

1. Clona este repositorio:
   ```bash
   git clone https://github.com/usuario/proyecto-ams.git
   ```

2. Configura la base de datos ejecutando el script `ams_db.sql` en MySQL.

3. Copia los archivos del sistema en la carpeta `htdocs` de XAMPP o súbelos a tu servidor web.

4. Inicia Apache y MySQL desde el panel de XAMPP.

5. Abre el navegador y accede a `http://localhost/ams`.

## 🧩 Configuración del Entorno

- Modificar el archivo `config/Conexion.php` con las credenciales correctas de la base de datos.
- Asegurar que los módulos `mysqli` y `openssl` estén habilitados en `php.ini`.
- Para despliegue en nube (AWS), configurar la instancia EC2, habilitar el puerto 80/443 y subir el código.

## 🚀 Uso del Sistema

1. **Login**:
   - Administrador: `admin / admin123`
   - Estudiante o Mentor: credenciales registradas.

2. **Gestión Académica**:
   - Crear usuarios (mentores y estudiantes)
   - Programar sesiones de mentoría
   - Asignar aulas y horarios

3. **Seguimiento**:
   - Registrar asistencia y comentarios
   - Ver historial de clases y rendimiento

4. **Administración**:
   - Panel de reportes
   - Asignación automática de mentorías
   - Sistema de notificaciones y alertas

## 📷 Capturas de Pantalla

> *(Puedes agregar aquí imágenes del sistema o capturas de las vistas principales como login, dashboard, programación de clases, reportes, etc.)*

## 👥 Autores y Colaboradores

- **Gregory Brandon Huanca Merma** – Desarrollador FullStack
- **Joan Cristian Medina Quispe** – Desarrollador Backend
- **Rodrigo Samael Adonai Lira Álvarez** – Especialista en UI/UX

## 📝 Licencia

Este proyecto fue desarrollado con fines académicos como parte del curso **Calidad y Pruebas de Software** de la Universidad Privada de Tacna.  
**Uso educativo y sin fines comerciales.**

## 📈 Estado del Proyecto / Roadmap

- ✅ Registro y autenticación de usuarios
- ✅ Gestión de clases y asignación de aulas
- ✅ Seguimiento de asistencia y rendimiento
- ✅ Panel de administración y reportes
- 🔄 Implementación de análisis predictivo (en progreso)
- 🔄 Versión móvil multiplataforma (planeado)
- 🔄 Integración con sistemas académicos existentes (planeado)

---
Desarrollado con ❤️ por estudiantes de Ingeniería de Sistemas - UPT
