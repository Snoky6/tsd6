<table class="stockchange">
    <?php
    $totaalprijs = 0.00;
    $totaalprijsWinkel = 0.00;
    $totaalprijsOnline = 0.00;
    foreach ($stockchanges as $stockchange) {
        /* calculate totalprice first */
        $prijs = $stockchange->prijs - ($stockchange->prijs * $stockchange->korting / 100);

        if ($stockchange->aantal > 0) {
            echo "<tr style='background-color: rgba(0,255,0,0.2);'>";
        } else {
            echo "<tr style='background-color: rgba(255,0,0,0.2);'>";           
        }
        echo "<td><i class='fa fa-picture-o' title='Klik hier om de artikelfoto te zien' onclick='showImage(\"" . $stockchange->artikel->imagePath . "\");' style='cursor: pointer;'></i> <label>" . anchor('admin/bewerkartikel/' . $stockchange->artikelId, $stockchange->artikel->barcode, 'target="_blank" title="' . $stockchange->artikel->naam . '"') . "</label></td>";
        echo "<td>Maat: " . $stockchange->artikel->maat->maat . "</td>";
        if ($stockchange->aantal > 0) {
            echo "<td title='Aantal toegevoegde voorraad (en huidige voorraad)' style='cursor: help;'><b style='color: green;'>+" . $stockchange->aantal . "</b> (" . $stockchange->artikel->artikelmaat->voorraad . ")</td>";
        } else {
            /* totaalprijs toevoegen want artikel is verkocht. Prijs vermenigvuldigen met aantal, maar aantal is negatief dus dat ook eerst maal -1 doen */
            $totaalprijs += $prijs * ($stockchange->aantal * -1);

            echo "<td title='Aantal verkochte voorraad (en huidige voorraad)' style='cursor: help;'><b style='color: red;'>" . $stockchange->aantal . "</b> (" . $stockchange->artikel->artikelmaat->voorraad . ")</td>";
        }

        if ($stockchange->bestellingId != NULL) {
            echo "<td title='Klik hier om naar de bestelling te gaan'>" . anchor('admin/bestelling/' . $stockchange->bestellingId, 'Online (' . $stockchange->bestellingId . ')', 'target="_blank"') . "</td>";
            $totaalprijsOnline += $prijs * ($stockchange->aantal * -1);
        } else {
            if ($stockchange->aantal > 0) {
                echo "<td>Winkel</td>";
            } else {
                echo "<td>Winkel</td>";
                $totaalprijsWinkel += $prijs * ($stockchange->aantal * -1);
            }
        }

        if ($stockchange->korting == 0) {
            echo "<td>&euro;" . number_format($prijs, 2) . "</td>";
        } else {

            echo "<td>&euro;" . number_format($prijs, 2) . " (-" . $stockchange->korting . "%)</td>";
        }

        echo "<td>" . $stockchange->datum . "</td>";

        echo "</tr>";
    }
    ?> 
</table>
<p style="font-size: 2em;">Totaalomzet van verkochte artikels: &euro;<?php echo number_format($totaalprijs, 2); ?></p>
<p style="font-size: 2em;">Totaalomzet van verkochte artikels (online): &euro;<?php echo number_format($totaalprijsOnline, 2); ?></p>
<p style="font-size: 2em;">Totaalomzet van verkochte artikels (winkel): &euro;<?php echo number_format($totaalprijsWinkel, 2); ?></p>