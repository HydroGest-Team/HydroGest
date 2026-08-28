# HydroGest
HydroGest - Sistema de gestion hidrica

##Chupluc el repo anterior, ready con el nuevo##
---

## Flujo de Git + Jira

- Cada tarea de Jira tiene un ID tipo `SCRUM-XX`.
- Rama: `SCRUM-XX-descripcion-corta`
- Commit: `SCRUM-XX: feat/fix/chore descripción`
- Al mergear el PR a `develop`, la tarjeta se vincula automáticamente
  en la sección "Desarrollo" de Jira.
- Recuerden mover el estado de la tarjeta manualmente-

---

## Estado actual 27/08/2026

- **Sprint 1 — SCRUM-2 (Frontend):** Layout base integrado con la plantilla **SB Admin** (Bootstrap 5). Navbar, sidebar y footer funcionando como partials reutilizables (`layouts/partials/`). Dashboard de prueba cargando correctamente en `/dashboard`.
- Rutas de módulos (Clientes, Contadores, Tarifas, Lecturas, Pagos) en el sidebar están como placeholders (`#`) hasta que se implementen en Sprint 2 — actualizar con `route()` cuando estén listas.
