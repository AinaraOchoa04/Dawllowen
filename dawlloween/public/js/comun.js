'use strict';

// Menú, aviso si abren el HTML a pelo y el secreto de la araña.
(function () {
    if (window.location.protocol === 'file:') {
        var aviso = document.createElement('p');
        aviso.className = 'alerta aviso-archivo';
        aviso.setAttribute('role', 'status');
        aviso.textContent = 'Estás abriendo el archivo en el disco. Entra por http://halloween.dev/ para que el acceso y el correo funcionen.';
        document.body.prepend(aviso);
    }

    var enlace = document.getElementById('enlace-cuenta');
    if (enlace && window.location.protocol !== 'file:') {
        fetch('estado.php', { headers: { Accept: 'application/json' } })
            .then(function (respuesta) {
                if (!respuesta.ok) {
                    return null;
                }
                return respuesta.json();
            })
            .then(function (datos) {
                if (!datos || !datos.logueado || !datos.usuario) {
                    return;
                }
                enlace.textContent = 'Salir (' + datos.usuario + ')';
                enlace.href = 'logout.php';
                enlace.classList.remove('activo');
                enlace.removeAttribute('aria-current');
            })
            .catch(function () {
                // Si Apache no está arrancado, el enlace se queda en Entrar.
            });
    }

    var arana = document.getElementById('arana');
    var secreto = document.getElementById('secreto');
    if (!arana || !secreto) {
        return;
    }

    var clics = 0;
    arana.addEventListener('click', function () {
        clics += 1;
        if (clics >= 5) {
            secreto.hidden = false;
            secreto.textContent = 'Secreto: en la poción de la portada mezcla calabaza, granada y sal.';
        }
    });
})();
