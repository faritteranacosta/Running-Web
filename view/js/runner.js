function confirmarEliminacion() {
    if (confirm("¿Está seguro que desea eliminar su cuenta? Tenga en cuenta que después de eliminarla no podrá acceder más con esta cuenta.")) {
        // Redirige al servidor para eliminar la cuenta
        document.getElementById('formEliminar').submit();
    }
}