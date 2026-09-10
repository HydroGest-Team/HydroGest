# HidroGest

Sistema de Gestión de Agua Potable — prototipo desarrollado como proyecto final de curso en la Universidad Mariano Gálvez de Guatemala, Campus Jutiapa.

**Stack:** Laravel 11 · Bootstrap 5 · MariaDB · AWS EC2
**Autenticación:** Laravel Breeze (sesiones)
**Frontend:** Blade puro, sin Vue/SPA
**Metodología:** Scrum · 3 sprints · equipo de 4 integrantes

---

## Descripción

HidroGest es un prototipo minimalista para la gestión de una oficina de agua potable: registro de clientes, contadores, tarifas, lecturas de consumo, generación de recibos y registro de pagos, con control de acceso basado en roles.

## Roles y permisos

| Rol | Clientes | Contadores | Tarifas | Lecturas | Pagos | Dashboard |
|---|---|---|---|---|---|---|
| Administrador | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Secretaria | ✅ | ✅ | ❌ | ✅ | ✅ | ✅ |
| Empleado | ❌ | ✅ | ✅ | ✅ | — | — |

## Requisitos previos

- PHP 8.2+
- Composer
- Node.js + npm
- MariaDB / MySQL (vía XAMPP o Laragon)
- Git

## Instalación local

1. **Clonar el repositorio**
```bash
   git clone https://github.com/HydroGest-Team/HydroGest.git
   cd HydroGest
   git checkout develop
```

2. **Instalar dependencias**
```bash
   composer install
   npm install && npm run build
```

3. **Configurar el entorno**
```bash
   cp .env.example .env
   php artisan key:generate
```
   Edita `.env` con tus credenciales de base de datos local:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hidrogest
DB_USERNAME=root
DB_PASSWORD=

   Asegúrate de tener MariaDB/MySQL corriendo (XAMPP o Laragon) y crea la base de datos `hidrogest` vacía antes de migrar. Si tu instancia local usa otro puerto (por ejemplo, una instalación portable en 3307), ajusta `DB_PORT` según corresponda.

4. **Migrar y poblar la base de datos**
```bash
   php artisan migrate:fresh --seed
```
   El seeder crea, en orden: Roles → Tipo de Tarifa → Tarifas → Usuarios → Clientes → Contadores → Períodos → Lecturas → Pagos.

5. **Levantar el servidor**
```bash
   php artisan serve
```
   La aplicación estará disponible en `http://127.0.0.1:8000`.

## Usuarios de prueba (seeder)

| Rol | Email | Password |
|---|---|---|
| Administrador | admin@hidrogest.test | password |
| Secretaria | secretaria@hidrogest.test | password |
| Empleado | lector@hidrogest.test | password |

## Datos de prueba incluidos

- 3 roles: Administrador, Secretaria, Empleado
- 1 tipo de tarifa (Residencial) y 2 tarifas históricas (Q2.50 ene–jun 2026, Q3.00 jul 2026 en adelante)
- 5 clientes con sus contadores asociados
- 3 períodos (dos cerrados, uno activo)
- 10 lecturas de ejemplo repartidas en 2 períodos
- Pagos de prueba sobre algunas lecturas de períodos cerrados

## Estructura de módulos

| Módulo | Responsable | Descripción |
|---|---|---|
| Autenticación y roles | I1 | Login (Breeze), middleware `CheckRole`, motor de cálculo de consumo |
| Base de datos y esquema | I2 | Migraciones, modelos Eloquent, seeders |
| Lecturas y pagos | I3 | `LecturaController`, `PagoController`, lógica de negocio y validaciones |
| Frontend | I4 | Todas las vistas Blade, plantilla Bootstrap (SB Admin), dashboard |

Documentación detallada de cada integrante, incluyendo decisiones de negocio consultadas al equipo e incidencias resueltas, en `docs/documentation-I{1,2,3,4}.md`.

## Fórmulas de negocio

consumo = lectura_actual - lectura_anterior
tarifa vigente a fecha F: WHERE vigente_desde <= F AND (vigente_hasta IS NULL OR vigente_hasta >= F)
monto = consumo × tarifa.monto_por_unidad


