<script>
    function changemainimg(img) {
        $("#mainimg").attr("src", img.src);
    }

    function checkMaat(id) {
        var artikelId = id;
        var maat = $('#maat' + id).val();
        // Check if empty of not
        if (maat === '0') {
            $("#maattekst" + id).html("Gelieve eerst een maat te selecteren.");
            $("#maattekst" + id).css("color", "red");
            event.preventDefault();
            return false;
        }
        //alert($('#maat').val() + " id:" + $('#hdnId').val());
        //$('#maatform').trigger('submit');
        //return true;
        addToCart(artikelId, maat)
    }

    function addToCart(artikelId, maat) {
        $.ajax({type: "GET",
            url: site_url + "/winkelmandje/addtocartajax",
            data: {artikelId: artikelId, maatId: maat},
            success: function (result) {                
                $("#addtocartbutton" + artikelId).text("Toegevoegd!");
                $("#maattekst" + artikelId).html("Artikel toegevoegd aan winkelmandje!");
                $("#maattekst" + artikelId).css("color", "black");
            }
        });
    }

    function showpopup(id) {
        $.ajax({type: "GET",
            url: site_url + "/artikels/detailspopup",
            data: {id: id},
            success: function (result) {
                $("#page-wrapper").addClass("blur");
                $("#popup").html(result);
                $("#popup").show();
            }
        });
    }

    function finishedCheckingArtikel(groepId) {
        $("#popup").html("");
    }
</script>

