## 27 al 30 de agosto

- **Sprint 1 — SCRUM-2 (Frontend):** Layout base integrado con la plantilla **SB Admin** (Bootstrap 5). Navbar, sidebar y footer funcionando como partials reutilizables (`layouts/partials/`). Dashboard de prueba cargando correctamente en `/dashboard`.
- Rutas de módulos (Clientes, Contadores, Tarifas, Lecturas, Pagos) en el sidebar están como placeholders (`#`) hasta que se implementen en Sprint 2 — actualizar con `route()` cuando estén listas.

- **Sprint 1 — SCRUM-14 (Frontend):** Layout base integrado con la plantilla **SB Admin** (Bootstrap 5). Navbar, sidebar y footer funcionando como partials reutilizables (`layouts/partials/`). Dashboard de prueba cargando correctamente en `/dashboard`.
- **Vista de login y bienvenida por rol:** Layout de autenticación separado (`layouts/guest.blade.php`) centrado en pantalla. Vista de login en `resources/views/auth/login.blade.php` (path esperado por Laravel Breeze). Vista de bienvenida post-login (`bienvenida.blade.php`) con contenido condicional según rol (admin / secretaria / lector).
- Rutas de módulos (Clientes, Contadores, Tarifas, Lecturas, Pagos) y acciones de login/logout están como placeholders (`#`) hasta que se implemente la autenticación real (Breeze recomendado, pendiente de confirmación del equipo) y las rutas de I2/I3 en Sprint 2.
