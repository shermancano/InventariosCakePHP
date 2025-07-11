$(document).ready(function() {
    $("#CentroCostoAdd").submit(function() {
        if (confirm('Recordatorio: Verifique el Centro de Costo Padre seleccionado, presione Cancelar para volver a editar o Aceptar para continuar ¿Desea continuar?') == 1) {
            return true;
        } else {
            return false;
        }
    });
});