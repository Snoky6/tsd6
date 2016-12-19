<!-- Banner -->
<div id="banner-wrapper">
    <div id="banner" class="box container">
        <div class="row">
            <div class="8u">
                <h2>Dulani webshop</h2>
                <p>Jouw winkelmandje!</p>                                                        
                <?php
                $totaalprijs = 0.00;
                if (count($karretje) == 0) {
                    echo "<div>Geen producten in winkelmandje!</div>";
                } else {
                    echo "<table><tr><th>Product</th><th>Prijs</th><th>Aantal</th><th>Totaal</th></tr>";
                    foreach ($karretje as $id => $aantal) {
                        $totaalprijs += ($aantal * $artikels[$id]->prijs);
                        echo "<tr><td>" . $artikels[$id]->naam . " (" . $karretje->maat . ")</td><td>&euro; " . str_replace('.', ',', number_format($artikels[$id]->prijs,2)) . "</td><td>" . $aantal . "</td><td>&euro; " . str_replace('.', ',', number_format(($aantal * $artikels[$id]->prijs),2)) . "</td><td>" . anchor('winkelmandje/verwijder/' . $id, 'Verwijderen') . "</td></tr>";
                    }
                    echo "</table><p>Totaalprijs: &euro; " . $totaalprijs . "</p>";
                }                
                ?>
            </div>
            <div class="4u">
                <ul>
                    <li><?php echo anchor('welcome/index', 'Verder winkelen', array('class' => 'button big icon fa-arrow-circle-right')); ?></li>
                    <li><?php echo anchor('winkelmandje/leeg', 'Maak leeg', array('class' => 'button alt big icon fa-arrow-circle-right')); ?></li>
                    <li><?php echo anchor('winkelmandje/betalen', 'Afrekenen', array('class' => 'button alt big icon fa-arrow-circle-right')); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Features -->
<div id="features-wrapper">
    <div class="container">
        <?php $counter = 0;
        $fullcounter = 0;
        ?>
        <?php foreach ($artikels as $artikel) { ?>  

    <?php if ($counter == 0) { ?> <div class="row"> <?php } ?>
                <div class="4u">						
                    <!-- Box -->
                    <section class="box feature">
    <?php echo anchor('artikels/details/' . $artikel->id, '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" />', array('class' => 'image featured shopimg')); ?>
                        <div class="inner">
                            <header>
                                <h2><?php echo $artikel->naam ?></h2>
                                <p>&euro;<?php echo number_format($artikel->prijs, 2) ?></p>
                            </header>
                            <p><?php echo $artikel->omschrijving ?></p>
                        </div>
                    </section>

                </div>						
            <?php if ($counter == 2 || count($artikels) == $fullcounter + 1) { ?></div> <?php } ?>
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

<!-- Main -->
<div id="main-wrapper">
    <div class="container">
        <div class="row">
            <div class="4u">

                <!-- Sidebar -->
                <div id="sidebar">
                    <section class="widget thumbnails">
                        <h3>Laatste kans!</h3>
                        <div class="grid">
                            <div class="row no-collapse 50%">
                                <div class="6u"><a href="#" class="image fit"><img src="<?php echo base_url() . APPPATH; ?>images/pic04.jpg" alt="" /></a></div>
                                <div class="6u"><a href="#" class="image fit"><img src="<?php echo base_url() . APPPATH; ?>images/pic05.jpg" alt="" /></a></div>
                            </div>
                            <div class="row no-collapse 50%">
                                <div class="6u"><a href="#" class="image fit"><img src="<?php echo base_url() . APPPATH; ?>images/pic06.jpg" alt="" /></a></div>
                                <div class="6u"><a href="#" class="image fit"><img src="<?php echo base_url() . APPPATH; ?>images/pic07.jpg" alt="" /></a></div>
                            </div>
                        </div>
                        <a href="#" class="button icon fa-file-text-o">Meer</a>
                    </section>
                </div>

            </div>
            <div class="8u important(collapse)">

                <!-- Content -->
                <div id="content">
                    <section class="last">
                        <h2>Ontvang korting door te delen</h2>
                        <p>Phasellus quam turpis, feugiat sit amet ornare in, hendrerit in lectus. Praesent semper bibendum ipsum, et tristique augue fringilla eu. Vivamus id risus vel dolor auctor euismod quis eget mi. Etiam eu ante risus. Aliquam erat volutpat. Aliquam luctus mattis lectus sit amet phasellus quam turpis.</p>
                        <a href="#" class="button icon fa-arrow-circle-right">Ga verder</a>
                    </section>
                </div>

            </div>
        </div>
    </div>
</div>
