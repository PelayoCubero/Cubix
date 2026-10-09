<section class="view" id="algos">
    <div class="view-header">
      <h1>Algoritmos</h1>
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
  </section>
  