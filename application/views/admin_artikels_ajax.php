<?php
$counter = 0;
$fullcounter = 0;
?>
<?php foreach ($artikels as $artikel) { ?>  

    <?php if ($counter == 0) { ?> <div class="row"> <?php } ?>
        <div class="3u">						
            <!-- Box -->
            <section class="box feature"> 
                <?php if ($artikel->korting > 0) { ?>
                    <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $artikel->korting . "&#37;"; ?></div></div>
                <?php } ?>
                <?php echo anchor('admin/bewerkartikel/' . $artikel->id, '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" />', array('class' => 'image featured shopimg')); ?>
                <div class="inner">
                    <header>
                        <h2><?php echo $artikel->naam ?></h2>
                        <?php if ($artikel->korting == 0) { ?>
                            <p>&euro;<?php echo number_format($artikel->prijs, 2) ?></p>
                        <?php } else { ?>
                            <p><span style="text-decoration:line-through;">&euro;<?php echo number_format($artikel->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($artikel->prijs - ($artikel->prijs * $artikel->korting / 100)), 2) ?></p>
                        <?php } ?>
                    </header>
                    <div class="omschrijving">
                        <p>
                            <?php
                            if (strlen($artikel->omschrijving) < 75) {
                                echo $artikel->omschrijving;
                            } else {
                                echo substr($artikel->omschrijving, 0, 62) . anchor('artikels/details/' . $artikel->id, '... (lees meer)', array('class' => 'leesmeer'));
                            }
                            ?>
                        </p>
                    </div>
                    <div class="bottommaten">
                        <p>
                            <?php
                            $maatcounter = 0;
                            if (count($artikel->artikelMaten) > 0) {
                                echo "Verkrijgbaar in: ";
                                foreach ($artikel->artikelMaten as $artikelMaat) {
                                    if ($artikelMaat->voorraad != null && $artikelMaat->voorraad != 0) {
                                        echo "<b>" . $artikelMaat->maat->maat . "</b> ";
                                        $maatcounter++;
                                    }
                                }
                            }
                            if ($maatcounter == 0) {
                                echo "<b style='color:red;'>Uitverkocht!</b> ";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </section>
        </div>						
        <?php if ($counter == 3 || count($artikels) == $fullcounter + 1) { ?></div> <?php } ?>
    <?php
    $counter++;
    $fullcounter++;
    if ($counter > 3) {
        $counter = 0;
    }
    ?>
<?php } ?>