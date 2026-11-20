(function () {

  var enCurso = {}; 

  function post(datos) {
    return fetch('tiempo.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(datos)
    }).then(function (r) {
      return r.text().then(function (txt) {
        try {
          return JSON.parse(txt);
        } catch (e) {
          throw new Error('el servidor no devolvió JSON (¿error de PHP o ruta de tiempo.php incorrecta?)');
        }
      });
    });
  }

  function eliminar(id) {
    if (enCurso[id]) return;
    if (!window.confirm('¿Eliminar este tiempo? No se puede deshacer.')) return;

    enCurso[id] = true;
    post({ accion: 'eliminar', id: id })
      .then(function (r) {
        if (!r.ok) throw new Error(r.error || 'error desconocido');
        document.dispatchEvent(new CustomEvent('tiempo:eliminado', {
          detail: { id: id, record: r.record }
        }));
      })
      .catch(function (e) {
        console.error('[eliminar_tiempo]', e.message);
        alert('No se pudo eliminar el tiempo: ' + e.message);
      })
      .then(function () { delete enCurso[id]; });
  }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-eliminar]');
    if (!btn) return;
    e.preventDefault();
    btn.blur(); 
    eliminar(parseInt(btn.dataset.eliminar, 10));
  });

  // ---- Efectos comunes a las dos páginas ----
  document.addEventListener('tiempo:eliminado', function (e) {
    var id = e.detail.id;
    var record = e.detail.record;

    // Perfil: quita la fila del historial (y avisa si se queda vacío)
    document.querySelectorAll('tr[data-tiempo="' + id + '"]').forEach(function (tr) {
      var cuerpo = tr.parentNode;
      tr.remove();
      if (cuerpo.id === 'historialTiempos' && !cuerpo.querySelector('tr[data-tiempo]')) {
        var vacia = document.createElement('tr');
        var td = document.createElement('td');
        td.colSpan = 5;
        td.className = 'vacio';
        td.textContent = 'Aún no tienes tiempos guardados.';
        vacia.appendChild(td);
        cuerpo.appendChild(vacia);
      }
    });

    // Récord (cronómetro y perfil): puede haber cambiado si se borró el mejor tiempo
    var recT = document.getElementById('recordT');
    if (recT) recT.textContent = record;
    var recP = document.getElementById('recordPerfil');
    if (recP) recP.textContent = record + ' record';
  });

})();
