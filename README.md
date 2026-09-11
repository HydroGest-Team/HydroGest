# 💧 HidroGest

**Sistema de Gestión de Agua Potable**

Proyecto final desarrollado en la **Universidad Mariano Gálvez de Guatemala — Campus Jutiapa**.

---

# 👥 Información del equipo

A continuación se detallan los integrantes responsables del desarrollo del proyecto **HidroGest**.

| # | Nombre completo                         | Carné            | Rol                            | Liderazgo              |
| - | --------------------------------------- | ---------------- | ------------------------------ | ---------------------- |
| 1 | Tulio René Alejandro Quintana Amézquita | 0905-23-5024     | Scrum Master + Backend Lead    | —                      |
| 2 | **Naser Daniel Martinez Morales**       | **0905-23-3623** | DB Designer + Backend Dev      | ⭐ **LÍDER DEL EQUIPO** |
| 3 | Melki Bladimir Ortiz Martinez           | 0905-23-6329     | Backend Dev (Lecturas / Pagos) | —                      |
| 4 | Franklin Boanerges López Chavarría      | 0905-23-4498     | Frontend Developer             | —                      |

> ⭐ **Líder del equipo:** Naser Daniel Martinez Morales — Integrante 2

---

# 🔗 Enlaces del proyecto

### GitHub

Repositorio oficial del proyecto **HidroGest**:

https://github.com/HydroGest-Team/HydroGest

### Jira

Gestión del proyecto mediante **Jira**, utilizado para la administración del backlog, historias de usuario, tareas, seguimiento de actividades y sprints:

https://purificadora-de-agua.atlassian.net/?continue=https%3A%2F%2Fpurificadora-de-agua.atlassian.net%2Fwelcome%2Fsoftware%3FprojectId%3D10000&atlOrigin=eyJpIjoiNjVjODAyYjgyOGFhNGQ0MDk1YzIwMjYyODBlZDBlNzIiLCJwIjoiamlyYS1zb2Z0d2FyZSJ9

---

# 📋 Información general

**Stack:** Laravel 11 · Bootstrap 5 · MariaDB · AWS EC2
**Autenticación:** Laravel Breeze (sesiones)
**Frontend:** Blade puro, sin Vue/SPA
**Metodología:** Scrum
**Duración:** 3 sprints
**Equipo:** 4 integrantes

---

# 📖 Descripción

**HidroGest** es un prototipo de sistema web desarrollado para la gestión de una oficina de agua potable.

El sistema permite administrar clientes, contadores, tarifas y lecturas de consumo, así como generar recibos, registrar pagos y consultar información mediante un dashboard.

La aplicación implementa un sistema de autenticación y control de acceso basado en roles, permitiendo que cada usuario acceda únicamente a las funcionalidades correspondientes a sus responsabilidades.

---

# 🔐 Roles y permisos

| Rol           | Clientes | Contadores | Tarifas | Lecturas | Pagos | Dashboard |
| ------------- | -------- | ---------- | ------- | -------- | ----- | --------- |
| Administrador | ✅        | ✅          | ✅       | ✅        | ✅     | ✅         |
| Secretaria    | ✅        | ✅          | ❌       | ✅        | ✅     | ✅         |
| Empleado      | ❌        | ✅          | ✅       | ✅        | —     | —         |

---

# 🛠️ Requisitos previos

Para ejecutar el proyecto de manera local se requiere:

* PHP 8.2+
* Composer
* Node.js + npm
* MariaDB / MySQL
* XAMPP o Laragon
* Git

---

# 🚀 Instalación local

## 1. Clonar el repositorio

```bash
git clone https://github.com/HydroGest-Team/HydroGest.git
cd HydroGest
git checkout develop
```

## 2. Instalar dependencias

```bash
composer install
npm install
npm run build
```

## 3. Configurar el entorno

Crear el archivo `.env` a partir del archivo de ejemplo:

```bash
cp .env.example .env
php artisan key:generate
```

