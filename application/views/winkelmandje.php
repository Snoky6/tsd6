<?php ?>
<?php
$counter = 0;
$fullcounter = 0;
?>
<script>
    function checkStock() {
        /*$.ajax({type: "GET",
         url: site_url + "/winkelmandje/checkStock",
         data: {},
         success: function (result) {
         alert(result);
         $("#banner-wrapper").html(result);
         return false;
         event.preventDefault();
         }
         });*/
    }
</script>

<style>
    .bigbanner
{
    padding: 4.5em;
    padding-top: 3.5em;
    padding-bottom: 0.5em;
}

.bigbanner h2
{
    font-size: 3.5em;
    margin: 0.1em 0 0.35em 0;
}

.bigbanner h2.bannerhead
{
    font-size: 2.75em;
    line-height: 1.35em;
    margin: 0;
    padding-bottom: 1em;
}

.winkelmandje-buttons li{
    margin-top: 1em;
}

.bottom-totaalprijs {
    margin-top: 1em; 
}
</style>
<!-- Banner -->
<div id="banner-wrapper">
    <div id="" class="box container bigbanner">
        <div class="row">
            <div class="8u">
                <div id="winkelmandje-header" class="">
                <!--<h2><?php echo global_bedrijfsnaam; ?> webshop</h2>-->
                <h2 class="bannerhead">Jouw winkelmandje!</h2>
                </div>
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
                                <div class="row" style="padding-top: 40px;">
                                    <div class="3u artikel-foto" style="padding-top: 0px;"><?php echo anchor('artikels/details/' . $karitem->artikel->id, '<img src="' . base_url() . APPPATH . $karitem->artikel->imagePath .'" width="100%"/>');?></div>
                                    <div class="9u artikel-omschrijving" style="padding-top: 0px;">
                                        <h4><?php echo $karitem->artikel->naam . " (" . $karitem->maat->maat . ")"; ?></h4>
                                        <p>
                                            Aantal: <?php echo $karitem->aantal; ?><br/>
                                            <?php if(!($karitem->artikel->korting > 0)) { ?>
                                            Prijs: <?php echo number_format($karitem->artikel->prijs, 2); ?><br/>
                                            <?php } else { ?>
                                            Prijs: <?php echo '<span style="text-decoration:line-through;">&euro;' . number_format($prijsZonderKorting,2) . '</span> &nbsp;&euro;' . number_format($karitem->artikel->prijs, 2); ?><br/>
                                            <?php } ?>
                                            <a href="<?php echo base_url() . 'index.php/winkelmandje/verwijder/' . $karitem->createid; ?>" onclick="" class="button fa fa-times clickable" style="font-size: 100%; padding: 0.5em 1.5em;">&nbsp; Verwijderen</a>
                                        </p>
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
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="4u winkelmandje-buttons">
                <ul>
                    <li><?php echo anchor('welcome/index', 'Verder winkelen', array('class' => 'button big icon fa-arrow-circle-right')); ?></li>
                    <?php if (count($karretje) != 0) { ?>                   
                        <li><?php echo anchor('winkelmandje/leeg', 'Maak leeg', array('class' => 'button alt big icon fa-arrow-circle-right')); ?></li>
                        <li><?php echo anchor('winkelmandje/index', 'Afrekenen', array('class' => 'button alt big icon fa-arrow-circle-right', 'onclick' => 'checkStock()')); ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
