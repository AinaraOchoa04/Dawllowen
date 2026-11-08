'use strict';

// Al escribir el usuario, pide su pregunta de seguridad al PHP.
(function () {
    var campo = document.getElementById('usuario');
    var caja = document.getElementById('texto-pregunta');
    if (!campo || !caja) {
        return;
    }

    campo.addEventListener('input', function () {
        var nombre = campo.value.trim();
        if (nombre.length < 3) {
            caja.textContent = '';
            return;
        }
        fetch('pregunta.php?usuario=' + encodeURIComponent(nombre))
            .then(function (respuesta) {
                return respuesta.json();
            })
            .then(function (datos) {
                if (datos.pregunta) {
                    caja.textContent = 'Pregunta de seguridad: ' + datos.pregunta;
                } else {
                    caja.textContent = 'Si el usuario existe, aquí aparecerá su pregunta.';
                }
            })
            .catch(function () {
                caja.textContent = 'No se ha podido consultar la pregunta. Si ya la sabes, escríbela abajo.';
            });
    });
})();
