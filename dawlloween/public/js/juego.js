'use strict';

/*
 * La cripta de la memoria.
 * Se crean botones, se les cambia la clase para voltearlos
 * y se escriben los contadores. No hay canvas: es todo DOM.
 */
(function () {
    var tablero = document.getElementById('tablero');
    var marcador = document.getElementById('marcador');
    var mejor = document.getElementById('mejor');
    var mensaje = document.getElementById('mensaje-juego');
    var btnFacil = document.getElementById('btn-facil');
    var btnNormal = document.getElementById('btn-normal');
    var btnReiniciar = document.getElementById('btn-reiniciar');
    if (!tablero || !marcador || !mejor || !mensaje) {
        return;
    }

    var SIMBOLOS = [
        { emoji: '🎃', nombre: 'calabaza' },
        { emoji: '👻', nombre: 'fantasma' },
        { emoji: '🦇', nombre: 'murciélago' },
        { emoji: '🕸️', nombre: 'telaraña' },
        { emoji: '💀', nombre: 'calavera' },
        { emoji: '🕯️', nombre: 'vela' },
        { emoji: '🧛', nombre: 'vampiro' },
        { emoji: '🐱', nombre: 'gato negro' }
    ];

    var dificultad = 'normal';
    var movimientos = 0;
    var segundos = 0;
    var paresHechos = 0;
    var totalPares = 0;
    var bloqueado = false;
    var abiertas = [];
    var reloj = null;

    function claveMejor() {
        return 'umbria-cripta-' + dificultad;
    }

    function leerNumero(clave) {
        try {
            return Number(localStorage.getItem(clave) || 0);
        } catch (error) {
            return 0;
        }
    }

    function guardarNumero(clave, valor) {
        try {
            localStorage.setItem(clave, String(valor));
        } catch (error) {
            // Sin almacenamiento no se guarda la marca, el juego sigue.
        }
    }

    function barajar(lista) {
        var copia = lista.slice();
        for (var i = copia.length - 1; i > 0; i -= 1) {
            var j = Math.floor(Math.random() * (i + 1));
            var tmp = copia[i];
            copia[i] = copia[j];
            copia[j] = tmp;
        }
        return copia;
    }

    function pararReloj() {
        if (reloj !== null) {
            window.clearInterval(reloj);
            reloj = null;
        }
    }

    function arrancarReloj() {
        if (reloj !== null) {
            return;
        }
        reloj = window.setInterval(function () {
            segundos += 1;
            pintarMarcador();
        }, 1000);
    }

    function pintarMarcador() {
        marcador.textContent = 'Movimientos: ' + movimientos + ' · Tiempo: ' + segundos + ' s';
        var marca = leerNumero(claveMejor());
        mejor.textContent = marca > 0
            ? 'Mejor marca en ' + dificultad + ': ' + marca + ' movimientos.'
            : 'Todavía no hay marca guardada en este navegador.';
    }

    function crearCarta(simbolo) {
        var boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'carta';
        boton.dataset.par = simbolo.nombre;
        boton.setAttribute('aria-label', 'Carta boca abajo');
        boton.innerHTML = '<span class="carta-inner"><span class="dorso" aria-hidden="true"></span><span class="cara" aria-hidden="true">' + simbolo.emoji + '</span></span>';
        boton.addEventListener('click', function () {
            alPulsar(boton, simbolo.nombre);
        });
        return boton;
    }

    function ganar() {
        pararReloj();
        var anterior = leerNumero(claveMejor());
        if (anterior === 0 || movimientos < anterior) {
            guardarNumero(claveMejor(), movimientos);
        }
        var extra = '';
        if (dificultad === 'normal' && movimientos <= 12) {
            extra = ' Has cerrado la cripta casi sin dudar.';
        }
        mensaje.textContent = 'Has encontrado todas las parejas en ' + movimientos + ' movimientos y ' + segundos + ' segundos.' + extra;
        pintarMarcador();
    }

    function alPulsar(carta, nombre) {
        if (bloqueado) {
            return;
        }
        if (carta.classList.contains('volteada') || carta.classList.contains('encontrada')) {
            return;
        }

        arrancarReloj();
        carta.classList.add('volteada');
        carta.setAttribute('aria-label', nombre);
        abiertas.push(carta);
        if (abiertas.length < 2) {
            return;
        }

        bloqueado = true;
        movimientos += 1;
        pintarMarcador();
        var primera = abiertas[0];
        var segunda = abiertas[1];

        if (primera.dataset.par === segunda.dataset.par) {
            primera.classList.add('encontrada');
            segunda.classList.add('encontrada');
            primera.setAttribute('aria-label', 'Pareja encontrada: ' + nombre);
            segunda.setAttribute('aria-label', 'Pareja encontrada: ' + nombre);
            abiertas = [];
            bloqueado = false;
            paresHechos += 1;
            if (paresHechos === totalPares) {
                ganar();
            }
            return;
        }

        window.setTimeout(function () {
            primera.classList.remove('volteada');
            segunda.classList.remove('volteada');
            primera.setAttribute('aria-label', 'Carta boca abajo');
            segunda.setAttribute('aria-label', 'Carta boca abajo');
            abiertas = [];
            bloqueado = false;
        }, 800);
    }

    function empezar() {
        pararReloj();
        movimientos = 0;
        segundos = 0;
        paresHechos = 0;
        bloqueado = false;
        abiertas = [];
        mensaje.textContent = '';
        tablero.innerHTML = '';

        var cantidad = dificultad === 'facil' ? 6 : 8;
        totalPares = cantidad;
        var elegidos = SIMBOLOS.slice(0, cantidad);
        var mazo = barajar(elegidos.concat(elegidos));
        mazo.forEach(function (simbolo) {
            tablero.appendChild(crearCarta(simbolo));
        });
        pintarMarcador();
    }

    function elegir(modo) {
        dificultad = modo;
        btnFacil.setAttribute('aria-pressed', modo === 'facil' ? 'true' : 'false');
        btnNormal.setAttribute('aria-pressed', modo === 'normal' ? 'true' : 'false');
        btnFacil.classList.toggle('activo', modo === 'facil');
        btnNormal.classList.toggle('activo', modo === 'normal');
        empezar();
    }

    btnFacil.addEventListener('click', function () {
        elegir('facil');
    });
    btnNormal.addEventListener('click', function () {
        elegir('normal');
    });
    btnReiniciar.addEventListener('click', empezar);
    empezar();
})();