Editar el archivo `.env` con las credenciales correspondientes a la base de datos local:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hidrogest
DB_USERNAME=root
DB_PASSWORD=
```

Asegúrate de tener **MariaDB/MySQL** ejecutándose mediante XAMPP, Laragon u otra instalación compatible.

Antes de realizar las migraciones, crea una base de datos vacía llamada:

```text
hidrogest
```

Si la instancia local utiliza otro puerto, por ejemplo `3307`, deberá modificarse `DB_PORT` según corresponda.

---

## 4. Migrar y poblar la base de datos

Ejecutar:

```bash
php artisan migrate:fresh --seed
```

El seeder genera los datos iniciales en el siguiente orden:

```text
Roles
  ↓
Tipo de Tarifa
  ↓
Tarifas
  ↓
Usuarios
  ↓
Clientes
  ↓
Contadores
  ↓
Períodos
  ↓
Lecturas
  ↓
Pagos
```

---

## 5. Levantar el servidor

```bash
php artisan serve
```

La aplicación estará disponible normalmente en:

```text
http://127.0.0.1:8000
```

---

# 👤 Usuarios de prueba

Los seeders crean los siguientes usuarios para probar los diferentes niveles de acceso:

| Rol           | Email                       | Password   |
| ------------- | --------------------------- | ---------- |
| Administrador | `admin@hidrogest.test`      | `password` |
| Secretaria    | `secretaria@hidrogest.test` | `password` |
| Empleado      | `lector@hidrogest.test`     | `password` |

> **Nota:** Estas credenciales corresponden únicamente al entorno de desarrollo y pruebas.

---

# 🗃️ Datos de prueba incluidos

El proyecto incluye datos precargados mediante seeders para facilitar las pruebas del sistema:

* **3 roles:** Administrador, Secretaria y Empleado.
* **1 tipo de tarifa:** Residencial.
* **2 tarifas históricas:**

  * Q2.50 — enero a junio de 2026.
  * Q3.00 — julio de 2026 en adelante.
* **5 clientes** con sus respectivos contadores.
* **3 períodos:** dos cerrados y uno activo.
* **10 lecturas de ejemplo** distribuidas en dos períodos.
* Pagos de prueba asociados a algunas lecturas de períodos cerrados.

---

# 🧩 Estructura de módulos

| Módulo                  | Responsable                   | Descripción                                                             |
| ----------------------- | ----------------------------- | ----------------------------------------------------------------------- |
| Autenticación y roles   | Integrante 1 — Tulio Quintana | Login con Breeze, middleware `CheckRole` y motor de cálculo de consumo  |
| Base de datos y esquema | Integrante 2 — Naser Martinez | Diseño de BD, migraciones, modelos Eloquent y seeders                   |
| Lecturas y pagos        | Integrante 3 — Melki Ortiz    | `LecturaController`, `PagoController`, lógica de negocio y validaciones |
| Frontend                | Integrante 4 — Franklin López | Vistas Blade, plantilla Bootstrap (SB Admin) y dashboard                |

La documentación detallada correspondiente al trabajo de cada integrante se encuentra en:

```text
docs/documentation-I1.md
docs/documentation-I2.md
docs/documentation-I3.md
docs/documentation-I4.md
```

Esta documentación contiene información relacionada con la implementación realizada, decisiones de negocio e incidencias encontradas y resueltas durante el desarrollo.

---

# 🧮 Fórmulas de negocio

## Cálculo del consumo

El consumo de agua se obtiene mediante:

```text
consumo = lectura_actual - lectura_anterior
```

## Selección de tarifa vigente

Para obtener la tarifa correspondiente a una fecha determinada:

```text
WHERE vigente_desde <= F
AND (vigente_hasta IS NULL OR vigente_hasta >= F)
```

## Cálculo del monto

```text
monto = consumo × tarifa.monto_por_unidad
```

El sistema maneja **una sola tarifa vigente a la vez**, sin distinción por tipo de servicio.

Esta fue una decisión tomada por el equipo para mantener el prototipo dentro del alcance establecido para el proyecto.

El campo `tipo_tarifa_id` existe en `tb_tarifas` como catálogo, pero no participa directamente en el cálculo.

---

# 🔄 Flujo principal del sistema

## 1. Inicio de sesión

El usuario inicia sesión utilizando las credenciales correspondientes a uno de los siguientes roles:

* Administrador
* Secretaria
* Empleado

Las funcionalidades disponibles dependen del rol asignado.

---

## 2. Registrar lectura

El registro de lecturas se realiza mediante:

```text
lecturas.index → lecturas.store
```

El sistema muestra únicamente los contadores correspondientes al período activo que todavía no poseen una lectura registrada.

Al registrar una nueva lectura, el sistema calcula automáticamente:

* Lectura anterior.
* Lectura actual.
* Consumo.
* Tarifa vigente.
* Monto correspondiente.

---

## 3. Ver recibo

El recibo puede visualizarse mediante:

```text
lecturas.show
```

El recibo presenta información como:

* Número de recibo.
* Cliente.
* Contador.
* Lectura anterior.
* Lectura actual.
* Consumo.
* Tarifa aplicada.
* Monto total.

El recibo puede imprimirse utilizando la función nativa del navegador:

```text
Ctrl + P
```

No se utilizan librerías externas para la generación de PDF.

---

## 4. Registrar pago

El registro de pagos se realiza mediante:

```text
pagos.create → pagos.store
```

El monto correspondiente se carga automáticamente desde el recibo y puede editarse antes de registrar el pago.

También se almacena información relacionada con:

* Método de pago.
* Fecha del pago.
* Lectura relacionada.
* Estado del pago.

Únicamente los usuarios con rol **Administrador** o **Secretaria** pueden registrar pagos.

---

## 5. Dashboard

El dashboard proporciona un resumen general del estado de los clientes.

Permite visualizar y consultar:

* Clientes al día.
* Clientes con pagos pendientes.
* Información de consumo.
* Búsqueda por nombre.
* Filtrado por estado.

---

# 💳 Estado de una lectura/pago

La tabla `tb_lecturas` no contiene una columna denominada `estado`.

El estado **Pagado/Pendiente** se determina según la existencia de un registro relacionado en `tb_pagos`.

Al registrar un pago se almacena:

```text
estado_pago = 'PAGADO'
```

Por lo tanto:

```text
Existe pago asociado → PAGADO

