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

          <?php
            $c = new Conexion();
            $c->conectar();

            $datos = $c->ejecutarCosulta("SELECT A.ID_Algoritmo AS id, A.Paso AS paso, A.Nombre AS nom, A.Imagen AS img, M.Combinacion AS scb, EXISTS (SELECT 1 FROM Usuario_Algoritmo UA WHERE UA.ID_Usuario = ? AND UA.ID_Algoritmo = A.ID_Algoritmo) AS sabido FROM Algoritmo A INNER JOIN Mezcla M ON A.ID_Algoritmo = M.ID_Algoritmo", [(int)$_SESSION["id_usuario"]]);
            $c->desconectar();

            foreach ($datos as $item) {
                $stage  = strtolower($item["paso"]);
                $clases = "algo-card" . ($stage === "f2l" ? " show" : "") . ($item["sabido"] ? " sabido" : "");
                $img    = "https://myrubik.com/" . ltrim($item["img"] ?? "", "/");
                $nombre = htmlspecialchars($item["nom"]);

                echo "<div class=\"" . $clases . "\" data-stage=\"" . htmlspecialchars($stage) . "\" data-algo=\"" . (int)$item["id"] . "\">
                        <div class=\"top\">
                          <div><div class=\"algo-name\">" . $nombre . "</div></div>
                          <img class=\"algo-img\" loading=\"lazy\" alt=\"" . $nombre . "\" src=\"" . htmlspecialchars($img) . "\">
                        </div>
                        <div class=\"algo-notation\">" . htmlspecialchars($item["scb"]) . "</div>
                        <button type=\"button\" class=\"btn btn-outline btn-small algo-sabo\" data-sabe=\"" . (int)$item["id"] . "\"><img class= \"icono\" src=\"imagenes/".($item["sabido"] ? "no" : "si") . ".png\">
                          </button>
                      </div>";
            }
          ?>

      </div>
    </div>
      <p style = "color:gray;">imagenes orginales de <a target="_blank" href="https://myrubik.com/">myrubik.com</a></p>
      <p style = "color:gray;">algoritmos orginales de <a target="_blank" href="https://myrubik.com/">myrubik.com</a>, de <a target="_blank" href="https://jperm.net/">jperm.net</a> y de <a target="_blank" href="https://kubekings.com">kubekings.com</a></p>
      <br><br>
  </section>
