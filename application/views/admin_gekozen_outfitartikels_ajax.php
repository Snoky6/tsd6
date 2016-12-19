<?php
$counter = 0;
$fullcounter = 0;
?>
<?php foreach ($artikels as $outfitartikel) { ?> 

<?php $artikel = $outfitartikel->artikel; // why? omdat een artikel in een outfitartikel zit. ?>

    <?php if ($counter == 0) { ?> <div class="row"> <?php } ?>
        <div class="4u" id="gekozenOutfitArtikel<?php echo $outfitartikel->id; ?>">						
            <!-- Box -->
            <section class="box feature"> 
                <?php if ($artikel->korting > 0) { ?>
                    <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $artikel->korting . "&#37;"; ?></div></div>
                <?php } ?>
                <?php echo '<a href="javascript:void(0)" title="verwijderen" class="image featured shopimg" onClick="verwijderArtikelVanOutfit(' . $outfitartikel->id . ')"><img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" /></a>'; ?>
                <div class="inner" style="height: auto !important; background-color: rgba(103,125,82,0.09)">
                    <header>
                        <h3><?php echo $artikel->naam ?></h3>
                        <div style="font-size: 60% !important;">
                        <?php if ($artikel->korting == 0) { ?>
                            <p>&euro;<?php echo number_format($artikel->prijs, 2) ?></p>
                        <?php } else { ?>
                            <p><span style="text-decoration:line-through;">&euro;<?php echo number_format($artikel->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($artikel->prijs - ($artikel->prijs * $artikel->korting / 100)), 2) ?></p>
                        <?php } ?>
                        </div>
                    </header>                    
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
<br/>

<?php if(count($artikels) == 0) {
    echo "<p>Nog geen artikels aan de outfit toegevoegd!</p><br/>";
} ?>