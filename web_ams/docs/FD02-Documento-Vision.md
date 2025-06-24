<center>

![./media/logo-upt.png](./media/logo-upt.png)

**UNIVERSIDAD PRIVADA DE TACNA**

**FACULTAD DE INGENIERÍA**

**Escuela Profesional de Ingeniería de Sistemas**

**PROYECTO DE UNIDAD I**  
**“SISTEMA DE MENTORÍA ACADÉMICA - AMS”**

Curso: _Calidad y Pruebas de Software_  
Docente: _Mag. Ing. Patrick Cuadros Quiroga_

Integrantes:  
- Huanca Merma, Gregory Brandon (2022073898)  
- Medina Quispe, Joan Cristian (2022074255)  
- Lira Alvarez, Rodrigo Samael Adonai (2019063331)

**Tacna – Perú**  
_2025_

</center>

Sistema de Mentoría Académica  
Documento de Visión  
Versión 2.0

---

# ÍNDICE

1. [INTRODUCCIÓN](#1-introduccion)  
   1.1 [Propósito](#11-proposito)  
   1.2 [Alcance](#12-alcance)  
   1.3 [Definiciones, Siglas y Abreviaturas](#13-definiciones-siglas-y-abreviaturas)  
   1.4 [Referencias](#14-referencias)  
   1.5 [Visión General](#15-vision-general)  

2. [POSICIONAMIENTO](#2-posicionamiento)  
   2.1 [Oportunidad de negocio](#21-oportunidad-de-negocio)  
   2.2 [Definición del problema](#22-definicion-del-problema)  

3. [DESCRIPCIÓN DE LOS INTERESADOS Y USUARIOS](#3-descripcion-de-los-interesados-y-usuarios)  
   3.1 [Resumen de los interesados](#31-resumen-de-los-interesados)  
   3.2 [Resumen de los usuarios](#32-resumen-de-los-usuarios)  
   3.3 [Entorno de usuario](#33-entorno-de-usuario)  
   3.4 [Perfiles de los interesados](#34-perfiles-de-los-interesados)  
   3.5 [Perfiles de los Usuarios](#35-perfiles-de-los-usuarios)  

4. [NECESIDADES DEL PRODUCTO](#4-necesidades-del-producto)  

5. [VISIÓN DEL PRODUCTO](#5-vision-del-producto)  

6. [REQUISITOS Y RESTRICCIONES DEL PRODUCTO](#6-requisitos-y-restricciones-del-producto)  
   6.1 [Requisitos del producto](#61-requisitos-del-producto)  
   6.2 [Restricciones del producto](#62-restricciones-del-producto)

---















Sistema de Mentoría Académica

Documento de Visión



Versión 2.0





SISTEMA DE MENTORÍA ACADÉMICA

## INTRODUCCIÓN

### Propósito

El Sistema de Mentoría Académica (AMS) tiene como propósito proporcionar un entorno automatizado y eficiente para gestionar el apoyo académico personalizado, promoviendo el acceso equitativo a una educación de calidad, en línea con los Objetivos de Desarrollo Sostenible (ODS), específicamente el ODS 4. Este sistema no solo se enfoca en mejorar el desempeño académico individual, sino también en reducir las tasas de deserción y fomentar una cultura de colaboración entre los miembros de la comunidad educativa, mediante el desarrollo de habilidades de liderazgo en los mentores.

El AMS busca simplificar y automatizar procesos clave, como la inscripción de estudiantes y mentores, la asignación de clases de refuerzo, el seguimiento del progreso académico y la generación de informes que permitan una toma de decisiones informada. Con esta herramienta, la Universidad Privada de Tacna podrá garantizar intervenciones académicas más oportunas y efectivas, optimizando tanto el tiempo como los recursos disponibles.

A largo plazo, el AMS tiene el potencial de ser replicado en otras instituciones educativas del país, contribuyendo a mejorar la calidad de la educación superior en Perú. Además, la plataforma permitirá ampliar su funcionalidad con módulos adicionales, como análisis predictivo del rendimiento académico y herramientas de intervención temprana, para asegurar una mejora continua en los resultados educativos.









### Alcance

El AMS se implementará inicialmente en la Facultad de Ingeniería, con un enfoque particular en la Escuela de Ingeniería de Sistemas. Los beneficiarios directos del sistema serán los estudiantes en riesgo académico, los mentores (estudiantes destacados y docentes), y los administradores encargados de la gestión del programa. El sistema abarcará desde la inscripción hasta la programación y monitoreo de las sesiones de mentoría, gestionando tanto recursos humanos como físicos (aulas y laboratorios).

Entre las funcionalidades destacadas se incluyen:

Asignación de mentores: El sistema evaluará la disponibilidad y las habilidades de mentores registrados para asignar el tutor adecuado a cada estudiante, asegurando un acompañamiento personalizado.

Seguimiento académico: Cada sesión será registrada, y el progreso del estudiante se reflejará en informes periódicos para monitorear su evolución.

Gestión de recursos: La plataforma facilitará la administración de aulas y espacios disponibles, evitando conflictos de horarios y optimizando el uso de los recursos físicos.

Comunicación y notificaciones: El sistema enviará alertas y recordatorios automáticos para garantizar la asistencia y la coordinación eficaz entre estudiantes y mentores.

Además, se contempla una expansión futura del sistema hacia una versión móvil, incrementando la accesibilidad y permitiendo que los usuarios participen en las sesiones desde cualquier lugar con conexión a internet.

### Definiciones, Siglas y Abreviaturas

AMS: Sistema de Mentoría Académica, la plataforma que automatiza la gestión de mentorías.

SRS: Especificación de Requerimientos del Software, documento que define los aspectos funcionales y no funcionales del sistema.

CRUD: Operaciones básicas de bases de datos: Crear, Leer, Actualizar y Eliminar, que se implementarán para gestionar usuarios, clases y recursos.

MVC: Modelo Vista Controlador, patrón de diseño utilizado en el desarrollo del sistema, separando la lógica de la aplicación en tres capas: modelo (datos), vista (interfaz de usuario) y controlador (procesos).

### Referencias

Informe Final del Proyecto AMS (2025). Desarrollo e implementación del Sistema de Mentoría Académica. Universidad Privada de Tacna.

Documento SRS del Proyecto AMS (2024). Especificación de Requerimientos del Sistema de Mentoría Académica. Universidad Privada de Tacna​​.

Informe de Factibilidad del Proyecto AMS (2025). Análisis de Viabilidad del Sistema de Mentoría Académica. Universidad Privada de Tacna

### Visión General

El AMS surge como una respuesta a la creciente necesidad de mejorar la calidad educativa en la educación superior, particularmente en carreras técnicas como ingeniería, donde los estudiantes enfrentan desafíos significativos que afectan su desempeño académico. La sobrecarga de trabajo de los docentes y la falta de programas efectivos de mentoría han provocado que muchos estudiantes no reciban el apoyo adecuado a tiempo, lo que ha contribuido a aumentar las tasas de deserción universitaria en Perú.

Con la implementación de esta plataforma, la Universidad Privada de Tacna busca transformar estos procesos mediante la digitalización y automatización de las mentorías. El sistema permitirá que estudiantes con alto rendimiento y docentes asuman roles de mentoría, impartiendo sesiones de refuerzo que se ajusten a las necesidades específicas de cada estudiante. Las funcionalidades automatizadas del AMS no solo optimizarán la gestión de los recursos académicos, sino que también facilitarán la comunicación directa entre los participantes, permitiendo una mayor coordinación y eficiencia en las sesiones​.

El AMS también generará informes detallados sobre el progreso de los estudiantes, brindando a los administradores datos precisos para la toma de decisiones académicas estratégicas. A largo plazo, el sistema contribuirá al fortalecimiento de la comunidad universitaria, promoviendo la colaboración entre estudiantes y docentes, y desarrollando habilidades de liderazgo en los mentores. Esta plataforma se alinea con las estrategias institucionales de la Universidad para modernizar su oferta educativa, mejorar la retención de estudiantes y elevar la calidad del aprendizaje​.



## POSICIONAMIENTO

### Oportunidad de negocio

El Sistema de Mentoría Académica (AMS) surge como una solución estratégica para enfrentar los desafíos actuales en el ámbito universitario, donde la alta complejidad académica, especialmente en carreras como la ingeniería, requiere un acompañamiento personalizado para evitar la deserción y mejorar el rendimiento estudiantil. Las universidades necesitan herramientas que permitan gestionar de manera eficiente las tutorías y ofrecer apoyo continuo a los estudiantes, todo dentro de un entorno accesible y automatizado.

Al ser desarrollado en PHP como una plataforma web de acceso público, el AMS estará disponible para toda la comunidad universitaria, permitiendo que tanto estudiantes como mentores y administradores participen activamente en la gestión de las mentorías desde cualquier dispositivo con acceso a internet. Esta solución garantiza una mayor accesibilidad, simplificando los procesos administrativos y académicos relacionados con el seguimiento del progreso estudiantil y la asignación de recursos académicos.

Beneficios esperados del AMS en el contexto universitario:

Mejora en la retención universitaria: Los estudiantes con bajo rendimiento recibirán apoyo oportuno y adecuado, reduciendo la probabilidad de abandono.

Promoción de la colaboración entre pares: Fomentará una cultura de mentoría entre estudiantes avanzados y aquellos que necesiten refuerzo, fortaleciendo el aprendizaje colaborativo.

Automatización de procesos académicos: La gestión de mentorías, incluyendo la inscripción, asignación y programación de clases, se realizará automáticamente, liberando tiempo para docentes y administradores.

Optimización del uso de recursos universitarios: Se gestionarán de forma eficiente aulas, laboratorios y horarios disponibles para las sesiones de mentoría.

Escalabilidad y flexibilidad: El sistema podrá adaptarse a las necesidades futuras de la universidad e implementarse en otras facultades o universidades.

El AMS no solo facilita la gestión de mentorías, sino que también contribuye al desarrollo de habilidades de liderazgo en los mentores, consolidando una comunidad universitaria más integrada. Su enfoque en la automatización y en la accesibilidad asegura que todos los miembros de la universidad, sin importar su ubicación, puedan participar en el proceso educativo de forma eficiente y colaborativa.



### Definición del problema

El contexto universitario actual plantea desafíos significativos en la educación superior, especialmente en carreras técnicas, donde los estudiantes a menudo enfrentan dificultades académicas que comprometen su rendimiento y aumentan la tasa de deserción. A pesar de los esfuerzos por implementar programas de tutoría, estos suelen depender en gran medida de la disponibilidad limitada de los docentes, quienes deben asumir múltiples responsabilidades académicas y administrativas, dejando poco espacio para el acompañamiento personalizado. Esta situación agrava el problema, ya que los estudiantes que necesitan refuerzo no reciben la atención adecuada a tiempo​.

Problemas específicos identificados en el contexto universitario:

Falta de tutorías personalizadas y oportunas: Los programas de apoyo existentes no se ajustan a las necesidades específicas de cada estudiante, lo que genera brechas en su aprendizaje.

Sobrecarga de trabajo en el personal docente: Los docentes no cuentan con el tiempo suficiente para brindar tutorías adicionales, lo que reduce la eficacia de los programas de apoyo.

Procesos manuales e ineficientes: La inscripción, programación y asignación de mentorías se realiza de forma manual, lo que ocasiona conflictos de horarios y limita el uso eficiente de los recursos académicos.

Ausencia de un sistema de seguimiento continuo: Sin un monitoreo constante del progreso académico, los problemas de rendimiento no se identifican a tiempo, lo que dificulta la intervención adecuada.

Alta tasa de deserción estudiantil: La falta de apoyo eficaz y el rendimiento académico insuficiente llevan a muchos estudiantes a abandonar sus estudios universitarios​.

El AMS se presenta como una solución integral a estos problemas. Esta plataforma permitirá la asignación automática de mentorías, asegurando que cada estudiante reciba el apoyo necesario en función de sus necesidades académicas. Además, la gestión digital de recursos evitará conflictos de horarios y garantizará un uso eficiente de las aulas y laboratorios. El sistema proporcionará informes periódicos sobre el progreso académico de los estudiantes, facilitando la toma de decisiones informada por parte de los administradores universitarios.

El desarrollo del sistema en PHP garantiza que sea escalable y accesible para todos los usuarios, permitiendo que la comunidad universitaria participe activamente en el proceso educativo. Con el AMS, la universidad podrá ofrecer una experiencia académica más personalizada y eficiente, asegurando que los estudiantes reciban el apoyo necesario para alcanzar sus objetivos académicos y reducir significativamente la deserción.

## DESCRIPCIÓN DE LOS INTERESADOS Y USUARIOS

### Resumen de los interesados

Los interesados en el AMS abarcan varios roles fundamentales dentro del ecosistema universitario. Cada uno de ellos tiene un impacto directo o indirecto en la implementación y éxito del sistema. Los principales interesados incluyen:

Estudiantes: Reciben el servicio de mentoría para mejorar su rendimiento académico.

Mentores: Estudiantes avanzados o docentes encargados de impartir clases de refuerzo.

Administradores del sistema: Gestionan la plataforma, monitorean el progreso de las mentorías y supervisan la asignación de recursos.

Docentes y personal académico: Aportan orientación al programa de mentoría, asegurando su alineación con los objetivos académicos de la universidad.

Autoridades universitarias: Velan por la correcta implementación del sistema, asegurando que este contribuya a reducir la tasa de deserción y mejorar los índices de rendimiento.



### Resumen de los usuarios

El sistema está diseñado para diversos tipos de usuarios, cada uno con un conjunto específico de funciones y responsabilidades dentro del AMS:

Estudiantes: Usuarios principales que acceden a las mentorías según sus necesidades académicas.

Mentores: Usuarios que gestionan las clases de refuerzo, registran la asistencia y monitorean el progreso de los estudiantes.

Administradores: Encargados de la gestión global del sistema, incluyendo la creación de usuarios, programación de recursos, y generación de informes para las autoridades universitarias.

Docentes y autoridades universitarias: Aportan conocimiento y orientación para garantizar la eficacia de las mentorías en la formación de los estudiantes.





### Entorno de usuario

El AMS se implementa como una plataforma web basada en PHP y está disponible para su uso público. El sistema podrá accederse desde dispositivos con conexión a internet, permitiendo a los usuarios interactuar desde computadoras de escritorio, portátiles, tabletas o teléfonos móviles. La plataforma ofrece una interfaz amigable con un diseño intuitivo, de fácil navegación y adaptada para el entorno educativo.

Las funciones del sistema estarán disponibles las 24 horas del día, brindando flexibilidad de acceso tanto para estudiantes como mentores. Además, la plataforma incluirá un sistema de notificaciones automáticas y recordatorios, asegurando que los usuarios estén al tanto de sus actividades y compromisos.

.

### Perfiles de los interesados

Estudiantes: Interesados clave que buscan mejorar su rendimiento académico a través de mentorías personalizadas.

Mentores: Comprometidos en brindar apoyo académico a estudiantes, facilitando sesiones de refuerzo enfocadas en las áreas de mayor dificultad.

Administradores: Supervisan el funcionamiento del sistema, garantizan la correcta asignación de recursos y generan informes sobre el progreso de los estudiantes.

Docentes y personal académico: Aportan conocimiento y orientación para garantizar la eficacia de las mentorías en la formación de los estudiantes.

Autoridades universitarias: Monitorean el impacto del sistema en los resultados institucionales, alineándose con los objetivos estratégicos de la universidad.

### Perfiles de los Usuarios

Estudiante: Usuario principal que solicita mentorías en áreas específicas y participa activamente en las sesiones programadas. Puede visualizar su progreso académico y solicitar nuevas clases según sus necesidades.

Mentor: Usuario con conocimientos avanzados en una materia específica que gestiona clases de refuerzo, registra la asistencia y evalúa el progreso del estudiante.

Administrador: Usuario con acceso a todas las funcionalidades del sistema. Es responsable de la configuración de usuarios, asignación de recursos, programación de clases y generación de informes para las autoridades universitarias.



### Necesidades de los interesados y usuarios

Estudiantes:

Acceso rápido a mentorías que aborden sus necesidades específicas.

Información clara sobre el calendario de clases y progreso académico.

Notificaciones automáticas que les recuerden sus clases y tareas pendientes.

Mentores:

Herramientas para gestionar sus clases y coordinar con los estudiantes.

Un sistema sencillo para registrar asistencia y evaluar el rendimiento de los estudiantes.

Comunicación eficiente con estudiantes y administradores.

Administradores:

Paneles de control intuitivos para gestionar usuarios y recursos.

Acceso a informes detallados que permitan supervisar el funcionamiento del sistema.

Capacidad para intervenir rápidamente ante problemas operativos.

Docentes y autoridades universitarias:

Informes que reflejen el impacto del sistema en el rendimiento estudiantil.

Herramientas para monitorear la tasa de deserción y proponer mejoras académicas.

Un sistema que permita la integración con otros servicios académicos de la universidad.



## VISTA GENERAL DEL PRODUCTO

### Perspectiva del producto

El AMS se presenta como una plataforma web desarrollada en PHP 8 (nativo), diseñada para brindar soporte académico personalizado en el contexto universitario. Su propósito es facilitar y simplificar los procesos de inscripción, asignación de mentorías y seguimiento del progreso académico, ofreciendo una herramienta integral que mejore el rendimiento estudiantil y reduzca la tasa de deserción.

El AMS se alinea con las necesidades específicas de la Universidad Privada de Tacna, abordando los desafíos existentes en la gestión de tutorías y proporcionando una solución escalable, accesible y eficiente. La plataforma permitirá que los estudiantes soliciten mentorías de manera rápida, mientras que mentores y administradores podrán gestionar clases, aulas y horarios mediante una interfaz centralizada y accesible desde cualquier dispositivo conectado a internet.

### Resumen de capacidades

Las principales capacidades del AMS incluyen:

Automatización de la asignación de mentorías: El sistema asigna automáticamente mentores a estudiantes en función de su disponibilidad y las necesidades académicas identificadas.

Gestión de recursos académicos: Optimiza la programación de aulas y laboratorios para las sesiones de refuerzo, evitando conflictos de horarios.

Seguimiento académico continuo: Registra el progreso de los estudiantes en cada sesión y genera informes detallados que ayudan a identificar áreas de mejora.

Notificaciones y alertas automáticas: Envía recordatorios sobre clases programadas y cambios de horarios tanto a estudiantes como a mentores.

Escalabilidad: La plataforma está diseñada para soportar el aumento de usuarios y recursos, con posibilidad de integrar análisis predictivo en el futuro para identificar estudiantes en riesgo de manera temprana.

### Suposiciones y dependencias

Infraestructura tecnológica: Se asume que la universidad proporcionará servidores adecuados para alojar la plataforma y garantizar su disponibilidad 24/7.

Acceso a internet: Tanto los estudiantes como los mentores y administradores deberán contar con acceso a internet para utilizar el sistema de manera efectiva.

Capacitación básica: Los usuarios recibirán capacitación inicial para familiarizarse con las funcionalidades del sistema.

Mantenimiento continuo: Se requiere un equipo de soporte técnico para realizar actualizaciones y resolver problemas operativos a medida que surjan.

Integración con sistemas existentes: El sistema puede requerir integración con plataformas académicas existentes para optimizar la gestión de información y la toma de decisiones académicas.

### Costos y precios

El Informe de Factibilidad destaca que la implementación del AMS minimiza los costos mediante el uso de herramientas de código abierto como PHP y HeidiSQL. La mayor parte del desarrollo ha sido realizada por estudiantes universitarios, lo que reduce significativamente los costos de recursos humanos​.

Costos de desarrollo: Reducidos, gracias a la participación de estudiantes en el desarrollo y la utilización de software sin licencia.

Infraestructura: En su fase inicial, el sistema se alojará en servidores locales proporcionados por la universidad y/o estudiantes. En fases futuras, podrían requerirse gastos en servidores externos o en servicios de hosting web.

Mantenimiento: El mantenimiento será realizado por el mismo equipo de desarrollo, con soporte adicional del área de TI de la universidad.

Migración a plataformas web avanzadas: De ser necesario, la migración hacia plataformas web más robustas implicará gastos adicionales en hosting y almacenamiento.

El retorno de inversión esperado se reflejará en la mejora del rendimiento académico y la reducción de la tasa de deserción, lo que a su vez incrementará la retención de estudiantes y contribuirá al posicionamiento de la universidad como una institución educativa innovadora y eficiente​.

### Licenciamiento e instalación

El sistema se implementará utilizando software de código abierto, lo que elimina la necesidad de adquirir licencias comerciales. Entre las herramientas utilizadas destacan:

HeidiSQL y MySQL: Para la gestión de la base de datos.

XAMPP: Para la instalación y gestión del servidor web local.

Instalación inicial:

Despliegue en servidores locales: El AMS se instalará en los servidores de la universidad, garantizando un entorno controlado para pruebas y puesta en marcha.

Pruebas piloto: Antes del lanzamiento oficial, se realizará una fase piloto en la Facultad de Ingeniería, permitiendo corregir errores y ajustar funcionalidades.

Expansión gradual: Una vez comprobada su eficacia, el sistema se expandirá hacia otras facultades y eventualmente hacia toda la universidad.

El sistema requerirá mantenimiento periódico para asegurar su funcionamiento continuo y eficiente. Las actualizaciones se realizarán en función de las necesidades operativas y académicas identificadas durante su uso.

## CARACTERÍSTICAS DEL PRODUCTO

El AMS ofrece una serie de características que lo convierten en una herramienta integral para gestionar mentorías dentro del contexto universitario:

Automatización de procesos académicos:

Asignación automática de mentores: El sistema asigna automáticamente mentores a los estudiantes en función de sus necesidades académicas, la disponibilidad de los mentores y su experiencia en áreas específicas.

Gestión de horarios y recursos académicos: El AMS optimiza la programación de clases de refuerzo, asignando aulas y laboratorios de manera eficiente, evitando conflictos de horarios y maximizando el uso de los recursos físicos de la universidad.

Plataforma web accesible y escalable:

Desarrollada en PHP: El sistema estará basado en PHP 8 y utilizará MySQL para la base de datos. Su arquitectura modular permitirá una fácil expansión y adaptación a futuras necesidades.



Accesibilidad desde cualquier dispositivo: El AMS será accesible desde cualquier dispositivo con conexión a internet, incluyendo computadoras de escritorio, laptops, tabletas y teléfonos móviles, garantizando una experiencia coherente y fluida para todos los usuarios.

Escalabilidad: El sistema está diseñado para soportar el crecimiento en número de usuarios y recursos, permitiendo la incorporación de nuevas funcionalidades y módulos según las necesidades de la universidad.

Seguimiento académico y generación de informes:

Registro continuo del progreso de los estudiantes: Cada sesión de mentoría será registrada, y el progreso de los estudiantes se reflejará en informes periódicos para ser evaluados por los mentores, administradores y autoridades universitarias.

Notificaciones y recordatorios automatizados:

Envío de alertas automáticas: El AMS enviará notificaciones automáticas a los usuarios para recordarles las clases de refuerzo programadas, cambios de horario y otros eventos importantes relacionados con las mentorías.

Sistema de recordatorios: Los estudiantes y mentores recibirán recordatorios periódicos para asegurarse de que no falten a las sesiones y para mantener un seguimiento constante del progreso.

Integración con sistemas universitarios:

Conexión con plataformas académicas: El AMS podrá integrarse con otras plataformas de gestión académica y administrativa de la universidad, garantizando la coherencia y la integración de los datos. Esto facilitará el acceso a la información necesaria para la toma de decisiones académicas.

Comunicación eficiente:

Canales de comunicación directa: El sistema incluirá un módulo de mensajería y notificación para facilitar la comunicación directa entre estudiantes, mentores y administradores, asegurando que cualquier duda o problema sea resuelto de manera oportuna y eficiente.

## RESTRICCIONES

Acceso a internet: La plataforma requiere una conexión constante para garantizar su uso eficiente, lo que puede limitar el acceso de algunos usuarios en situaciones de conectividad deficiente.

Capacitación inicial: Es necesario proporcionar formación básica a los usuarios para que puedan manejar la plataforma sin dificultades.

Dependencia tecnológica: La plataforma está diseñada para funcionar en servidores locales en su fase inicial, lo que podría limitar la escalabilidad sin infraestructura adicional.

Normativas de protección de datos: El AMS debe cumplir con las leyes peruanas de protección de datos personales, lo que implica la gestión adecuada de la información de los usuarios.

Carga simultánea: En la fase inicial, la plataforma está optimizada para gestionar hasta 100 usuarios concurrentes. El crecimiento más allá de esta capacidad requerirá ajustes en la infraestructura y el software.

## RANGOS DE CALIDAD

El AMS se ha desarrollado siguiendo parámetros de calidad que aseguran un funcionamiento eficiente y accesible:

Disponibilidad: El sistema estará operativo las 24 horas, todos los días de la semana, con un tiempo de inactividad mínimo programado para mantenimiento.

Tiempo de respuesta: Las operaciones básicas (CRUD) deben completarse en menos de 2 segundos, garantizando una experiencia de usuario fluida.

Escalabilidad: El sistema soportará el crecimiento futuro en términos de usuarios y funcionalidades, con una estructura modular y fácilmente ampliable.

Seguridad: Las credenciales y datos personales estarán protegidos mediante encriptación y acceso controlado por roles.

Compatibilidad: El AMS será accesible desde navegadores modernos y dispositivos móviles, garantizando una experiencia coherente en todas las plataformas.

Mantenibilidad: El código del sistema está estructurado y documentado para facilitar futuras actualizaciones y correcciones.

## PRECEDENCIA Y PRIORIDAD

Para garantizar una implementación eficiente, se ha establecido un orden de prioridad en las funcionalidades del sistema:

Funcionalidades esenciales:

Registro de usuarios (estudiantes, mentores y administradores).

Autenticación y gestión de perfiles.

Asignación automática de mentorías y gestión de horarios.

Funcionalidades de seguimiento:

Registro del progreso académico de los estudiantes.

Generación de informes periódicos para administradores.

Funcionalidades de comunicación y notificaciones:

Envío de notificaciones automáticas sobre clases y eventos importantes.

Gestión avanzada de recursos:

Administración de aulas y laboratorios.

Integración con sistemas académicos adicionales.

Expansión futura:

Implementación de análisis predictivo para identificar estudiantes en riesgo.

Migración hacia servicios de hosting externos o nubes académicas.

La implementación seguirá un enfoque incremental, priorizando las funcionalidades críticas para asegurar un lanzamiento exitoso en la Facultad de Ingeniería. Las funcionalidades adicionales se desplegarán gradualmente, basándose en las necesidades y retroalimentación de los usuarios.

## OTROS REQUERIMIENTOS DEL PRODUCTO

### Estándares legales

El AMS debe cumplir con las normativas legales vigentes en Perú para garantizar el manejo adecuado de los datos personales y la protección de la privacidad de los usuarios:

Ley N° 29733 – Ley de Protección de Datos Personales: La plataforma gestionará la información de los estudiantes, mentores y administradores de manera confidencial, aplicando protocolos de protección y asegurando que los datos solo se utilicen para los fines previstos.

Política de seguridad de la información institucional: La universidad definirá políticas internas que regulen el acceso a la plataforma, estableciendo controles para evitar el uso indebido de los datos.

Cumplimiento de términos y condiciones: Se solicitará a los usuarios la aceptación explícita de las condiciones de uso del sistema y la política de privacidad al momento de registrarse.

### Estándares de comunicación

El sistema debe garantizar una comunicación eficaz y fluida entre todos los actores involucrados (estudiantes, mentores y administradores). Se implementarán los siguientes estándares de comunicación:

Notificaciones automáticas: Envío de alertas por correo electrónico y mensajes internos sobre cambios de horario, recordatorios y actualizaciones relevantes.

Integración con servicios de mensajería: Se evaluará la posibilidad de integrar herramientas de mensajería instantánea o videoconferencia (como Microsoft Teams o Zoom) para facilitar la interacción entre mentores y estudiantes.

Servicios PHP y páginas dinámicas: Se utilizarán APIs para permitir la integración con sistemas académicos externos o complementarios (como plataformas de gestión de aprendizaje o sistemas administrativos).



### Estándares de cumplimiento de la plataforma

El AMS se ha diseñado siguiendo buenas prácticas de desarrollo web para garantizar compatibilidad, accesibilidad y escalabilidad:

Multiplataforma: La plataforma será accesible desde cualquier dispositivo con un navegador moderno, asegurando la misma experiencia de usuario en computadoras, tabletas y teléfonos móviles.

Compatibilidad con navegadores: El sistema será compatible con los navegadores más utilizados, como Google Chrome, Mozilla Firefox, Safari y Microsoft Edge.

Accesibilidad: La plataforma seguirá los lineamientos de accesibilidad web WCAG 2.1, permitiendo su uso por personas con discapacidades mediante soporte para lectores de pantalla y navegación por teclado.

Optimización: El sistema estará diseñado para manejar adecuadamente la carga de usuarios, con procesos de escalabilidad para evitar interrupciones durante el aumento de la demanda.



### Estándares de calidad y seguridad

El sistema se desarrollará con altos estándares de calidad y seguridad, garantizando la protección de los datos y una operación eficiente:

Seguridad de la información:

Encriptación de contraseñas y datos sensibles mediante algoritmos seguros (como SHA-256, Bcrypt).

Control de acceso basado en roles (estudiante, mentor, administrador) para limitar el acceso a la información según el perfil del usuario.

Implementación de firewalls y medidas de prevención de ataques (como inyecciones SQL y ataques XSS).

Pruebas y validación:

Se realizarán pruebas de usabilidad, carga y estrés para garantizar la estabilidad del sistema.

Pruebas de seguridad regulares para identificar vulnerabilidades y mantener la integridad del sistema.

Mantenimiento y actualizaciones:

El sistema contará con mantenimiento preventivo y correctivo para garantizar su funcionamiento continuo.

Las actualizaciones de seguridad se realizarán de manera periódica para proteger los datos y asegurar la estabilidad operativa.



## GITHUB WIKIS

### Propósito del Wiki

El Wiki del repositorio ams-mentoría es la fuente única de verdad ( single source of truth ) para toda la documentación viva del sistema.
 Su misión es:

Centralizar guías, normas de codificación y artefactos de calidad.

Garantizar trazabilidad entre requisitos (SRS), código y pruebas.

Facilitar la onboarding de nuevos colaboradores y stakeholders.



### Estructura

La siguiente estructura en formato mind-map muestra, de forma clara y jerárquica, todas las secciones esenciales que debe contener el Wiki de GitHub para garantizar una documentación viva, accesible y alineada con las prácticas Docs-as-Code del proyecto.



### Buenas prácticas de mantenimiento

Docs-as-Code: cambios en documentación via pull request con reviewers de QA.

Plantillas automáticas:

Issue templates: “Bug report”, “User Story (Como…Quiero…Para…)”.

PR template: checklist (lint, pruebas, actualización de Wiki).

Versionado: cada release estable (tag vX.Y.Z) debe congelar un snapshot del Wiki bajo releases/.

Automatizaciones: GitHub Action que valida enlaces rotos y markdown-lint.

Métrica de salud



## ROADMAP DEL PROYECTO

### Visión temporal

El roadmap cubre dos semestres académicosGitHub Projects (vista Kanban). Cada columna representa un hito con criterios de aceptación tipo quality gate.



### Hitos y entregables





### Gestión y seguimiento

Etiqueta de issue - hito: cada issue lleva la etiqueta del hito correspondiente.

Sprint duration: 2 semanas; review los viernes.

KPIs semanales: burndown, tests fallados, cobertura, bugs críticos.

Quality gates: no se avanza de fase si KPI objetivo no se cumple.



### Futuras líneas de trabajo

Análisis predictivo (Machine Learning) para detección temprana de riesgo.

Migración a contenedores

Integración con LMS (Moodle, Canvas) vía LTI.







## CONCLUSIONES

El Sistema de Mentoría Académica (AMS) se presenta como una solución estratégica para mejorar el rendimiento académico y reducir la tasa de deserción en la Universidad Privada de Tacna. La plataforma, desarrollada en PHP 8 (nativo), facilita el acceso a tutorías personalizadas mediante la semiautomatizada de procesos clave, como la asignación de mentorías y la gestión de recursos académicos.

El AMS se alinea con las necesidades de un entorno universitario moderno, optimizando la interacción entre estudiantes, mentores y administradores, y permitiendo un seguimiento continuo del progreso académico. Con su enfoque escalable y accesible, el sistema tiene el potencial de ser replicado en otras facultades y universidades, contribuyendo significativamente al fortalecimiento de la educación superior en Perú.

Gracias a la implementación de tecnologías de código abierto y a la participación de estudiantes en su desarrollo, el proyecto minimiza los costos operativos, garantizando su viabilidad económica a largo plazo. Además, al integrar herramientas de análisis y notificaciones automáticas, el AMS permitirá una toma de decisiones académicas más informada y eficaz.

En resumen, el AMS no solo impactará positivamente en el rendimiento académico, sino que también fomentará una cultura de colaboración, liderazgo y aprendizaje entre pares, consolidando una comunidad universitaria más cohesionada y eficiente.



## RECOMENDACIONES

Para asegurar el éxito y la sostenibilidad del AMS, se proponen las siguientes recomendaciones:

Capacitación continua: Proveer formación a estudiantes, mentores y administradores para garantizar un uso eficiente del sistema.

Monitoreo constante del desempeño del sistema: Implementar métricas de evaluación periódica para identificar oportunidades de mejora y realizar ajustes.

Expansión gradual: Iniciar con la Facultad de Ingeniería y expandir el uso del sistema hacia otras facultades y universidades con base en los resultados obtenidos en la fase piloto.

Actualización y mantenimiento preventivo: Asegurar que la plataforma reciba mantenimiento regular y que se implementen actualizaciones de seguridad para proteger los datos de los usuarios.

Integración con otras plataformas: Conectar el AMS con otros sistemas académicos y administrativos para maximizar su impacto y funcionalidad.

Implementación de módulos predictivos: Incorporar herramientas de análisis predictivo para identificar estudiantes en riesgo y facilitar intervenciones tempranas.



## BIBLIOGRAFÍA

Arce, J., Martínez, R., & Torres, P. (2018). Modelos de mentoría académica en universidades de América Latina. Revista Iberoamericana de Educación, 78(2), 135-152.

Gómez, L., Pérez, J., & Herrera, C. (2019). Tutoría y mentoría en el ámbito universitario: Retos y oportunidades en Perú. Revista de Educación Superior, 47(1), 89-104.

Instituto Nacional de Estadística e Informática (INEI). (2020). Indicadores de rendimiento académico en las universidades peruanas: Informe estadístico. INEI.

PHP Group. (2023). PHP manual.

PlantUML. (2025). PlantUML documentation.

Universidad Privada de Tacna. (2024a). Documento de factibilidad del Sistema de Mentoría Académica (AMS). Facultad de Ingeniería.

Universidad Privada de Tacna. (2024b). Documento SRS del Sistema de Mentoría Académica (AMS). Facultad de Ingeniería.

Universidad Privada de Tacna. (2024c). Informe final del Sistema de Mentoría Académica (AMS). Facultad de Ingeniería.

World Wide Web Consortium (W3C). (2018). Web Content Accessibility Guidelines (WCAG) 2.1.

GitHub. (2025). Documenting your project with Wikis.