<script>
    function changemainimg(img) {
        $("#mainimg").attr("src", img.src);
    }

    function popupcheckMaat(id) {
        var artikelId = id;
        var maat = $('#popupmaat').val();
        // Check if empty of not
        if (maat === '0') {
            $("#popupmaattekst").html("Gelieve eerst een maat te selecteren.");
            $("#popupmaattekst").css("color", "red");
            event.preventDefault();
            return false;
        }
        popupaddToCartAndShowSidebar(artikelId, maat)
    }

    function popupaddToCart(artikelId, maat) {
        $.ajax({type: "GET",
            url: site_url + "/winkelmandje/addtocartajax",
            data: {artikelId: artikelId, maatId: maat},
            success: function (result) {
                $("#small-dialog").html("<b>Artikel toegevoegd aan winkelmandje!</b><button title='Close (Esc)' type='button' class='mfp-close'>×</button>");
                Element.prototype.documentOffsetTop = function () {
                    return this.offsetTop + (this.offsetParent ? this.offsetParent.documentOffsetTop() : 0);
                };

                var top = document.getElementById('small-dialog').documentOffsetTop() - (window.innerHeight / 2);
                window.scrollTo(0, top);                

                setTimeout(function () {
                    if ($("#small-dialog").is(":visible")) {
                        /* POPUP is al open */
                        $(".mfp-close").click();
                    }
                }, 1500);

            }
        });
    }
    
    function popupaddToCartAndShowSidebar(artikelId, maat) {
        $.ajax({type: "GET",
            url: site_url + "/winkelmandje/addtocartajax",
            data: {artikelId: artikelId, maatId: maat},
            success: function (result) {
                /* function in home to open sidecart */
                $(".mfp-close").click();
                openOverlayCart(result);
            }
        });
    }
</script>
<style>
    .bottomimagesContainer{
        width: 100%;
        //background-color: #eee;
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

</style>
        
    <?php if ($artikel != null) { ?>              

        <!--<div class="2u">
        <?php //echo '<div class="extraimg"><img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" class="" onclick="changemainimg(this)"/></div>'; ?>
        <?php //foreach ($artikel->fotos as $foto) { ?>
        <?php //echo '<div class="extraimg"><img src="' . base_url() . APPPATH . $foto->imagePath . '" alt="" onclick="changemainimg(this)"/></div>'; ?>
        <?php //} ?>
        </div>   -->
        <div class="row">
            <div class="6u">						
                <!-- Box -->
                <section class="box feature" style="box-shadow: 0 0 0 0 white !important;">
                    <?php if ($artikel->korting > 0) { ?>
                        <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $artikel->korting . "&#37;"; ?></div></div>
                    <?php } ?>
                    <?php echo '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" class="image featured" id="mainimg" />'; ?>
                    <!-- test nieuwe fotos -->
                    <?php if (count($artikel->fotos) > 0) { ?>
                        <div class="bottomimagesContainer">
                            <div class="bottomimages">
                                <?php echo '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" onclick="changemainimg(this)"/>'; ?>
                                <?php foreach ($artikel->fotos as $foto) { ?>
                                    <?php echo '<img src="' . base_url() . APPPATH . $foto->imagePath . '" alt="" onclick="changemainimg(this)"/>'; ?>                                      
                                <?php } ?>

                            </div>
                        </div>
                    <?php } ?>

                    <div class="inner" style="height: auto !important; display: none;">
                        <header>
                            <h2><?php echo $artikel->naam ?></h2>
                            <?php if ($artikel->korting == 0) { ?>
                                <p>&euro;<?php echo number_format($artikel->prijs, 2) ?></p>
                            <?php } else { ?>
                                <p><span style="text-decoration:line-through;">&euro;<?php echo number_format($artikel->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($artikel->prijs - ($artikel->prijs * $artikel->korting / 100)), 2) ?></p>
                            <?php } ?>
                        </header>
                        <div class="omschrijving">
                            <p style="max-height: 10000px !important;"><?php
                                echo $artikel->omschrijving;
                                ?>
                            </p>
                        </div>
                        <div class="bottommaten">
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
                    </div>
                </section>
            </div>
            <div class="6u">						
                <!-- Box -->
                <section class="box feature" style="margin-bottom: 0px !important; box-shadow: 0 0 0 0 black;">                        
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
                                echo "<p id='popupmaattekst'>Kies een maat: </p>";
                                $options[0] = '-- Selecteer --';
                                foreach ($artikel->artikelMaten as $artikelMaat) {
                                    if ($artikelMaat->voorraad != null && $artikelMaat->voorraad != 0) {
                                        $options[$artikelMaat->maat->id] = $artikelMaat->maat->maat;
                                    }
                                }
                                echo form_dropdown('maat', $options, "", "id='popupmaat'");
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


                    if (count($artikel->artikelMaten) > 0) {
                        //$js = 'onclick="checkMaat();" style="width: 100%"';
                        //echo form_submit('submit', 'In winkelmandje', $js);
                        echo '<a href="javascript:void(0)" id="popupaddtocartbutton' . $artikel->id . '" onclick="popupcheckMaat(' . $artikel->id . ')" style="width: 100%; text-align: center;" class="button icon fa-shopping-cart">In winkelmandje</a>';
                    }
//echo anchor('winkelmandje/voegtoe/' . $artikel->id, 'Voeg toe aan winkelmandje', array('class' => 'button big icon fa-arrow-circle-right')); 
                    echo form_close();
                    ?>             

                </section> 

                <!-- Show description here for popup -->            
                <div class="omschrijving" style="margin-top:2%;">
                    <h3>Omschrijving</h3>
                    <p style="max-height: 10000px !important;"><?php
                        echo $artikel->omschrijving;
                        ?>
                    </p>
                </div>
            </div>
        </div>
    <?php } ?>

