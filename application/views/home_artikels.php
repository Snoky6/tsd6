<script>
    // dont load more if images are still loading (see home.php)
    $('#artikels img').load(function () {
        loadedimages++;
        //alert('done');
        if (loadedimages == <?php echo count($artikels) ?>) {
            allLoaded = true;
            loadedimages = 0;           
            
            $('.firstimage').each(function() {
                $(this).css('transform', 'scale(1)');
            });
        }
    });

    if (autosearchfor != "") {
        // gezocht op artikel, laatste loader wegdoen
        $(".loadingartikelsanimation").hide();
    }

    function showSmallCartPopup(id) {
        $.ajax({type: "GET",
            url: site_url + "/artikels/getMatenForCartPopup",
            data: {id: id},
            success: function (result) {
                $("#small-dialog").html(result);
                $(".popup-with-zoom-anim").click();
            }
        });

    }

    function shopimgHoverIn(artikelId, imgPath) {
        if ($("#artikel-foto-container-" + artikelId + " img").attr("src") !== "<?php echo base_url() . APPPATH; ?>" + imgPath && $("#artikel-foto-container-" + artikelId).css('opacity') == 1) {
            /* Fade to 0.001 because otherwise the heigth isn't inherited */
            $("#artikel-foto-container-" + artikelId + " img").fadeTo(0.001, "slow");
            $("#artikel-extra-foto-container-" + artikelId + " img").attr("src", "<?php echo base_url() . APPPATH; ?>" + imgPath);
            $("#artikel-extra-foto-container-" + artikelId).fadeIn("slow");
        }
        $("#bottom-cart-" + artikelId).css("height", "50px");
    }

    function shopimgHoverOut(artikelId) {
        $("#artikel-extra-foto-container-" + artikelId).fadeOut("slow");
        $("#artikel-foto-container-" + artikelId + " img").fadeTo(1, "slow");
        $("#bottom-cart-" + artikelId).css("height", "0px");
    }
</script>
<style>
    .homeSmallCart i {
        font-size: 130%;
        position: absolute;
        bottom: 15px;
        right: 15px;
        padding: 12px;
        width: 45px;
        height: 45px;
        color: #fff;
        /*background-color: rgba(230,29,128,0.8);*/
        background-color: rgba(0,0,0,0.1);
        box-shadow: 0 0 5px 1px rgba(0,0,0,0.15);
        transition: background-color .25s ease-in-out;        
        border-radius: 50%;
    }

    .homeSmallCart i:hover {        
        color: #fff;
        /*background-color: rgba(0,144,197,0.8);*/
        background-color: rgba(230,29,128,0.8);
        transition: background-color .25s ease-in-out;        
    }

    .clickable {
        cursor: pointer;
    }

    .bottom-cart {
        position: absolute;
        overflow: hidden;
        bottom: 0px;
        height: 0px;
        z-index: 1;
        width: 100%;
        text-align: center;        
        background-color: rgba(255,255,255,0.9);
        vertical-align: middle;        
        text-transform: uppercase;
        line-height: 50px;

        -webkit-transition: height 0.5s;
        -moz-transition: height 0.5s;
        transition: height 0.5s;
    }

    .bottom-cart span {
        color: #000;
    }
    
    .firstimage {
        transform: scale(0.1);
        transition: all 0.25s ease-in-out;
    }

</style>



<?php
$counter = 0;
$fullcounter = 0;

$rowclass = 4;
$homepagerows = 3;
if (isset($setting)) {
    $rowclass = (12 / $setting->homepagerows);
    $homepagerows = $setting->homepagerows;
} else {
    $rowclass = 4;
    $homepagerows = 3;
}
?>
<?php foreach ($artikels as $artikel) { ?>  
    <?php if ($counter == 0) { ?> <div id="loadedartikels" class="row"> <?php } ?>
        <div class="<?php echo $rowclass; ?>u">            
            <!-- Box -->
            <?php
            $vals = "";
            if (count($artikel->fotos) > 0) {
                $vals = $artikel->id . ',\'' . $artikel->fotos{0}->imagePath . '\'';
            } else {
                $vals = $artikel->id . ',\'' . $artikel->imagePath . '\'';
            }
            ?>
            <section class="box feature" onmouseenter="shopimgHoverIn(<?php echo $vals; ?>)" onmouseleave="shopimgHoverOut(<?php echo $artikel->id; ?>)">
                <div style="position: relative">
                    <?php if ($artikel->korting > 0) { ?>
                        <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $artikel->korting . "&#37;"; ?></div></div>
                    <?php } ?>
                    <?php
                    $js2 = "";
                    $js2 = 'onclick="showSmallCartPopup(' . $artikel->id . ')"';

                    echo '<div class="bottom-cart clickable" ' . $js2 . ' id="bottom-cart-' . $artikel->id . '"><span>Snel overzicht</span></div>';
                    ?>
                    <?php echo anchor('artikels/details/' . $artikel->id, '<div style="position:relative; min-height: 254px;"><div id="artikel-foto-container-' . $artikel->id . '" style="position: relative;"><img src="' . base_url() . APPPATH . $artikel->imagePath . '" class="firstimage" alt="" /></div><div id="artikel-extra-foto-container-' . $artikel->id . '" style="position: absolute; display:none; top: 0px; width: 100%;"><img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" /></div></div>', array('class' => 'image featured shopimg', 'style' => 'max-height: 4000px;', 'id' => 'artikel-foto' . $artikel->id)); ?>
                </div>
                <div class="inner">
                    <header>
                        <h2><?php echo $artikel->naam ?></h2>
                        <?php if ($artikel->korting == 0) { ?>
                            <p style="">&euro;<?php echo number_format($artikel->prijs, 2) ?></p>
                        <?php } else { ?>
                            <p style=""><span style="text-decoration:line-through;">&euro;<?php echo number_format($artikel->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($artikel->prijs - ($artikel->prijs * $artikel->korting / 100)), 2) ?></p>
                        <?php } ?>
                    </header>
                    <div class="omschrijving" style="display:none;">
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
                    <div class="bottommaten" style="position:relative;">
                        <p>
                            <?php
                            if (count($artikel->artikelMaten) > 0) {
                                echo "Verkrijgbaar in: ";
                                foreach ($artikel->artikelMaten as $artikelMaat) {
                                    if ($artikelMaat->voorraad != null && $artikelMaat->voorraad != 0) {
                                        echo "<b>" . $artikelMaat->maat->maat . "</b> ";
                                    }
                                }
                            } else {
                                echo "<b style='color:red;'>Uitverkocht!</b> ";
                            }
                            ?>
                        </p>
                    </div>
                    <!--<div class="homeSmallCart">
                        <i class="fa fa-shopping-cart clickable" aria-hidden="true" onclick="showSmallCartPopup(<?php echo $artikel->id; ?>)"></i>
                    </div>-->
                </div>
            </section>
        </div>						
        <?php if ($counter == $homepagerows - 1 || count($artikels) == $fullcounter + 1) { ?></div> <?php } ?>
        <?php
    $counter++;
    $fullcounter++;
    if ($counter > $homepagerows - 1) {
        $counter = 0;
    }
    ?>
<?php } ?>
<?php if (count($artikels) == 0) { ?>

<?php } else { ?>
    <div id="" class="loadingartikelsanimation"></div>
    <div id="loadartikels" class="loadingartikels" style="display: none;"></div>
<?php } ?>