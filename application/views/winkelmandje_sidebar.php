<?php
$counter = 0;
$fullcounter = 0;
$totaalprijs = 0.00;
$incl = FALSE; // inclusief transport
?>
<div class="row">
    <div class="12u">        
        <?php
        if (count($karretje) == 0) {
            echo "<div>Geen producten in winkelmandje!</div>";
        } else {
            foreach ($karretje as $karitem) {
                if ($karitem->artikel->korting > 0) {
                    $prijsZonderKorting = $karitem->artikel->prijs;
                    $karitem->artikel->prijs = ($karitem->artikel->prijs - ($karitem->artikel->prijs * $karitem->artikel->korting / 100));
                }
                $totaalprijs += ($karitem->aantal * $karitem->artikel->prijs);
                ?>
                <div class="row">
                    <div class="4u artikel-foto"><img src="<?php echo base_url() . APPPATH . $karitem->artikel->imagePath; ?>" width="100%"/></div>
                    <div class="8u artikel-omschrijving">
                        <h4><?php echo $karitem->artikel->naam . " (" . $karitem->maat->maat . ")"; ?></h4>
                        <p>Aantal: <?php echo $karitem->aantal; ?><br/>
                            Prijs: <?php echo number_format($karitem->artikel->prijs, 2); ?></p>
                    </div>
                </div>
                <?php
                // PRIJS TERUG ZETTEN
                if ($karitem->artikel->korting > 0) {
                    $karitem->artikel->prijs = $prijsZonderKorting;
                }
            }
            $incl = FALSE; // inclusief transport
            if ($totaalprijs < $taxvrijlimiet) {
                $totaalprijs+=$transportkost;
                $incl = TRUE;
            }
            ?>                                      
            <?php
            echo '<div class="12u bottom-totaalprijs" > ';
            echo "<p style='font-size: 165%; font-weight: 700; margin-bottom: 0px;'>Totaalprijs: &euro; " . number_format($totaalprijs, 2) . "</p>";
            if ($incl) {
                echo "<p class='smalltext'>Inclusief verzendingskosten: &euro; " . $transportkost . " (vanaf &euro;" . number_format($taxvrijlimiet, 2) . " gratis verzonden in België!)</p>";
            } else {
                echo "<p class='smalltext'>Inclusief gratis verzending</p>";
            }
            echo "</div>";
            
            // Set buttons to go to actual cart or close
            echo '<div class="12u">';
            echo anchor('winkelmandje/index', 'Winkelwagen bekijken', array('class' => 'button icon fa-shopping-cart', 'style' => 'width: 100%; text-align: center;'));
            echo '</div>';
        }
        ?>
    </div>
</div>