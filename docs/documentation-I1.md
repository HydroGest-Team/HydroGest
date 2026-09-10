# HidroGest — Resumen de Autenticación y Backend Lead (Integrante 1)

**Rol en el equipo:** Scrum Master + Backend Lead — autenticación, roles y permisos, motor de cálculo de consumo, coordinación técnica entre integrantes.

## Sprint 1 — Setup del proyecto (26–28 ago)

- Repositorio GitHub, ramas (`main`/`develop`/`feature/*`), conexión con Jira, README inicial.
- Instalación de Laravel 11 local (XAMPP), configuración de `.env` con MariaDB.
- Creación de los 7 modelos Eloquent vacíos (`User`, `Role`, `Cliente`, `Contador`, `Tarifa`, `Lectura`, `Pago`): solo clase, `$fillable` y relaciones básicas, sin lógica — a la espera de las migraciones de I2.

## Sprint 2 — Autenticación con roles (1–5 sep)

**Autenticación:** instalación de Laravel Breeze (blade). Se decidió por Breeze en vez de `Auth::attempt()` manual por ahorro de tiempo (~1.5h) y menor riesgo de bugs en un prototipo con deadline ajustado.

**Middleware `CheckRole`:** creado y registrado como alias `role` en `bootstrap/app.php`. Valida `auth()->user()->role->nombre_rol` contra los roles permitidos en cada grupo de rutas.

**Matriz de permisos aplicada:**
| Rol | Clientes | Contadores | Tarifas | Dashboard |
|---|---|---|---|---|
| Administrador | ✅ | ✅ | ✅ | ✅ |
| Secretaria | ✅ | ✅ | ❌ | ✅ |
| Empleado | ❌ | ✅ | ✅ | — |

**Integración con el esquema de I2:** el modelo `User` original de Breeze esperaba tabla `users` con columnas `name`/`email`/`password`. Se coordinó con I2 para adaptar `tb_usuarios` al esquema estándar de Laravel (en vez de reescribir la lógica interna de Breeze), agregando `$table = 'tb_usuarios'` en `User.php` y