No existe pago asociado → PENDIENTE
```

El prototipo no contempla un flujo de pagos pendientes de confirmación.

---

# 🚧 Funcionalidades fuera de alcance

Debido a que HidroGest corresponde a un prototipo académico, algunas funcionalidades no forman parte del alcance actual del proyecto:

* Tarifas diferenciadas por tipo de servicio residencial/comercial.
* Generación directa de archivos PDF.
* Notificaciones mediante SMS.
* Notificaciones mediante correo electrónico.
* Recuperación de contraseña mediante correo electrónico.
* Tests automatizados con PHPUnit/Pest.
* Listados históricos avanzados de recibos.
* Filtros avanzados para pagos históricos.

Algunas consultas históricas se encuentran parcialmente cubiertas mediante `pagos.index`.

---

# 🐛 Bugs conocidos y resueltos durante el desarrollo

Durante el desarrollo se detectaron distintos problemas relacionados principalmente con diferencias entre las convenciones predeterminadas de Laravel/Breeze y el esquema de base de datos utilizado por HidroGest.

Entre los principales problemas solucionados se encuentran:

* Nombre de clase/archivo inconsistente en `TipoTarifa.php` (**PSR-4**).
* Enums de estado (`activo_cliente`, `activo_contador`, `estado_periodo`) con valores diferentes a los utilizados en controladores y vistas.
* FK `pago()` apuntando a `lectura_id` en lugar de `lecturas_id`.
* `$fillable` y `$casts` desalineados con las migraciones reales de varios modelos.
* Diferencias de codificación en los estados **"Al día"** y **"Pendiente"** del dashboard.
* Botón de Logout sin funcionalidad real.
* Sidebar mostrando módulos no autorizados para determinados roles.

La documentación detallada de cada incidencia, incluyendo quién la encontró y cómo fue solucionada, está disponible en:

```text
docs/
```

---

# ☁️ Despliegue en AWS EC2

El despliegue del sistema puede realizarse mediante una instancia de **AWS EC2 con Ubuntu**.

## Procedimiento general

1. Lanzar una instancia EC2 con Ubuntu.
2. Instalar Nginx.
3. Instalar PHP 8.2.
4. Instalar Composer.
5. Clonar el repositorio de HidroGest.
6. Configurar el archivo `.env` para producción.
7. Instalar las dependencias.
8. Ejecutar las migraciones.
9. Optimizar la configuración de Laravel.
10. Configurar Nginx para servir la aplicación.

### Comandos principales

```bash
composer install --optimize-autoloader --no-dev
php artisan migrate --seed
php artisan config:cache
```

Nginx deberá configurarse para apuntar al directorio:

```text
public/
```

del proyecto Laravel.

---

# 🌿 Flujo de trabajo Git + Jira

El equipo utiliza una estrategia de integración entre **GitHub y Jira** para organizar y dar seguimiento al desarrollo del proyecto.

Cada tarea creada en Jira posee un identificador con el formato:

```text
SCRUM-XX
```

Por ejemplo:

```text
SCRUM-15
```

---

## 🌱 Nomenclatura de ramas

Cada tarea se desarrolla en su propia rama utilizando el identificador correspondiente de Jira:

```text
SCRUM-XX-descripcion-corta
```

Ejemplo:

```text
SCRUM-15-registro-lecturas
```

---

## 💾 Nomenclatura de commits

Los commits utilizan el identificador de Jira seguido del tipo de cambio:

```text
SCRUM-XX: feat/fix/chore descripción
```

Ejemplos:

```text
SCRUM-15: feat agregar registro de lecturas
```

```text
SCRUM-18: fix corregir cálculo del consumo
```

```text
SCRUM-21: chore actualizar seeders
```

---

## 🔀 Flujo de integración

El flujo general de trabajo utilizado por el equipo es:

```text
Jira
  ↓
