
(function () {

  if (typeof window.toggleTimer !== 'function') {
    console.error('[guardar_tiempo] No se encontró toggleTimer(). ¿Está cargado script.js ANTES que este archivo?');
    return;
  }

  var ultimoId = null;     
  var penal = 'ninguna';  

  var btnMas2 = document.getElementById('btnMas2');
  var btnDNF  = document.getElementById('btnDNF');

  if (!btnMas2 || !btnDNF) {
    console.warn('[guardar_tiempo] Faltan los botones con id="btnMas2" y id="btnDNF". +2 y DNF no funcionarán.');
  }

  function mostrarError(msg) {
    console.error('[guardar_tiempo]', msg);
    var h = document.getElementById('timerHint');
    var error = 'No se puede guardar, ' + msg;
    if (h) h.textContent = error; alert(error);
  }

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
          throw new Error('el servidor no devolvió JSON (¿error de PHP o ruta de tiempo.php incorrecta?): ' + txt.slice(0, 200));
        }
      });
    });
  }

  function refrescarBotones() {
    [[btnMas2, 'mas2'], [btnDNF, 'dnf']].forEach(function (par) {
      if (!par[0]) return;
      var activo = penal === par[1];
      par[0].classList.toggle('btn-primary', activo);
      par[0].classList.toggle('btn-outline', !activo);
    });
  }

  var original = window.toggleTimer;
  window.toggleTimer = function () {
    var estabaCorriendo = running;
    var mezcla = document.getElementById('timerScramble').textContent; 
    original();
    if (!estabaCorriendo) return;

    ultimoId = null;
    penal = 'ninguna';
    refrescarBotones();

    var sel = document.getElementById('selCubo');
    if (!sel.value) { mostrarError('elige un cubo (añade uno en tu perfil si no tienes)'); return; }

    post({
      accion: 'guardar',
      ms: Math.round(times[0] * 1000),
      mezcla: mezcla,
      idcubo: sel.value
    }).then(function (r) {
      if (r.ok) { ultimoId = r.id; }
      else { mostrarError(r.error || 'error desconocido'); }
    }).catch(function (e) {
      mostrarError(e.message);
    });
  };

  function marcar(tipo) {
    if (!ultimoId) return;
    var nueva = (penal === tipo) ? 'ninguna' : tipo;
    post({
      accion: 'penalizar',
      id: ultimoId,
      mas2: nueva === 'mas2' ? 1 : 0,
      dnf: nueva === 'dnf' ? 1 : 0
    }).then(function (r) {
      if (r.ok) { penal = nueva; refrescarBotones(); }
      else { mostrarError(r.error || 'error desconocido'); }
    }).catch(function (e) {
      mostrarError(e.message);
    });
  }

  if (btnMas2) btnMas2.addEventListener('click', function () { marcar('mas2'); });
  if (btnDNF)  btnDNF.addEventListener('click', function () { marcar('dnf'); });

})();