El sistema maneja **una sola tarifa vigente a la vez** (sin distinción por tipo de servicio) — decisión de equipo para mantener el prototipo dentro del alcance de las 4 semanas. El campo `tipo_tarifa_id` existe en `tb_tarifas` como catálogo, pero no participa en el cálculo.

## Flujo principal del sistema

1. **Login** según rol (Administrador / Secretaria / Empleado).
2. **Registrar lectura** (`lecturas.index` → `lecturas.store`): el sistema solo muestra contadores del período activo que aún no tienen lectura registrada, y calcula automáticamente consumo y monto.
3. **Ver recibo** (`lecturas.show`): número de recibo, cliente, contador, consumo, tarifa aplicada y monto — imprimible con `Ctrl+P` (sin librerías externas).
4. **Registrar pago** (`pagos.create` → `pagos.store`): monto pre-llenado desde el recibo (editable), método de pago y fecha. Solo Administrador y Secretaria pueden registrar pagos.
5. **Dashboard**: resumen de clientes al día/pendientes, filtrable por nombre y estado.

## Estado de una lectura/pago

`tb_lecturas` no tiene columna `estado` — el estado "Pagado/Pendiente" se deriva de la existencia de un registro asociado en `tb_pagos`. Al registrar un pago, se guarda directamente con `estado_pago = 'PAGADO'` (no existe flujo de "pago pendiente de confirmar" en este prototipo).

## Fuera de alcance (prototipo)

- Tarifas diferenciadas por tipo de servicio (residencial/comercial)
- Generación de PDF — el recibo usa impresión nativa del navegador
- Notificaciones por SMS/email
- Recuperación de contraseña por email
- Tests automatizados (PHPUnit/Pest)
- Listado/índice de recibos y pagos históricos con filtros avanzados (parcialmente cubierto por `pagos.index`)

## Bugs conocidos y resueltos durante el desarrollo

El proyecto tuvo varios desajustes entre el esquema real de base de datos (nombrado en español, con prefijo `tb_`) y las convenciones que Laravel/Breeze esperan por defecto. Los más relevantes, ya corregidos:

- Nombre de clase/archivo inconsistente en `TipoTarifa.php` (PSR-4)
- Enums de estado (`activo_cliente`, `activo_contador`, `estado_periodo`) con valores distintos a los usados en controladores y vistas
- FK `pago()` apuntando a `lectura_id` en vez de `lecturas_id`
- `$fillable`/`$casts` desalineados con las migraciones reales en varios modelos
- Estado "Al día"/"Pendiente" del dashboard con mismatch de encoding entre controlador y vista
- Botón de Logout sin funcionalidad real (enlace sin acción)
- Sidebar mostrando módulos no permitidos según el rol del usuario

Detalle completo de cada bug, quién lo encontró y cómo se resolvió, en la documentación de cada integrante (`docs/`).

## Despliegue en AWS EC2

1. Lanzar una instancia EC2 (t2.micro, capa gratuita) con Ubuntu.
2. Instalar Nginx, PHP 8.2 y Composer.
3. Clonar el repositorio y configurar `.env` de producción.
4. Ejecutar:
```bash
   composer install --optimize-autoloader --no-dev
   php artisan migrate --seed
   php artisan config:cache
```
5. Configurar Nginx para apuntar a la carpeta `public/` del proyecto.

---

## Flujo de Git + Jira

- Cada tarea de Jira tiene un ID tipo `SCRUM-XX`.
- Rama: `SCRUM-XX-descripcion-corta`
- Commit: `SCRUM-XX: feat/fix/chore descripción`
- Al mergear el PR a `develop`, la tarjeta se vincula automáticamente en la sección "Desarrollo" de Jira.
- Recuerden mover el estado de la tarjeta manualmente.
- Nunca pushear directo a `main`. Integración vía Pull Request a `develop` (protección: 1 review aprobando).

---

*HidroGest · Universidad Mariano Gálvez de Guatemala · 2026*