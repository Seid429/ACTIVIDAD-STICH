<script>
    // Botones con data-ver="id-del-input" alternan la visibilidad de la contraseña.
    document.querySelectorAll('[data-ver]').forEach(function (boton) {
        boton.addEventListener('click', function () {
            var campo = document.getElementById(boton.dataset.ver);
            var oculto = campo.type === 'password';
            campo.type = oculto ? 'text' : 'password';
            boton.querySelector('span').textContent = oculto ? 'visibility_off' : 'visibility';
        });
    });
</script>
