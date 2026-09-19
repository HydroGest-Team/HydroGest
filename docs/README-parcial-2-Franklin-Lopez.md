---

## Funciones agregadas para parcial 2

- **Exportar a CSV**: cada listado principal (Clientes, Contadores, Tarifas, Lecturas, Pagos) tiene un botón "Exportar CSV" que descarga el listado completo vía `streamDownload`, sin generar archivos temporales en el servidor. Incluye BOM UTF-8 para que Excel muestre bien acentos y "ñ".
  - Rutas nuevas: `clientes.export`, `contadores.export`, `tarifas.export`, `lecturas.export`, `pagos.export` — registradas antes de cada `Route::resource(...)` correspondiente, dentro del mismo grupo de middleware/roles.
  - Métodos `export()` agregados a `ClienteController`, `ContadorController`, `TarifaController`, `LecturaController`, `PagoController`.

- **Confirmación antes de eliminar**: reemplazado el `confirm()` nativo del navegador por un modal Bootstrap de confirmación, en los dos módulos que permiten eliminar (Clientes y Contadores). Un solo modal compartido por vista, reutilizado vía JS con los datos de la fila seleccionada (mismo patrón que el modal de editar).