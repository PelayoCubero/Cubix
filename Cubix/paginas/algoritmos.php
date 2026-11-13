<section class="view" id="algos">
    <div class="view-header">
      <h1>Algoritmos</h1>
      <h4 style = "color:gray;">imagenes orginales de <a href="https://myrubik.com/">myrubik.com</a></h4>
    </div>
    
    <div id="cfopPanel">
      <div class="stage-tabs">
        <button class="tab stage active" data-stage="f2l">F2L</button>
        <button class="tab stage" data-stage="oll">OLL</button>
        <button class="tab stage" data-stage="pll">PLL</button>
      </div>

      <div class="algo-grid" id="algoGrid">

          <?php

            $sql = "SELECT algoritmo.Paso as paso, algoritmo.nombre as nom, algoritmo.imagen as img, mezcla.Combinacion as scb FROM `algoritmo` inner join mezcla on algoritmo.ID_Algoritmo = mezcla.ID_Algoritmo";
            $c = new Conexion();
            $c->conectar();

            $datos = $c->ejecutarCosulta($sql);
          
          foreach ($datos as $item) {
            $stage = strtolower($item["paso"]);
            $show  = ($stage === "f2l") ? " show" : "";

            echo "<div class=\"algo-card$show\" data-stage=\"" . htmlspecialchars($stage) . "\">
            <div class=\"top\">
                  <div>
                    <div class=\"algo-name\">" . htmlspecialchars($item["nom"]) . "</div>
                    <div class=\"algo-stage\">" . htmlspecialchars($item["paso"]) . "</div>
                  </div>
                      <img class=\"algo-img\" src=\"https://myrubik.com/" . htmlspecialchars($item["img"] ?? "") . "\">
                    </div>
                    <div class=\"algo-notation\">" . htmlspecialchars($item["scb"]) . "</div>
                  </div>";
  }

            $c->desconectar();


          ?>



      </div>
    </div>
  </section>
  