SCRUM-XX
  ↓
Rama de desarrollo
  ↓
Commits
  ↓
Pull Request
  ↓
develop
  ↓
Integración
```

Al realizar el merge del **Pull Request** hacia `develop`, la actividad queda vinculada con la sección **Desarrollo** de Jira.

El estado de la tarjeta correspondiente deberá actualizarse manualmente según el avance realizado.

---

## 📌 Reglas del repositorio

Para mantener organizado el desarrollo se establecieron las siguientes reglas:

* No realizar `push` directamente a `main`.
* El desarrollo debe realizarse mediante ramas.
* Cada rama debe estar relacionada con una tarea de Jira.
* Los commits deben incluir el identificador de Jira.
* La integración se realiza mediante **Pull Request** hacia `develop`.
* Los Pull Requests requieren al menos **1 review aprobado** antes de integrarse.
* Las tareas de Jira deben actualizarse de acuerdo con su estado real.
* `main` se utiliza para mantener versiones estables del proyecto.

---

# 📂 Organización general del proyecto

```text
HydroGest/
│
├── app/
│   ├── Http/
│   └── Models/
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── docs/
│   ├── documentation-I1.md
│   ├── documentation-I2.md
│   ├── documentation-I3.md
│   └── documentation-I4.md
│
├── public/
│
├── resources/
│   └── views/
│
├── routes/
│
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

# 📚 Información académica

| Información     | Detalle                                 |
| --------------- | --------------------------------------- |
| **Proyecto**    | HidroGest                               |
| **Descripción** | Sistema de Gestión de Agua Potable      |
| **Tipo**        | Proyecto Desarrollo WEB                 |
| **Institución** | Universidad Mariano Gálvez de Guatemala |
| **Campus**      | Jutiapa                                 |
| **Metodología** | Scrum                                   |
| **Sprints**     | 3                                       |
| **Integrantes** | 4                                       |
| **Año**         | 2026                                    |

---

<div align="center">

# 💧 HYDROGEST

### Sistema de Gestión de Agua Potable

**HydroGest Team**

Universidad Mariano Gálvez de Guatemala
Campus Jutiapa

**2026**

</div>
