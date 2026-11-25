(function () {
  var enCurso = {};

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-sabe]');
    var img = '<img class= \"icono\" src=\"imagenes\\'
    if (!btn) return;
    var id = btn.dataset.sabe;
    if (enCurso[id]) return;
    enCurso[id] = true;
    btn.disabled = true;

    fetch('algoritmos.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id: id })
    })
      .then(function (r) { return r.json(); })
      .then(function (r) {
        if (!r.ok) throw new Error(r.error || 'error desconocido');
        document.querySelectorAll('.algo-card[data-algo="' + id + '"]').forEach(function (card) {
          card.classList.toggle('sabido', r.sabido);
          card.querySelector('[data-sabe]').innerHTML = r.sabido ? img + 'no.png\">' : img + 'si.png\">';
        });
      })
      .catch(function (err) { alert('No se pudo guardar: ' + err.message); })
      .then(function () { delete enCurso[id]; btn.disabled = false; });
  });
})();
