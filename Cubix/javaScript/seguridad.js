// seguridad.js: todos los ajustes de seguridad del lado del navegador.
// Se carga ANTES que script.js en index.php, registro.php y main.php.
//   1. CSRF: añade el token a todas las peticiones fetch que no son GET.
//   2. Limpieza de inputs: no deja escribir ( ) / \ " < >
//   3. Contraseñas: botón del ojo para ver la contraseña en claro.

// ---------- 1. CSRF ----------
(function () {
  var meta  = document.querySelector('meta[name="csrf-token"]');
  var token = meta ? meta.content : '';
  var fetchOriginal = window.fetch;

  window.fetch = function (url, opciones) {
    opciones = opciones || {};
    var metodo = (opciones.method || 'GET').toUpperCase();
    var mismoSitio = typeof url === 'string' && !/^(https?:)?\/\//i.test(url);   // solo rutas propias

    if (token && mismoSitio && metodo !== 'GET' && metodo !== 'HEAD') {
      var cabeceras = new Headers(opciones.headers || {});
      cabeceras.set('X-CSRF-Token', token);
      opciones.headers = cabeceras;
    }
    return fetchOriginal.call(this, url, opciones);
  };
})();

// ---------- 2 y 3. Inputs ----------
// Limpieza de inputs


(function () {
 
  var PROHIBIDOS = /[()\/\\"<>]/g;
 
  function limpiar(el) {
    var original = el.value;
    var limpio = original.replace(PROHIBIDOS, '');
    if (original === limpio) return;
 
    var pos = null;
    try { pos = el.selectionStart; } catch (e) {}
 
    if (pos !== null && pos !== undefined) {
      var nuevaPos = original.slice(0, pos).replace(PROHIBIDOS, '').length;
      el.value = limpio;
      try { el.setSelectionRange(nuevaPos, nuevaPos); } catch (e) {}
    } else {
      el.value = limpio;
    }
  }
 
  var SELECTOR = 'input:not([type=checkbox]):not([type=radio]):not([type=file]):not([type=submit]):not([type=button]), textarea';
 
  document.addEventListener('input', function (e) {
    if (e.target.matches(SELECTOR)) limpiar(e.target);
  });
 
  document.addEventListener('submit', function (e) {
    e.target.querySelectorAll(SELECTOR).forEach(limpiar);
  }, true);
 
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll(SELECTOR).forEach(limpiar);
  });
 
})();
 
// Ver contraseña

(function () {

  var OJO = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>';
  var OJO_TACHADO = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a18.5 18.5 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12A3 3 0 1 1 9.88 9.88"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

  var estilo = document.createElement('style');
  estilo.textContent =
    '.pw-wrap{position:relative;display:block;}' +
    '.pw-wrap input{padding-right:42px !important;}' +
    '.pw-toggle{position:absolute;top:50%;right:8px;transform:translateY(-50%);' +
      'display:flex;align-items:center;justify-content:center;width:30px;height:30px;' +
      'background:none;border:none;border-radius:6px;padding:0;cursor:pointer;' +
      'color:var(--text-faint,#5c5e69);transition:color .15s;}' +
    '.pw-toggle:hover,.pw-toggle:focus-visible{color:var(--text,#eae8e1);outline:none;}' +
    '.pw-toggle.activo{color:var(--yellow-face,#ffd43b);}';
  document.head.appendChild(estilo);

  function añadirOjo(input) {
    if (input.dataset.ojo) return;
    input.dataset.ojo = '1';

    var wrap = document.createElement('span');
    wrap.className = 'pw-wrap';
    input.parentNode.insertBefore(wrap, input);
    wrap.appendChild(input);

    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'pw-toggle';
    btn.innerHTML = OJO;
    btn.setAttribute('aria-label', 'Mostrar contraseña');
    btn.setAttribute('aria-pressed', 'false');
    btn.title = 'Mostrar contraseña';
    wrap.appendChild(btn);

    // Evita que el botón le quite el foco al input
    btn.addEventListener('mousedown', function (e) { e.preventDefault(); });

    btn.addEventListener('click', function () {
      var visible = input.type === 'text';
      input.type = visible ? 'password' : 'text';
      btn.innerHTML = visible ? OJO : OJO_TACHADO;
      btn.classList.toggle('activo', !visible);
      btn.setAttribute('aria-pressed', String(!visible));
      var txt = visible ? 'Mostrar contraseña' : 'Ocultar contraseña';
      btn.setAttribute('aria-label', txt);
      btn.title = txt;
      input.focus();
    });

    // Al enviar el formulario, vuelve a ocultarla por seguridad
    if (input.form) {
      input.form.addEventListener('submit', function () {
        input.type = 'password';
      });
    }
  }

  function iniciar() {
    document.querySelectorAll('input[type=password]').forEach(añadirOjo);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciar);
  } else {
    iniciar();
  }

})();



