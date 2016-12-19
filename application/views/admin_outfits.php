<!-- Banner -->
<div id="banner-wrapper">
    <?php
    // message outfit bewerkt
    if (isset($bewerkt)) {
        echo "<div class='toegevoegd'>" . $bewerkt . "</div>";
    }
    ?>
    <div id="banner" class="box container">
        <div class="row">
            <div class="12u">
                <h2>Dulani webshop</h2>
                <p>Alle outfits zijn weergegeven</p>
            </div>            
        </div>
    </div>
</div>
<!-- Features -->
<div id="features-wrapper">
    <div class="container">
        <?php
        $counter = 0;
        $fullcounter = 0;
        ?>
        <?php foreach ($outfits as $outfit) { ?>  

            <?php if ($counter == 0) { ?> <div class="row"> <?php } ?>
                <div class="4u">						
                    <!-- Box -->
                    <section class="box feature"> 
                        <?php if ($outfit->korting > 0) { ?>
                            <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $outfit->korting . "&#37;"; ?></div></div>
                        <?php } ?>
                        <?php echo anchor('admin/bewerkoutfit/' . $outfit->id, '<img src="' . base_url() . APPPATH . $outfit->imagePath . '" alt="" />', array('class' => 'image featured shopimg')); ?>
                        <div class="inner">
                            <header>
                                <h2><?php echo $outfit->naam ?></h2>
                                
                            </header>
                            <div class="omschrijving">
                                <p>
                                    <?php
                                    if (strlen($outfit->omschrijving) < 75) {
                                        echo $outfit->omschrijving;
                                    } else {
                                        echo substr($outfit->omschrijving, 0, 62) . anchor('outfits/details/' . $outfit->id, '... (lees meer)', array('class' => 'leesmeer'));
                                    }
                                    ?>
                                </p>
                            </div>                            
                        </div>
                    </section>

                </div>						
                <?php if ($counter == 2 || count($outfits) == $fullcounter + 1) { ?></div> <?php } ?>
            <?php
            $counter++;
            $fullcounter++;
            if ($counter > 2) {
                $counter = 0;
            }
            ?>
        <?php } ?>
    </div>
</div>
