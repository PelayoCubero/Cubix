<section class="view" id="algos">
    <div class="view-header">
      <div class="eyebrow">Biblioteca de entrenamiento</div>
      <h1>Algoritmos</h1>
    </div>

    <div class="method-tabs">
      <button class="tab active" data-method="cfop">CFOP</button>
      <button class="tab" data-method="other">Roux / ZZ (próximamente)</button>
    </div>

    <div id="cfopPanel">
      <div class="stage-tabs">
        <button class="tab stage active" data-stage="f2l">F2L</button>
        <button class="tab stage" data-stage="oll">OLL</button>
        <button class="tab stage" data-stage="pll">PLL</button>
      </div>

      <div class="algo-grid" id="algoGrid">

        <div class="algo-card show" data-stage="f2l">
          <div class="top">
            <div class="net" id="net-f2l-1"></div>
            <div><div class="algo-name">Inserción básica</div><div class="algo-stage">F2L · par ya emparejado</div></div>
          </div>
          <div class="algo-notation">U R U' R'</div>
        </div>

        <div class="algo-card show" data-stage="f2l">
          <div class="top">
            <div class="net" id="net-f2l-2"></div>
            <div><div class="algo-name">Caso 3 · esquina abajo</div><div class="algo-stage">F2L</div></div>
          </div>
          <div class="algo-notation">U' R U R' U R U' R'</div>
        </div>

        <div class="algo-card" data-stage="oll">
          <div class="top">
            <div class="net" id="net-oll-1"></div>
            <div><div class="algo-name">Sune</div><div class="algo-stage">OLL · último layer</div></div>
          </div>
          <div class="algo-notation">R U R' U R U2 R'</div>
        </div>

        <div class="algo-card" data-stage="oll">
          <div class="top">
            <div class="net" id="net-oll-2"></div>
            <div><div class="algo-name">Antisune</div><div class="algo-stage">OLL</div></div>
          </div>
          <div class="algo-notation">R U2 R' U' R U' R'</div>
        </div>

        <div class="algo-card" data-stage="oll">
          <div class="top">
            <div class="net" id="net-oll-3"></div>
            <div><div class="algo-name">Cruz (OLL 21)</div><div class="algo-stage">OLL</div></div>
          </div>
          <div class="algo-notation">R U2 R2 U' R2 U' R2 U2 R</div>
        </div>

        <div class="algo-card" data-stage="pll">
          <div class="top">
            <div class="net" id="net-pll-1"></div>
            <div><div class="algo-name">T-Perm</div><div class="algo-stage">PLL</div></div>
          </div>
          <div class="algo-notation">R U R' U' R' F R2 U' R' U' R U R' F'</div>
        </div>

        <div class="algo-card" data-stage="pll">
          <div class="top">
            <div class="net" id="net-pll-2"></div>
            <div><div class="algo-name">U-Perm (a)</div><div class="algo-stage">PLL</div></div>
          </div>
          <div class="algo-notation">R U' R U R U R U' R' U' R2</div>
        </div>

        <div class="algo-card" data-stage="pll">
          <div class="top">
            <div class="net" id="net-pll-3"></div>
            <div><div class="algo-name">J-Perm (a)</div><div class="algo-stage">PLL</div></div>
          </div>
          <div class="algo-notation">R' U L' U2 R U' R' U2 R L</div>
        </div>

      </div>
    </div>

    <div class="flash-section">
      <div class="flashcard" id="flashcard" onclick="this.classList.toggle('flipped')">
        <div class="flashcard-inner">
          <div class="flash-face front">
            <div class="fq">Tu caso más débil</div>
            <div style="font-family:var(--font-display);font-weight:700;font-size:18px;">OLL 21 · Cruz</div>
            <div style="font-size:12px;color:var(--text-faint)">toca para ver la solución</div>
          </div>
          <div class="flash-face back">
            <div class="fq">Solución</div>
            <div class="algo-notation" style="font-size:12.5px">R U2 R2 U' R2 U' R2 U2 R</div>
          </div>
        </div>
      </div>
      <div style="max-width:340px;font-size:13px;color:var(--text-dim)">
        Repetición espaciada: este caso vuelve a aparecer porque fallaste 3 de tus últimas 5 repeticiones.
      </div>
    </div>
  </section>