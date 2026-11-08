'use strict';

// Filtros, búsqueda, favoritos y ficha. Las cartas ya están en el HTML.
(function () {
    var rejilla = document.getElementById('rejilla');
    var buscar = document.getElementById('buscar');
    var vacio = document.getElementById('sin-resultados');
    var modalEl = document.getElementById('modalReceta');
    var titulo = document.getElementById('modalTitulo');
    var cuerpo = document.getElementById('modalCuerpo');
    if (!rejilla || !buscar || !vacio) {
        return;
    }

    var CLAVE = 'umbria-favoritas';
    var filtro = 'todas';
    var modal = null;
    if (modalEl && window.bootstrap) {
        modal = new window.bootstrap.Modal(modalEl);
    }

    function leerFavs() {
        try {
            var datos = JSON.parse(localStorage.getItem(CLAVE) || '[]');
            return Array.isArray(datos) ? datos : [];
        } catch (error) {
            return [];
        }
    }

    function guardarFavs(lista) {
        try {
            localStorage.setItem(CLAVE, JSON.stringify(lista));
        } catch (error) {
            // El navegador puede bloquear el almacenamiento.
        }
    }

    function normalizar(texto) {
        return texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function marcarFav(boton, activo) {
        boton.setAttribute('aria-pressed', activo ? 'true' : 'false');
        boton.textContent = activo ? 'Guardada' : 'Guardar';
        boton.classList.toggle('guardada', activo);
    }

    var favs = new Set(leerFavs());
    document.querySelectorAll('.receta-col').forEach(function (col) {
        var boton = col.querySelector('.btn-favorito');
        if (boton && favs.has(col.dataset.id)) {
            marcarFav(boton, true);
        }
    });

    function aplicar() {
        var q = normalizar(buscar.value.trim());
        var visibles = 0;
        document.querySelectorAll('.receta-col').forEach(function (col) {
            var nombre = normalizar(col.dataset.nombre || '');
            var tipo = col.dataset.tipo;
            var esTop = col.dataset.top === 'si';
            var esFav = favs.has(col.dataset.id);
            var coincideTexto = q === '' || nombre.indexOf(q) !== -1;
            var coincideFiltro = true;
            if (filtro === 'top') {
                coincideFiltro = esTop;
            } else if (filtro === 'guardadas') {
                coincideFiltro = esFav;
            } else if (filtro !== 'todas') {
                coincideFiltro = tipo === filtro;
            }
            var ver = coincideTexto && coincideFiltro;
            col.hidden = !ver;
            if (ver) {
                visibles += 1;
            }
        });
        vacio.hidden = visibles !== 0;
    }

    document.querySelectorAll('.filtro').forEach(function (boton) {
        boton.addEventListener('click', function () {
            filtro = boton.dataset.filtro;
            document.querySelectorAll('.filtro').forEach(function (otro) {
                var activo = otro === boton;
                otro.classList.toggle('activo', activo);
                otro.setAttribute('aria-pressed', activo ? 'true' : 'false');
            });
            aplicar();
        });
    });

    buscar.addEventListener('input', aplicar);

    function abrir(col) {
        var nombre = col.querySelector('h3');
        var detalle = col.querySelector('.detalle');
        if (!nombre || !detalle || !titulo || !cuerpo) {
            return;
        }
        titulo.textContent = nombre.textContent;
        var copia = detalle.cloneNode(true);
        copia.hidden = false;
        cuerpo.replaceChildren(copia);
        if (modal) {
            modal.show();
        }
    }

    rejilla.addEventListener('click', function (evento) {
        var col = evento.target.closest('.receta-col');
        if (!col) {
            return;
        }
        if (evento.target.closest('.ver-receta')) {
            abrir(col);
        }
        var fav = evento.target.closest('.btn-favorito');
        if (!fav) {
            return;
        }
        var id = col.dataset.id;
        if (favs.has(id)) {
            favs.delete(id);
            marcarFav(fav, false);
        } else {
            favs.add(id);
            marcarFav(fav, true);
        }
        guardarFavs(Array.from(favs));
        aplicar();
    });

    if (window.location.hash) {
        var destacada = document.getElementById(window.location.hash.slice(1));
        if (destacada && destacada.classList.contains('receta-col')) {
            abrir(destacada);
        }
    }

    aplicar();
})();
