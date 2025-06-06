<center>

![./media/media/image1.png](./media/logo-upt.png)

**UNIVERSIDAD PRIVADA DE TACNA**

**FACULTAD DE INGENIERIA**

**Escuela Profesional de Ingeniería de Sistemas**

**Proyecto _SISTEMA DE MENTORÍA ACADÉMICA_**

Curso: _Calidad y Pruebas de Software_

Docente: _Mag. Ing. Patrick Cuadros Quiroga_

Integrantes:

- Huanca Merma, Gregory Brandon (2022073898)
- Medina Quispe, Joan Cristian (2022074255)
- Lira Alvarez, Rodrigo Samael Adonai (2019063331)

**Tacna – Perú**

_2025_

</center>

Sistema _SISTEMA DE MENTORÍA ACADÉMICA_

Informe de Factibilidad

Versión 2.0

| CONTROL DE VERSIONES |     |     |     |           |                  |
| :------------------: | :-: | :-: | :-: | :-------: | :--------------: |
| Versión              | Hecha por | Revisada por | Aprobada por |   Fecha   |      Motivo       |
| 2.0                  | JMQ       | JMQ          | GHM          | 25/04/2025 | Versión Original |

# INDICE GENERAL

- [INDICE GENERAL](#indice-general)
- [1. DESCRIPCIÓN DEL PROYECTO](#1-descripción-del-proyecto)
  - [1.1 Nombre del proyecto](#11-nombre-del-proyecto)
  - [1.2 Duración del proyecto](#12-duración-del-proyecto)
  - [1.3 Descripción](#13-descripción)
  - [1.4 Objetivos](#14-objetivos)
    - [1.4.1 Objetivo general](#141-objetivo-general)
    - [1.4.2 Objetivos Específicos](#142-objetivos-específicos)
- [2. RIESGOS](#2-riesgos)
- [3. ANÁLISIS DE LA SITUACIÓN ACTUAL](#3-análisis-de-la-situación-actual)
  - [3.1 Planteamiento del problema](#31-planteamiento-del-problema)
  - [3.2 Consideraciones de hardware y software](#32-consideraciones-de-hardware-y-software)
    - [Hardware](#hardware)
    - [Software](#software)
- [4. ESTUDIO DE FACTIBILIDAD](#4-estudio-de-factibilidad)
  - [4.1 Factibilidad Técnica](#41-factibilidad-técnica)
  - [4.2 Factibilidad Económica](#42-factibilidad-económica)
    - [4.2.1 Costos Generales](#421-costos-generales)
    - [4.2.2 Costos operativos durante el desarrollo](#422-costos-operativos-durante-el-desarrollo)
    - [4.2.3 Costos del ambiente](#423-costos-del-ambiente)
    - [4.2.4 Costos de personal (estimado)](#424-costos-de-personal-estimado)
    - [4.2.5 Recursos Terraform](#425-recursos-terraform)
    - [4.2.6 Costos totales del desarrollo del sistema](#426-costos-totales-del-desarrollo-del-sistema)
  - [4.3 Factibilidad Operativa](#43-factibilidad-operativa)
  - [4.4 Factibilidad Legal](#44-factibilidad-legal)
  - [4.5 Factibilidad Social](#45-factibilidad-social)
  - [4.6 Factibilidad Ambiental](#46-factibilidad-ambiental)
- [5. ANÁLISIS FINANCIERO](#5-análisis-financiero)
  - [5.1 Justificación de la Inversión](#51-justificación-de-la-inversión)
    - [5.1.1 Beneficios del Proyecto](#511-beneficios-del-proyecto)
    - [5.1.2 Criterios de Inversión](#512-criterios-de-inversión)
      - [5.1.2.1 Relación Beneficio/Costo (B/C)](#5121-relación-beneficiocosto-bc)
      - [5.1.2.2 Valor Actual Neto (VAN)](#5122-valor-actual-neto-van)
      - [5.1.2.3 Tasa Interna de Retorno (TIR)](#5123-tasa-interna-de-retorno-tir)
- [6. CONCLUSIONES](#6-conclusiones)

# 1. DESCRIPCIÓN DEL PROYECTO

## 1.1 Nombre del proyecto

“SISTEMA DE MENTORÍA ACADÉMICA”

## 1.2 Duración del proyecto

10 MESES

## 1.3 Descripción

- El AMS es una plataforma web (PHP + MySQL) que es una aplicación web desarrollada en PHP nativo con conexión a una base de datos MySQL, diseñada para facilitar la gestión de mentorías académicas en la Escuela Profesional de Ingeniería de Sistemas de la Universidad Privada de Tacna.

Entre las funcionalidades implementadas destacan:

- Registro y autenticación de usuarios (mentores y estudiantes).
- Gestión de perfiles de mentoría.
- Asignación y seguimiento de sesiones de mentoría.
- Panel de administración para la gestión de usuarios.

El sistema está estructurado bajo una arquitectura sencilla de cliente-servidor, utilizando HTML y Bootstrap para la interfaz de usuario, y ejecutándose en un servidor local (XAMPP) o en una instancia virtual básica en la nube.

Actualmente, se encuentra en fase inicial de desarrollo funcional y validación manual, con la posibilidad de ampliar sus capacidades en futuras versiones (por ejemplo: asignación de aulas, indicadores académicos, reportes automatizados).

Las funcionalidades avanzadas como infraestructura como código (IaC), pruebas automatizadas o ambientes de staging aún no han sido implementadas, pero están consideradas para fases futuras del proyecto.

## 1.4 Objetivos

### 1.4.1 Objetivo general

- **Implementar un sistema de mentoría académica que mejore el rendimiento de los estudiantes de Ingeniería de Sistemas, reduzca las tasas de deserción y fortalezca el apoyo académico personalizado.**

### 1.4.2 Objetivos Específicos

- Desarrollar una plataforma que permita la gestión eficiente de mentorías, incluyendo registro de usuarios, programación de sesiones y asignación de aulas.
- Implementar un sistema de solicitud de clases por demanda, permitiendo a los estudiantes solicitar sesiones de refuerzo en temas específicos.
- Crear un mecanismo de seguimiento del progreso de los estudiantes para evaluar la efectividad de las mentorías.
- Establecer un sistema de comunicación directa entre mentores y estudiantes para facilitar la coordinación y el intercambio de información.
- Diseñar una interfaz de usuario intuitiva y accesible para todos los tipos de usuarios (estudiantes, mentores, administradores).
- Proveer un análisis integral de factibilidad (técnica, económica, operativa, legal, social y ambiental) para el AMS.
- Estimar, mediante Terraform + Infracost, el costo total de infraestructura en AWS (us-east-1) a 1 y 3 años.

# 2. RIESGOS

| Riesgo                    | Impacto                            | Probabilidad | Mitigación                                    |
| ------------------------- | ---------------------------------- | ------------ | --------------------------------------------- |
| Baja adopción             | Objetivos académicos no cumplidos  | Media        | Campañas de difusión, incentivos a mentores   |
| Sobrecarga de sistema     | Pérdida de servicio                | Baja         | Auto-scaling y monitoreo CloudWatch           |
| Vulnerabilidades de datos | Sanciones legales                  | Media        | HTTPS, IAM estricto, copias cifradas          |
| Costos cloud inesperados  | Presupuesto excedido               | Media        | Alarmas de presupuesto AWS, instancias programadas |
| Resistencia al cambio     | Retraso en implementación          | Alta         | Talleres a docentes y estudiantes             |

# 3. ANÁLISIS DE LA SITUACIÓN ACTUAL

## 3.1 Planteamiento del problema

- En el contexto universitario actual de la Universidad Privada de Tacna, específicamente en la Facultad de Ingeniería de Sistemas, se ha identificado una serie de problemas relacionados con el rendimiento académico y el apoyo a los estudiantes:
  - Falta de apoyo académico en temas críticos: Los estudiantes no reciben orientación adecuada en áreas donde enfrentan más dificultades.
  - Dificultad para identificar áreas de mejora: Tanto profesores como estudiantes carecen de visibilidad completa sobre las áreas en las que los estudiantes están fallando hasta que es demasiado tarde.
  - Manejo manual de mentoría: El proceso de emparejamiento mentor-estudiante se realiza de forma manual, generando demoras y no garantizando la compatibilidad.
  - Seguimiento deficiente: No existe un seguimiento continuo y organizado de los avances de los estudiantes durante las mentorías.
  - Retroalimentación académica limitada: Falta de mecanismos claros para que los profesores den feedback a los estudiantes.
  - Desaprovechamiento de recursos: Aulas y laboratorios no se utilizan eficientemente para sesiones de tutoría o mentoría.
  - Comunicación ineficiente: No hay un sistema centralizado para la comunicación entre mentores y estudiantes.
- Estos problemas contribuyen a una elevada tasa de deserción y a un rendimiento académico subóptimo en la facultad.

## 3.2 Consideraciones de hardware y software

### Hardware

- **Servidores**: Se requerirán servidores para alojar la aplicación y la base de datos. Inicialmente, se puede considerar un servidor físico o virtual:
  - Procesador: Intel Xeon o AMD EPYC (8 núcleos o más)
  - RAM: 32 GB
  - Almacenamiento: 1 TB SSD
- **Equipos de desarrollo**: Computadoras para el equipo de desarrollo con especificaciones adecuadas para ejecutar entornos de desarrollo y pruebas.
- **Dispositivos de red**: Routers y switches para garantizar una conexión estable y segura que establezca Internet de fibra óptica.

### Software

| Capa                       | Herramienta / Tecnología                              |
| -------------------------- | ----------------------------------------------------- |
| Sistema operativo servidor | Windows 11 (laboratorio) / Amazon Linux 2023 (cloud)  |
| Backend                    | PHP 8 (nativo)                                       |
| Base de datos              | MySQL 8 – HeidiSQL                                    |
| Frontend                   | HTML + Bootstrap (PHP)                               |
| Servidor de aplicaciones   | Apache + PHP-FPM (EC2)                                |
| Control de versiones       | Git (GitHub)                                          |
| IaC / Cloud                | Terraform 1.8 + Infracost                             |
| Gestión de proyectos       | Jira                                                  |
| UI/UX                      | Figma / Balsamiq                                       |

# 4. ESTUDIO DE FACTIBILIDAD

## 4.1 Factibilidad Técnica

- **Tecnología disponible**: Las tecnologías necesarias para desarrollar el sistema (PHP, bases de datos relacionales, servidores web) son ampliamente conocidas y están bien establecidas.
- **Experiencia del equipo**: Se asume que la Facultad de Ingeniería de Sistemas cuenta con personal docente y estudiantes avanzados con conocimientos en desarrollo de software.
- **Infraestructura existente**: La universidad cuenta con parte de la infraestructura necesaria (servidores, red) que podría adaptarse para este proyecto.
- **Escalabilidad**: El sistema puede desarrollarse inicialmente para desktop y luego expandirse a plataformas web y móviles, como se menciona en el documento.
- **Integración**: Aunque puede presentar desafíos, es técnicamente posible integrar el sistema con las plataformas existentes de la universidad.

**Stack de referencia y prácticas DevOps**

- **Stack**: PHP 8 (nativo), MySQL 8, Apache + PHP-FPM sobre EC2 t3.micro.
- **Infraestructura**: Definida como código con Terraform; ambientes Dev/QA apagados fuera de horario.
- **CI/CD**: GitHub Actions con PHPUnit + Selenium; despliegue automatizado a S3/EC2.
- **Escalabilidad y disponibilidad**: Auto-scaling opcional; ALB + ELB; disponibilidad prometida 99,9 %.
- **Compatibilidad con laboratorio**: Windows 11 para pruebas locales; Docker Desktop para contenerizar.

## 4.2 Factibilidad Económica

- **Costos de desarrollo**:
  - Salarios del equipo de desarrollo (si se contrata personal adicional)
  - Licencias de software (la mayoría puede ser open-source, reduciendo costos)
  - Costos de hardware (servidores, si no se utilizan los existentes)
- **Costos de implementación**:
  - Capacitación de usuarios (estudiantes, mentores, administradores)
  - Posible actualización de infraestructura de red
- **Costos de mantenimiento**:
  - Actualizaciones de software
  - Soporte técnico continuo
- **Beneficios esperados**:
  - Reducción en la tasa de deserción estudiantil
  - Mejora en el rendimiento académico general
  - Optimización del uso de recursos (aulas, tiempo de los docentes)
  - Potencial para generar ingresos si el sistema se expande a otras facultades o instituciones
- **Retorno de la inversión**:
  - A corto plazo: Mejora en la satisfacción de los estudiantes y en la reputación de la facultad
  - A largo plazo: Aumento en la retención de estudiantes y posible incremento en la matrícula debido a la mejora en la calidad educativa

Considerando estos factores, el proyecto parece ser económicamente factible, especialmente si se considera como una inversión a largo plazo en la calidad educativa de la institución. Sin embargo, se recomienda realizar un análisis detallado de costos y beneficios para determinar el presupuesto exacto y el tiempo esperado para el retorno de la inversión.

### 4.2.1 Costos Generales

| Ítem                              | Cantidad | Costo Unitario (S/.) | Costo Total (S/.) |
| --------------------------------- | -------- | -------------------- | ----------------- |
| Impresiones y copias documentación | 200      | 0.50                 | 100.00            |
| Material de oficina (set completo) | 2 SETS   | 150.00               | 300.00            |
| Equipamiento adicional (adaptadores, cables, etc.) | 1        | 600.00               | 600.00            |
| **Total**                         |          |                      | **1,000.00**      |

### 4.2.2 Costos operativos durante el desarrollo

| Ítem             | Costo Mensual (S/.) | Duración (meses) | Costo Total (S/.) |
| ---------------- | ------------------- | ---------------- | ----------------- |
| Renta de oficina | 1,000               | 8                | 8,000.00          |
| Electricidad     | 250                 | 8                | 2,000.00          |
| Agua             | 150                 | 8                | 1,200.00          |
| Internet         | 1,510               | 8                | 1,200.00          |
| **Total**        |                     |                  | **12,400.00**     |

### 4.2.3 Costos del ambiente

| Ítem                          | Costo (S/.) | Período (meses) | Costo Total (S/.) |
| ----------------------------- | ----------- | --------------- | ----------------- |
| Licencias de software LIBRE   | –           | –               |                   |
| Transporte                    | 30.00       | 4               | 120.00            |
| Refrigerios                   | 20.00       | 4               | 80.00             |
| **Total**                     |             |                 | **200.00**        |

### 4.2.4 Costos de personal (estimado)

| Rol                  | Cantidad | Salario Mensual (S/.) | Duración (meses) | Costo Total ($) |
| -------------------- | -------- | --------------------- | ---------------- | --------------- |
| Gerente de Proyecto  | 1        | 3,500                 | 8                | 28,000         |
| Desarrollador Junior | 1        | 3,000                 | 8                | 24,000         |
| Diseñador UX/UI      | 1        | 2,000                 | 6                | 12,000         |
| Tester               | 1        | 1,800                 | 4                | 7,200          |
| **Total**            |          |                       |                  | **71,200.00**  |

### 4.2.5 Recursos Terraform

| Recurso            | Cantidad   | USD/mes | PEN (3.70) |
| ------------------ | ---------- | ------- | ---------- |
| EC2 t3.micro       | 2 × 730 h  | 16.94   | 62.7       |
| RDS db.t3.micro    | 1 × 730 h  | 12.41   | 45.9       |
| S3 50 GB           | 1          | 1.15    | 4.3        |
| Data egress 200 GB | –          | 18.00   | 66.6       |
| **Total mensual**  |            | 48.50   | 179.5      |

### 4.2.6 Costos totales del desarrollo del sistema

| Categoría           | Costo ($) | Costo Referencial ($) |
| ------------------- | --------- | ---------------------- |
| Costos Generales    | 1,000.00  | 1,000.00               |
| Costos Operativos   | 12,400.00 | 12,400.00              |
| Costos del Ambiente | 200.00    | 200.00                 |
| Costos de Personal  | 00.00     | 71,200.00              |
| **Total**           | **84,800.00** |                        |

## 4.3 Factibilidad Operativa

| Factor                 | Evaluación                                                                                      |
| ---------------------- | ------------------------------------------------------------------------------------------------ |
| Aceptación de usuarios | El sistema resuelve necesidades reales de estudiantes y docentes; se espera alta adopción.       |
| Facilidad de uso       | La UI intuitiva (HTML + Bootstrap) y flujos simples favorecen el aprendizaje en < 1 h.    |
| Procesos alineados     | Complementa procesos académicos existentes (registro, asesorías) sin sustituirlos.               |
| Capacitación           | Personal de la Facultad de Ingeniería capacitará a usuarios finales mediante talleres de 4 h.    |
| Soporte técnico        | Equipo de TI universitaria brindará soporte de 1er y 2º nivel, con SLAs académicos (8×5).       |

## 4.4 Factibilidad Legal

| Aspecto               | Cumplimiento                                                                                              |
| --------------------- | ---------------------------------------------------------------------------------------------------------- |
| Protección de datos   | Ley 29733: almacenamiento en AWS cifrado (RDS AES-256), política de privacidad y consentimiento.            |
| Derechos de autor     | Código propiedad de la Universidad Privada de Tacna; licenciamiento interno.                               |
| Licencias de software | Uso de OSS (PHP OSS, MySQL GPL, Bootstrap MIT); cumplimiento de términos.                               |
| Normativas educativas | Alineado con reglamentos universitarios y directivas SUNEDU sobre tutorías académicas.                     |

## 4.5 Factibilidad Social

- **Mejora educativa**: Contribuye directamente a mejorar la calidad de la educación y el apoyo a los estudiantes.
- **Inclusión**: Proporciona apoyo adicional a estudiantes que puedan estar enfrentando dificultades académicas.
- **Desarrollo de habilidades**: Los estudiantes que actúen como mentores desarrollarán habilidades de liderazgo y enseñanza.
- **Fortalecimiento de la comunidad académica**: Fomenta la colaboración y el apoyo mutuo entre estudiantes y docentes.
- **Impacto social**: Egresados mejor preparados para el mercado laboral regional.

## 4.6 Factibilidad Ambiental

- **Reducción de papel**: Al ser un sistema digital, reduce la necesidad de documentación física.
- **Optimización de recursos**: El uso eficiente de aulas y laboratorios puede resultar en un menor consumo de energía.
- **Huella de carbono**: Se debe considerar el consumo energético de los servidores y equipos necesarios para el funcionamiento del sistema.
- **Disposición de hardware**: Planificar la disposición responsable de equipos electrónicos al final de su vida útil.
- **Concientización**: Incluir mensajes de concientización ambiental dentro del sistema para promover prácticas sostenibles entre los usuarios.

| Línea de acción        | Impacto                                                                                         |
| ---------------------- | ----------------------------------------------------------------------------------------------- |
| Reducción de papel     | Flujos 100 % digitales → ↓ impresiones administrativas.                                           |
| Optimización de recursos | Mejor uso de aulas/labs → menor consumo eléctrico.                                              |
| Huella de carbono      | Uso de cloud (energía renovable parcial AWS) + apagado Dev/QA fuera de horas pico.              |
| Disposición de hardware | Política de reciclaje RAEE para equipos obsoletos del laboratorio.                              |
| Concientización        | Mensajes “verde” en el dashboard: tips de sostenibilidad para usuarios.                         |

# 5. ANÁLISIS FINANCIERO

El plan financiero se ocupa del análisis de ingresos y gastos asociados a cada proyecto, desde el punto de vista del instante temporal en que se producen. Su misión fundamental es detectar situaciones financieramente inadecuadas.

Se tiene que estimar financieramente el resultado del proyecto.

## 5.1 Justificación de la Inversión

### 5.1.1 Beneficios del Proyecto

| Tipo        | Indicador                     | Meta  |
| ----------- | ----------------------------- | ----- |
| **Tangible**   | ↓ procesos manuales          | 40 %  |
|             | ↓ costos operativos           | 25 %  |
|             | ↑ eficiencia infra académica  | 35 %  |
| **Intangible** | ↑ experiencia estudiantil    | —     |
|             | ↑ imagen institucional         | —     |
|             | Mejor toma de decisiones      | —     |
|             | ↑ competitividad académica     | —     |

### 5.1.2 Criterios de Inversión

| Categoría                | Sub-beneficio                  | Ahorro / Ingreso (S/.) |
| ------------------------ | ------------------------------ | ---------------------- |
| **A. Recursos físicos**     | Reducción en impresiones       | 250                    |
|                          | Ahorro en materiales de oficina | 90                     |
|                          | Digitalización de documentos    | 120                    |
| **B. Tiempo administrativo**  | Gestión de matrículas          | 320                    |
|                          | Coordinación académica         | 200                    |
|                          | Generación de reportes         | 180                    |
| **C. Retención estudiantil**   | Prevención de deserción       | 900                    |
|                          | Seguimiento académico          | 250                    |
|                          | Alerta temprana                | 180                    |
| **D. Eficiencia operativa**   | Optimización de horarios      | 140                    |
|                          | Reducción de conflictos de aulas | 110                   |
|                          | Mejor asignación de recursos   | 170                    |
| **E. Servicios adicionales**    | Cursos de refuerzo online      | 350                    |
|                          | Talleres especializados        | 230                    |
|                          | Asesorías personalizadas       | 210                    |
| **F. Automatización**       | Reducción de horas extras      | 280                    |
|                          | Eficiencia en procesos         | 320                    |
|                          | Minimización de errores administrativos | 190          |
| **Total beneficios mensuales** |                            | **3,680**             |

#### 5.1.2.1 Relación Beneficio/Costo (B/C)

- Beneficios (8 meses): 3,680 × 8 = $29,440.00
- Costos reales: $13,600
- **B/C = 2.16**

#### 5.1.2.2 Valor Actual Neto (VAN)

- Flujos de efectivo:
  - Inversión inicial: -$13,600.00
  - Beneficios mensuales: $3,680.00
  - Período: 8 meses
- **VAN = -13,600 + 29,440.00 = 15,840.00**

#### 5.1.2.3 Tasa Interna de Retorno (TIR)

| Criterio | Valor    | Referencia | Decisión |
| -------- | -------- | ---------- | -------- |
| B/C      | 2.37     | > 1        | Viable   |
| VAN      | 15,840   | > 0        | Viable   |
| TIR      | 15.5 %   | > 15 %     | Viable   |

TIR ≈ 15 % + [((-2,021) / ((-2,021) – (-4,500))) × (20 % – 15 %)] ≈ 15.5 %.

# 6. CONCLUSIONES

- **Viabilidad integral confirmada**: El AMS es factible desde los frentes técnico, operativo, económico, legal, social y ambiental.
- **Impacto educativo decisivo**: Se espera un aumento medible del rendimiento y la retención estudiantil, así como una cultura de tutoría alineada con el ODS 4.
- **Solidez técnica y de calidad**: El stack PHP 8 + MySQL 8, gestionado con Terraform y CI/CD, garantiza despliegues reproducibles, escalabilidad controlada y pruebas continuas.
- **Rentabilidad sobresaliente**: ROI estimado > 500 % y periodo de recuperación < 2 meses; ahorros anuales proyectados de S/ 44,160 frente a un costo de S/ 7,200.
- **Cumplimiento normativo y seguridad**: Almacenamiento cifrado, IAM restrictivo y plena adhesión a la Ley 29733 y licencias OSS.
- **Compromiso ambiental**: Digitalización de procesos, optimización de aulas y apagado de ambientes no productivos reducen la huella de carbono; disposición RAEE planificada.
- **Recomendación**: Iniciar un piloto de 12 meses; monitorear KPIs de adopción, progreso académico, consumo de recursos y costes reales; realizar ajuste iterativo al mes 6.

**Próximas acciones prioritarias**:

- Elaborar cronograma detallado y plan de capacitación por perfiles.
- Formalizar protocolo de soporte y mesa de ayuda.
- Refinar matriz de riesgos y estrategia de actualización continua.
