// Herramienta para buscar usuarios y añadirlos como amigos (sin recargar la página).
(function () {

  var input = document.getElementById('amigoBuscar');
  var cont  = document.getElementById('amigoResultados');
  var lista = document.getElementById('listaAmigos');   // widget "Amigos" (opcional)

  if (!input || !cont) {
    console.warn('[amigos] Faltan los elementos #amigoBuscar y #amigoResultados.');
    return;
  }

  var colores = ['var(--green-face)', 'var(--orange-face)', 'var(--blue-face)', 'var(--red-face)', 'var(--yellow-face)'];
  var temporizador = null;

  function post(datos) {
    return fetch('amigos.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(datos)
    }).then(function (r) {
      return r.text().then(function (txt) {
        try {
          return JSON.parse(txt);
        } catch (e) {
          throw new Error('el servidor no devolvió JSON (¿ruta de amigos.php incorrecta o error de PHP?)');
        }
      });
    });
  }

  function mensaje(txt) {
    cont.innerHTML = '';
    var d = document.createElement('div');
    d.style.padding = '16px 0';
    d.style.fontSize = '13px';
    d.style.color = 'var(--text-dim)';
    d.textContent = txt;
    cont.appendChild(d);
  }

  function iniciales(u) {
    return ((u.nom[0] || '') + (u.ape[0] || '')).toUpperCase();
  }

  function pintar(usuarios) {
    cont.innerHTML = '';
    if (!usuarios.length) {
      mensaje('No se encontraron usuarios.');
      return;
    }

    usuarios.forEach(function (u) {
      var fila = document.createElement('div');
      fila.className = 'feed-item';
      fila.style.alignItems = 'center';

      var av = document.createElement('div');
      av.className = 'avatar';
      av.style.background = colores[u.id % colores.length];
      av.textContent = iniciales(u);

      var cuerpo = document.createElement('div');
      cuerpo.className = 'feed-body';
      var who = document.createElement('div');
      who.className = 'who';
      var b = document.createElement('b');
      b.textContent = u.nom + ' ' + u.ape;
      who.appendChild(b);
      cuerpo.appendChild(who);
      var idLinea = document.createElement('div');
      idLinea.className = 'when';
      idLinea.textContent = 'ID ' + u.id;
      cuerpo.appendChild(idLinea);

      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'btn btn-outline btn-small';
      btn.textContent = 'Añadir';
      btn.addEventListener('click', function () { anadir(u, fila, btn); });

      fila.appendChild(av);
      fila.appendChild(cuerpo);
      fila.appendChild(btn);
      cont.appendChild(fila);
    });
  }

  function buscar() {
    post({ accion: 'buscar', q: input.value.trim() })
      .then(function (r) {
        if (r.ok) pintar(r.usuarios);
        else mensaje('Error: ' + (r.error || 'desconocido'));
      })
      .catch(function (e) { mensaje('Error: ' + e.message); });
  }

  function anadirALista(u) {
    if (!lista) return;
    var row = document.createElement('div');
    row.className = 'rank-row';
    row.innerHTML = '<span class="rank-num"></span><span class="rname"></span><span class="rtime">0.0</span>';
    row.querySelector('.rname').textContent = ' ' + u.nom + ' ' + u.ape;
    lista.appendChild(row);
  }

  function anadir(u, fila, btn) {
    btn.disabled = true;
    btn.textContent = 'Añadiendo…';
    post({ accion: 'anadir', id: u.id })
      .then(function (r) {
        if (r.ok) {
          fila.remove();
          anadirALista(u);
          if (!cont.children.length) mensaje('No quedan más usuarios que mostrar.');
        } else {
          btn.disabled = false;
          btn.textContent = 'Reintentar';
          console.error('[amigos]', r.error);
        }
      })
      .catch(function (e) {
        btn.disabled = false;
        btn.textContent = 'Reintentar';
        console.error('[amigos]', e.message);
      });
  }

  // Busca mientras escribes (con una pequeña espera)
  input.addEventListener('input', function () {
    clearTimeout(temporizador);
    temporizador = setTimeout(buscar, 250);
  });

  buscar();   // sugerencias iniciales

})();