<style>
    .inner {
        height: auto !important;
    }
    .bottomimagesContainer{
        width: 100%;
        background-color: #eee;
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


    .hiddenPopup {
        display: none;
        position: fixed;
        padding: 70px;
        padding-left: 0px;
        z-index: 99;
        top: 5em;
        left: 10%;
        width: 80%;
        margin-left: auto;
        margin-right: auto;    
        border-radius: 10px;
        background-color: #f3f3f3;
        border: 1px solid rgba(0,0,0,0.2);
        box-shadow: 0px 0px 100px 0px rgba(0,0,0,0.5);
        text-align: center;
        max-height: 80%;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .blur {
        -webkit-filter: blur(15px);
        -moz-filter: blur(15px);
        -o-filter: blur(15px);
        -ms-filter: blur(15px);
        filter: blur(15px);
    }

    .closebutton {
        position: absolute;
        top: 0px;
        right: 0px;
        float:right;

        -webkit-animation:spin2 0.1s;
        -moz-animation:spin2 0.1s;
        animation:spin2 0.1s;
    }

    .closebutton:hover {
        -webkit-animation:spin 0.1s;
        -moz-animation:spin 0.1s;
        animation:spin 0.1s;   
    }

    @-moz-keyframes spin { 100% { -moz-transform: rotate(180deg); } }
    @-webkit-keyframes spin { 100% { -webkit-transform: rotate(180deg); } }
    @keyframes spin { 100% { -webkit-transform: rotate(180deg); transform:rotate(180deg); } }

    @-moz-keyframes spin2 { 100% { -moz-transform: rotate(-180deg); } }
    @-webkit-keyframes spin2 { 100% { -webkit-transform: rotate(-180deg); } }
    @keyframes spin2 { 100% { -webkit-transform: rotate(-180deg); transform:rotate(-180deg); } }

    .clickable {
        cursor: pointer;
    }
    
    .smallertext {
    font-size: 80% !important;
    padding: 0px;
    line-height: 10px !important;
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
            <div class="row">                
                <div class="6u">						
                    <!-- Box -->
                    <section class="box feature">
                        <?php if ($outfit->korting > 0) { ?>
                            <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $outfit->korting . "&#37;"; ?></div></div>
                        <?php } ?>
                        <img src="<?php echo base_url() . APPPATH . $outfit->imagePath ?>" class="image featured" alt="" id="mainimg" />
                        <!-- test nieuwe fotos -->
                        <?php if (count($outfit->fotos) > 0) { ?>
                            <div class="bottomimagesContainer">
                                <div class="bottomimages">
                                    <?php echo '<img src="' . base_url() . APPPATH . $outfit->imagePath . '" alt="" onclick="changemainimg(this)"/>'; ?>
                                    <?php foreach ($outfit->fotos as $foto) { ?>
                                        <?php echo '<img src="' . base_url() . APPPATH . $foto->imagePath . '" alt="" onclick="changemainimg(this)"/>'; ?>                                      
                                    <?php } ?>

                                </div>
                            </div>
                        <?php } ?>
                        <div class="inner">
                            <header>
                                <h2><?php echo $outfit->naam ?></h2>
                                <!--<?php if ($outfit->korting == 0) { ?>
                                                        <p>&euro;<?php echo number_format($outfit->prijs, 2) ?></p>
                                <?php } else { ?>
                                                        <p><span style="text-decoration:line-through;">&euro;<?php echo number_format($outfit->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($outfit->prijs - ($outfit->prijs * $outfit->korting / 100)), 2) ?></p>
                                <?php } ?>-->
                            </header>
                            <div class="omschrijving">
                                <p><?php
                                    echo $outfit->omschrijving;
                                    ?>
                                </p>
                            </div>                            
                        </div>
                    </section>
                </div>
                <?php
                $counter = 0;
                $fullcounter = 0;
                ?>
                <?php foreach ($outfit->artikels as $artikel) { ?>  

                    <?php if ($counter == 0) { ?>  <?php } ?>
                    <div class="6u pcOnly">						
                        <!-- Box -->
                        <section class="box feature" style="min-height: 210px;"> 
                            <?php if ($artikel->artikel->korting > 0) { ?>
                                <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $artikel->artikel->korting . "&#37;"; ?></div></div>
                            <?php } ?>
                            <div class="inner" style="height: auto;">
                                <div class="outfitOnderdeel">
                                    <img src="<?php echo base_url() . APPPATH . $artikel->artikel->imagePath; ?>" onclick="showpopup(<?php echo $artikel->artikel->id; ?>)"/>
                                </div>
                                <header>
                                    <?php echo '<h2 onClick="showpopup(' . $artikel->artikel->id . ')" class="clickable">' . $artikel->artikel->naam . '</h2>' ?>
                                    <?php if ($artikel->artikel->korting == 0) { ?>
                                        <p>&euro;<?php echo number_format($artikel->artikel->prijs, 2) ?></p>
                                    <?php } else { ?>
                                        <p><span style="text-decoration:line-through;">&euro;<?php echo number_format($artikel->artikel->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100)), 2) ?></p>
                                    <?php } ?>
                                        <?php echo '<p onClick="showpopup(' . $artikel->artikel->id . ')" class="clickable smallertext">Klik voor meer info</p>' ?>
                                </header>
                                <!--<div class="omschrijving">
                                    <p>
                                        <?php
                                        if (strlen($artikel->artikel->omschrijving) < 75) {
                                            echo $artikel->artikel->omschrijving;
                                        } else {
                                            echo substr($artikel->artikel->omschrijving, 0, 62) . anchor('artikels/details/' . $artikel->artikel->id, '... (lees meer)', array('class' => 'leesmeer'));
                                        }
                                        ?>
                                    </p>
                                </div>-->
                                <!--<div class="bottommaten">
                                    <p>
                                        <?php                                        
                                        $counter = 0;
                                        if (count($artikel->artikel->artikelMaten) > 0) {
                                            echo "Verkrijgbaar in: ";
                                            foreach ($artikel->artikel->artikelMaten as $artikel->artikelMaat) {
                                                if ($artikel->artikelMaat->voorraad != null && $artikel->artikelMaat->voorraad != 0) {
                                                    echo "<b>" . $artikel->artikelMaat->maat->maat . "</b> ";
                                                    $counter++;
                                                }
                                            }
                                        }
                                        if ($counter == 0) {
                                            echo "<b style='color:red;'>Uitverkocht!</b> ";
                                        }
                                        ?>
                                    </p>
                                </div>-->
                                <!-- bestellen div -->
                                <div class="bottommaten">
                                    <?php
                                        $matentekst = "";
                                        $counter = 0;
                                        if (count($artikel->artikel->artikelMaten) > 0) {
                                            //$matentekst = "Verkrijgbaar in: ";
                                            foreach ($artikel->artikel->artikelMaten as $artikel->artikelMaat) {
                                                if ($artikel->artikelMaat->voorraad != null && $artikel->artikelMaat->voorraad != 0) {
                                                    $matentekst .= "<b>" . $artikel->artikelMaat->maat->maat . "</b> ";
                                                    $counter++;
                                                }
                                            }
                                        }
                                        if ($counter == 0) {
                                            //echo "<b style='color:red;'>Uitverkocht!</b> ";
                                        }
                                        ?>
                                    <?php
                                    echo form_open('winkelmandje/voegtoemetmaat', "id='maatform" . $artikel->artikel->id . "'");
                                    if (count($artikel->artikel->artikelMaten) > 0) {
                                        echo "<p id='maattekst" . $artikel->artikel->id . "'>Kies een maat ( " . $matentekst . "): </p>";
                                        $options[0] = '-- Selecteer --';
                                        foreach ($artikel->artikel->artikelMaten as $artikelMaat) {
                                            if ($artikelMaat->voorraad != null && $artikelMaat->voorraad != 0) {
                                                $options[$artikelMaat->maat->id] = $artikelMaat->maat->maat;
                                            }
                                        }
                                        echo form_dropdown('maat', $options, "", "id='maat" . $artikel->artikel->id . "' style='width: 63%'");
                                    } else {
                                        echo "<b style='color:red;'>Uitverkocht!</b> ";
                                    }
                                    echo "<input type='hidden' name='artikelId' id='hdnId' value='" . $artikel->artikel->id . "'/>";
                                    ?>  
                                </div>
                                <!--<div style="clear: both;">                                
                                    <?php
                                    echo form_open('winkelmandje/voegtoemetmaat', "id='maatform" . $artikel->artikel->id . "'");
                                    if (count($artikel->artikel->artikelMaten) > 0) {
                                        echo "<p id='maattekst" . $artikel->artikel->id . "'>Kies een maat: </p>";
                                        $options[0] = '-- Selecteer --';
                                        foreach ($artikel->artikel->artikelMaten as $artikelMaat) {
                                            if ($artikelMaat->voorraad != null && $artikelMaat->voorraad != 0) {
                                                $options[$artikelMaat->maat->id] = $artikelMaat->maat->maat;
                                            }
                                        }
                                        echo form_dropdown('maat', $options, "", "id='maat" . $artikel->artikel->id . "'");
                                    } else {
                                        //echo "<b style='color:red;'>Uitverkocht!</b> ";
                                    }
                                    echo "<input type='hidden' name='artikelId' id='hdnId' value='" . $artikel->artikel->id . "'/>";
                                    ?>                                
                                </div>   --> 

                            </div>
                            <?php
                            //if (count($artikel->artikel->artikelMaten > 0)) {
                            if ($counter > 0) {
                                //$js = 'onclick="checkMaat(' . $artikel->artikel->id . ')" style="width: 100%"';
                                echo '<a href="javascript:void(0)" id="addtocartbutton' . $artikel->artikel->id . '" onclick="checkMaat(' . $artikel->artikel->id . ')" style="width: 100%; text-align: center;" class="button icon fa-shopping-cart">In winkelmandje</a>';
                                //echo form_submit('submit', 'Voeg toe aan winkelmandje', $js);
                            }

                            echo form_close();
                            ?>

                        </section>

                    </div>



                    <!-- mobile -->
                    <div class="6u phoneOnly">						
                        <!-- Box -->
                        <section class="box feature"> 
                            <?php if ($artikel->artikel->korting > 0) { ?>
                                <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $artikel->artikel->korting . "&#37;"; ?></div></div>
                            <?php } ?>
                            <?php echo anchor('artikels/details/' . $artikel->artikel->id, '<img src="' . base_url() . APPPATH . $artikel->artikel->imagePath . '" alt="" />', array('class' => 'image featured shopimg')); ?>
                            <div class="inner" style="height: auto;">                                
                                <header>
                                    <h2><?php echo anchor('artikels/details/' . $artikel->artikel->id, $artikel->artikel->naam) ?></h2>
                                    <?php if ($artikel->artikel->korting == 0) { ?>
                                        <p>&euro;<?php echo number_format($artikel->artikel->prijs, 2) ?></p>
                                    <?php } else { ?>
                                        <p><span style="text-decoration:line-through;">&euro;<?php echo number_format($artikel->artikel->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($artikel->artikel->prijs - ($artikel->artikel->prijs * $artikel->artikel->korting / 100)), 2) ?></p>
                                    <?php } ?>                                        
                                </header>                                
                                <div class="bottommaten">
                                    <p>
                                        <?php
                                        $counter = 0;
                                        if (count($artikel->artikel->artikelMaten) > 0) {
                                            echo "Verkrijgbaar in: ";
                                            foreach ($artikel->artikel->artikelMaten as $artikel->artikelMaat) {
                                                if ($artikel->artikelMaat->voorraad != null && $artikel->artikelMaat->voorraad != 0) {
                                                    echo "<b>" . $artikel->artikelMaat->maat->maat . "</b> ";
                                                    $counter++;
                                                }
                                            }
                                        }
                                        if ($counter == 0) {
                                            echo "<b style='color:red;'>Uitverkocht!</b> ";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <!-- bestellen div -->
                                <div style="clear: both;">                                
                                    <?php
                                    echo form_open('winkelmandje/voegtoemetmaat', "id='maatform'");
                                    if (count($artikel->artikel->artikelMaten) > 0) {
                                        //echo "<p id='maattekst'>Kies een maat: </p>";
                                        $options[0] = '-- Selecteer --';
                                        foreach ($artikel->artikel->artikelMaten as $artikelMaat) {
                                            if ($artikelMaat->voorraad != null && $artikelMaat->voorraad != 0) {
                                                $options[$artikelMaat->maat->id] = $artikelMaat->maat->maat;
                                            }
                                        }
                                        echo form_dropdown('maat', $options, "", "id='maat'");
                                    } else {
                                        //echo "<b style='color:red;'>Uitverkocht!</b> ";
                                    }
                                    echo "<input type='hidden' name='artikelId' id='hdnId' value='" . $artikel->artikel->id . "'/>";
                                    ?>                                
                                </div>

                            </div>
                            <?php
                            //if (count($artikel->artikel->artikelMaten > 0)) {
                            if ($counter > 0) {
                                $js = 'onclick="checkMaat(' . $artikel->artikel->id . ')" style="width: 100%"';
                                echo form_submit('submit', 'Voeg toe aan winkelmandje', $js);
                            }

                            echo form_close();
                            ?>

                        </section>

                    </div>

                <?php } ?>

            </div>                
        </div>
    </div>


</div>
