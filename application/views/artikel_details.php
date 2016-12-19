<?php
$URL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<script src="<?php echo base_url() . APPPATH; ?>js/jquery.elevatezoom.js"></script>
<script>
    function changemainimg(img) {
        $("#mainimg").attr("src", img.src);
        var imgzoom = $(img).attr('data-zoom-image');
        $("#mainimg").attr("data-zoom-image", imgzoom);
        startZoom(true);
    }

    function checkMaat() {
        // Get the Login Name value and trim it
        var maat = $('#maat').val();
        // Check if empty of not
        if (maat === '0') {
            //alert("Gelieve eerst een maat te selecteren.");
            $("#maattekst").html("Gelieve eerst een maat te selecteren.");
            $("#maattekst").css("color", "red");
            event.preventDefault();
            return false;
        }
        $('#maatform').trigger('submit');
        return true;
    }
    //);

    $(document).ready(function () {
        startZoom();

        // reset the zoom when window is resized
        $(window).on('resize', function () {
            startZoom(true);
        });
    });

    function startZoom(refresh) {
        // Timeout needed to make sure all images and sources are loaded.
        var x = 1000;

        if (refresh) {
            $(".zoomContainer").remove();
            x = 1;
        }

        setTimeout(function () {
            $("#mainimg").elevateZoom({
                zoomType: "inner",
                cursor: "crosshair",
                zoomWindowFadeIn: 500,
                zoomWindowFadeOut: 750
            });
        }, x);
    }


</script>
<style>
    .bottomimagesContainer{
        width: 100%;
        background-color: transparent;
        padding: 5px;
        padding-left: 0px;
        height: 120px;
        overflow-x: scroll;
        overflow-y: hidden;
        //border-top: 2px solid black;
    }

    .bottomimages{
        //width: 9999px;
        display: inline-flex;        
        overflow-x: visible;
        height: 120px;

    }
    .bottomimages img {
        cursor: pointer;
        height: 110px;
        padding-right: 3px !important;
        /*margin: 0px !important;
        border-right: 1px solid black;*/
    }

    #social-media-buttons {        
        bottom: 0px;
        left:0px;
        width: 100%;
    }

    #social-media-buttons i{
        padding: 10px;
        font-size: 110%;
    }

    .clickable {
        cursor: pointer;
    }

</style>



<meta http-equiv="x-ua-compatible" content="IE=9" >
<div id="banner-wrapper" style='padding-bottom: 20px;'>
    <div id="" class="box container">        
        <?php if ($artikel != null) { ?>              
            <div class="row">                               
                <div class="7u">						
                    <!-- Box -->
                    <section class="box feature">
                        <?php if ($artikel->korting > 0) { ?>
                            <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $artikel->korting . "&#37;"; ?></div></div>
                        <?php } ?>
                        <div style="width: 100% !important; overflow: hidden;">
                            <?php
                            if ($artikel->largeImagePath != null) {
                                $zoomImg = base_url() . APPPATH . $artikel->largeImagePath;
                            } else {
                                $zoomImg = base_url() . APPPATH . $artikel->imagePath;
                            }

                            echo '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" class="image featured" id="mainimg" data-zoom-image="' . $zoomImg . '"/>';
                            ?>
                        </div>
                        <!-- test nieuwe fotos -->
                        <?php if (count($artikel->fotos) > 0) { ?>
                            <div class="bottomimagesContainer">
                                <div class="bottomimages">
                                    <?php echo '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" id="mainExtraFoto" alt="" onclick="changemainimg(this)" data-zoom-image="' . base_url() . APPPATH . $artikel->imagePath . '"/>'; ?>
                                    <?php
                                    foreach ($artikel->fotos as $foto) {
                                        if ($artikel->largeImagePath != null) {
                                            $zoomImg = base_url() . APPPATH . $artikel->largeImagePath;
                                        } else {
                                            $zoomImg = base_url() . APPPATH . $artikel->imagePath;
                                        }
                                        echo '<img src="' . base_url() . APPPATH . $foto->imagePath . '" id="extraFoto' . $foto->id . '" alt="" onclick="changemainimg(this)" data-zoom-image="' . $zoomImg . '"/>';
                                    }
                                    ?>

                                </div>
                            </div>
                        <?php } ?>
                    </section>
                </div>
                <div class="5u" style="position: relative;">						
                    <!-- Box -->
                    <section class="box feature" style="margin-bottom: 0px !important;">                        
                        <div class="inner autoheight">
                            <header>
                                <h2><?php echo $artikel->naam ?></h2>
                                <?php if ($artikel->korting == 0) { ?>
                                    <p>&euro;<?php echo number_format($artikel->prijs, 2) ?></p>
                                <?php } else { ?>
                                    <p><span style="text-decoration:line-through;">&euro;<?php echo number_format($artikel->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($artikel->prijs - ($artikel->prijs * $artikel->korting / 100)), 2) ?></p>
                                <?php } ?>
                            </header>                            
                            <div>                                
                                <?php
                                echo form_open('winkelmandje/voegtoemetmaat', "id='maatform'");
                                if (count($artikel->artikelMaten) > 0) {
                                    echo "<p id='maattekst'></p>";
                                    $options[0] = '-- Kies een maat --';
                                    foreach ($artikel->artikelMaten as $artikelMaat) {
                                        if ($artikelMaat->voorraad != null && $artikelMaat->voorraad != 0) {
                                            $options[$artikelMaat->maat->id] = $artikelMaat->maat->maat;
                                        }
                                    }
                                    echo form_dropdown('maat', $options, "", "id='maat'");
                                } else {
                                    echo "<b style='color:red;'>Uitverkocht!</b> ";
                                }
                                echo "<input type='hidden' name='artikelId' id='hdnId' value='" . $artikel->id . "'/>";
                                ?>                                
                            </div>
                        </div>
                        <input type="submit" value="Submit" style="display:none;" />
                        <?php
                        //echo form_hidden('artikelId', $artikel->id, "id='hdnId'");


                        if (count($artikel->artikelMaten > 0)) {
                            $js = 'onclick="checkMaat();" style="width: 100%"';
                            echo form_submit('submit', 'In winkelmandje', $js);
                        }
//echo anchor('winkelmandje/voegtoe/' . $artikel->id, 'Voeg toe aan winkelmandje', array('class' => 'button big icon fa-arrow-circle-right')); 
                        echo form_close();
                        ?>             

                    </section>
                    <div style="padding: 0px 10px; margin-top: 10px !important;" class="fb-share-button" data-href="<?php echo $URL; ?>" data-layout="icon_link"></div>
                    <!-- Show description here -->            
                    <div class="omschrijving" style="margin-top:2%;">
                        <h3>Omschrijving</h3>
                        <p style="max-height: 10000px !important;"><?php
                    echo $artikel->omschrijving;
                        ?>
                        </p>
                    </div>

                    <div id="social-media-buttons" class="12u">
                        <i class="fa fa-facebook clickable" style="color: #3a5897;" aria-hidden="true"></i>
                        <i class="fa fa-twitter clickable" style="color: #2fc7f2" aria-hidden="true"></i>
                        <i class="fa fa-instagram clickable" style="" aria-hidden="true"></i>
                        <i class="fa fa-google-plus clickable" style="color: #f95c38" aria-hidden="true"></i>
                        <i class="fa fa-pinterest-p clickable" style="color: #f01951" aria-hidden="true"></i>
                    </div>
                </div>
                <!--<div class="1u"></div>-->
            </div>  
        <?php } ?>        
    </div>