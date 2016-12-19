<div id="fulldiv">
    <script>
        var artikelIdGlobal = <?php echo $artikel->id; ?>;
        $(".closeControl").click(function (event) {
            event.preventDefault();
            $("#popup").fadeOut("slow", function () {
                $("#popup").hide();
                $("#page-wrapper").removeClass("blur");
                finishedCheckingArtikel(artikelIdGlobal);
            });

            
        });

        $(document).mouseup(function (e)
        {
            var container = $("#popup");
            if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0
                    && ($("#popup").html() != "")) // bug function would keep executing even after this ajax code has been cleared
            {
                $("#page-wrapper").removeClass("blur");
                $("#popup").hide();
                finishedCheckingArtikel(artikelIdGlobal);
            }
        });

        function changemainimgpopup(img) {
            $("#mainimgpopup").attr("src", img.src);
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
            popupaddToCart(artikelId, maat)
        }

        function popupaddToCart(artikelId, maat) {
            $.ajax({type: "GET",
                url: site_url + "/winkelmandje/addtocartajax",
                data: {artikelId: artikelId, maatId: maat},
                success: function (result) {
                    $("#popupaddtocartbutton" + artikelId).text("Toegevoegd!");
                    $("#addtocartbutton" + artikelId).text("Toegevoegd!");
                    
                    $("#addtocartbutton" + artikelId).removeClass("fa-shopping-cart");
                    $("#addtocartbutton" + artikelId).addClass("fa-check");
                    
                    $("#popupaddtocartbutton" + artikelId).removeClass("fa-shopping-cart");
                    $("#popupaddtocartbutton" + artikelId).addClass("fa-check");
                    $(".closeControl").click();                  

                }
            });
        }

    </script>

    <img src="<?php echo base_url() . APPPATH; ?>images/icons/close.png" width="70px" title="Sluiten" class="closebutton clickable closeControl" />


    <!-- Features -->
    <div id="features-wrapper">
        <div class="container">
            <?php if ($artikel != null) { ?>              
                <div class="row">
                    <!--<div class="2u">
                    <?php //echo '<div class="extraimg"><img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" class="" onclick="changemainimg(this)"/></div>'; ?>
                    <?php //foreach ($artikel->fotos as $foto) { ?>
                    <?php //echo '<div class="extraimg"><img src="' . base_url() . APPPATH . $foto->imagePath . '" alt="" onclick="changemainimg(this)"/></div>'; ?>
                    <?php //} ?>
                    </div>   -->              
                    <div class="6u">						
                        <!-- Box -->
                        <section class="box feature">
                            <?php if ($artikel->korting > 0) { ?>
                                <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $artikel->korting . "&#37;"; ?></div></div>
                            <?php } ?>
                            <?php echo '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" class="image featured" id="mainimgpopup" />'; ?>
                            <!-- test nieuwe fotos -->
                            <?php if (count($artikel->fotos) > 0) { ?>
                                <div class="bottomimagesContainer">
                                    <div class="bottomimages">
                                        <?php echo '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" onclick="changemainimgpopup(this)"/>'; ?>
                                        <?php foreach ($artikel->fotos as $foto) { ?>
                                            <?php echo '<img src="' . base_url() . APPPATH . $foto->imagePath . '" alt="" onclick="changemainimgpopup(this)"/>'; ?>                                      
                                        <?php } ?>

                                    </div>
                                </div>
                            <?php } ?>

                            <div class="inner" style="height: auto !important;">
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
                        <section class="box feature" style="margin-bottom: 0px !important; box-shadow: 0px 0px 40px -5px;">                        
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
                    </div>
                    <!--<div class="1u"></div>-->
                </div>  
            <?php } ?>
        </div>
    </div>


</div>