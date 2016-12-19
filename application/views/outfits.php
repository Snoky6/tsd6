<style>
    .shopimg {
        max-height: 999px !important;
    }
    
    .inner {
        max-height: 5em;
    }
</style>

<div id="banner-wrapper" style='padding-bottom: 20px;'>
    <div id="banner" class="box container">
        <div class="row" >
            <div class="12u">
                <h2>Dulani webshop</h2>
                <p><?php echo $pagina; ?></p>
            </div>

        </div>
    </div>
</div>
<div id="main-wrapper" style='background-color: #eee; padding-top: 0px;'>

    <!-- Features -->
    <div id="features-wrapper">
        <div class="container">
            <?php if (count($outfits) != 0) { ?>
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
                                <?php echo anchor('outfits/details/' . $outfit->id, '<img src="' . base_url() . APPPATH . $outfit->imagePath . '" alt="" />', array('class' => 'image featured shopimg')); ?>
                                <div class="inner">
                                    <header>
                                        <h2><?php echo $outfit->naam ?></h2>
                                        <!--<?php if ($outfit->korting == 0) { ?>
                                            <p>&euro;<?php echo number_format($outfit->prijs, 2) ?></p>
                                        <?php } else { ?>
                                            <p><span style="text-decoration:line-through;">&euro;<?php echo number_format($outfit->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($outfit->prijs - ($outfit->prijs * $outfit->korting / 100)), 2) ?></p>
                                        <?php } ?>-->
                                    </header>
                                    <!--<div class="omschrijving">
                                        <p>
                                            <?php
                                            if (strlen($outfit->omschrijving) < 75) {
                                                echo $outfit->omschrijving;
                                            } else {
                                                echo substr($outfit->omschrijving, 0, 62) . anchor('outfits/details/' . $outfit->id, '... (lees meer)', array('class' => 'leesmeer'));
                                            }
                                            ?>
                                        </p>
                                    </div>-->
                                    
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
                <?php
            } else {
                echo '<h3>Hier komen binnenkort onze outfits!</h3>';
            }
            ?>
        </div>
    </div>

    <!-- Main -->
    <div id="main-wrapper">
        <div class="container">
            <div class="row">
                <div class="4u">

                    <!-- Sidebar -->
                    <div id="sidebar">
                        <section class="widget thumbnails">
                            <h3>Onlangs bekeken</h3>
                            <div class="grid"> 
                                <?php
                                $counter = 0;
                                $fullcounter = 0;
                                ?>
                                <?php foreach ($onlangsbekeken as $outfit) { ?>  

                                    <?php if ($counter == 0) { ?> <div class="row no-collapse 50%"> <?php } ?>
                                        <div class="6u">
                                            <?php echo anchor('outfits/details/' . $outfit->id, '<img src="' . base_url() . APPPATH . $outfit->imagePath . '" alt="" />', array('class' => 'image fit')); ?>


                                        </div>						
                                        <?php if ($counter == 1 || count($onlangsbekeken) == $fullcounter + 1) { ?></div> <?php } ?>
                                    <?php
                                    $counter++;
                                    $fullcounter++;
                                    if ($counter > 1) {
                                        $counter = 0;
                                    }
                                    ?>
                                <?php
                                }
                                if (count($onlangsbekeken) == 0) {
                                    echo "</p>Geen onlangs bekeken outfits.</p>";
                                }
                                ?>                            

                            </div>                        
                        </section>
                    </div>

                </div>
                <div class="8u important(collapse)">

                    <!-- Content -->
                    <div id="content">
                        <section class="last">
                            <h2>Schrijf je in voor de nieuwsbrief</h2>
                            <p>Wil je graag op de hoogte gehouden worden van de laatste nieuwtjes bij Dulani? Schrijf je dan snel in voor onze nieuwsbrief!</p>
                            <?php echo anchor('nieuwsbrief/index', 'Ga verder', array('class' => 'button icon fa-arrow-circle-right')); ?>
                        </section>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
