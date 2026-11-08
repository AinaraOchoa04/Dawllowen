'use strict';

// Cuenta atrás y mezclador de poción. Los dos escriben en el DOM.
(function () {
    var cuenta = document.getElementById('cuenta-atras');
    if (cuenta) {
        var destino = new Date(2027, 9, 31, 0, 0, 0);

        function pintarCuenta() {
            var ms = destino.getTime() - Date.now();
            if (ms < 0) {
                ms = 0;
            }
            var dias = Math.floor(ms / 86400000);
            var horas = Math.floor((ms % 86400000) / 3600000);
            var minutos = Math.floor((ms % 3600000) / 60000);
            cuenta.textContent = 'Faltan ' + dias + ' días, ' + horas + ' horas y ' + minutos + ' minutos para el 31 de octubre de 2027.';
        }

        pintarCuenta();
        window.setInterval(pintarCuenta, 30000);
    }

    var form = document.getElementById('form-pocion');
    var resultado = document.getElementById('resultado-pocion');
    var caldero = document.getElementById('caldero');
    if (!form || !resultado || !caldero) {
        return;
    }

    function mezclar() {
        var a = document.getElementById('ing1').value;
        var b = document.getElementById('ing2').value;
        var c = document.getElementById('ing3').value;
        if (a === '' || b === '' || c === '') {
            resultado.textContent = 'Elige tres ingredientes.';
            return;
        }
        if (a === b || a === c || b === c) {
            resultado.textContent = 'Elige tres ingredientes distintos.';
            caldero.style.background = '#3b214c';
            return;
        }

        var clave = [a, b, c].sort().join('|');
        if (clave === 'calabaza|granada|sal') {
            resultado.textContent = 'Ponche de medianoche. Has dado con la mezcla secreta: está en las recetas, con marco dorado.';
            caldero.style.background = '#e2b657';
            return;
        }
        if (clave === 'fantasma|hueso|murcielago') {
            resultado.textContent = 'Niebla embotellada. Huele a sótano y no se bebe.';
            caldero.style.background = '#5a3d6e';
            return;
        }

        resultado.textContent = 'Burbujea, cambia de color y no pasa nada útil. Prueba otra mezcla.';
        caldero.style.background = '#243808';
    }

    form.addEventListener('submit', function (evento) {
        evento.preventDefault();
        mezclar();
    });
})();
