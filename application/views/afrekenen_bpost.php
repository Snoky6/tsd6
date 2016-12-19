<?php
$counter = 0;
$fullcounter = 0;
?>
<script>
    var bestellingid = 0; //wordt meegegeven in ajax

    $(document).ready(function () {
        setTimeout(function () {
            scrollToGegevens();

            $("#email").keydown(function () {
                $("#email").removeClass('wrongInput');
            });
        }, 500);


        //loadShm();
    });

    function scrollToGegevens() {
        /*$('#gegevens').scrollView();*/
        $('html, body').animate({
            scrollTop: $("#gegevens").offset().top
        }, 2000);
    }


    function voorwaarden() {
        if (!($('voorwaarden').checked())) {
            //return false;
        }
    }

    function handlePaypalPay() {
        // check of alles ingevult is wat required is        
        var isValid = true;
        $('.form-fieldinput').each(function () {
            if ($(this).val() === '')
                isValid = false;
        });
        if (isValid) {
            $("#paypalpay").click();
        } else {
            //alert("U heeft niet alle velden ingevuld.")
        }
    }

    function checkPrijsLandChange(drop) {
        if ($("#landen").val() !== "BE") {
            var totaal = parseFloat($("#hiddenSubPrijs").val());
            if (totaal < <?php echo $taxvrijlimiet; ?>) {
                //totaal += 8;
                $("#prijsland").html("Let op: Het subtotaal bedraagt &euro; " + totaal + ", <br>pas gratis levering vanaf &euro;<?php echo $taxvrijlimiet; ?>!");
                $("#totaalPrijs").html("Totaalprijs: &euro; <label id='totaalPrijsNumber'>" + ((totaal + <?php echo $transportkost; ?>).toFixed(2)) + "</label>");
                $("#leveringTekst").html("Inclusief verzendingskosten: &euro; <?php echo $transportkost; ?> (vanaf &euro;<?php echo $taxvrijlimiet; ?> gratis verzonden in de buurlanden!)");
            } else {
                $("#prijsland").html("Gratis levering!");
                $("#totaalPrijs").html("Totaalprijs: &euro; <label id='totaalPrijsNumber'>" + ((totaal).toFixed(2)) + "</label>");
                $("#leveringTekst").html("Inclusief gratis verzending");
            }
        } else {
            var totaal = parseFloat($("#hiddenSubPrijs").val());
            if (parseFloat($("#hiddenSubPrijs").val()) < <?php echo $taxvrijlimiet; ?>) {
                $("#prijsland").html("Let op: pas gratis levering vanaf &euro;<?php echo $taxvrijlimiet; ?>!");
                $("#totaalPrijs").html("Totaalprijs: &euro; <label id='totaalPrijsNumber'>" + ((totaal + <?php echo $transportkost; ?>).toFixed(2)) + "</label>");
                $("#leveringTekst").html("Inclusief verzendingskosten: &euro; <?php echo $transportkost; ?> (vanaf &euro;<?php echo $taxvrijlimiet; ?> gratis verzonden in België!)");
            } else {
                $("#prijsland").html("Gratis levering!");
                $("#totaalPrijs").html("Totaalprijs: &euro; <label id='totaalPrijsNumber'>" + ((totaal).toFixed(2)) + "</label>");
                $("#leveringTekst").html("Inclusief gratis verzending");
            }
        }
    }

    function showLeverOpties() {
        $(".leverAdres").show();
        $("#klikLever").html("Geef hieronder het leveradres op");
    }

    function checkIfCodeExists() {
        var kortingcode = $("#kortingcode").val();
        $.ajax({type: "GET",
            url: site_url + "/admin/checkkortingcode",
            data: {kortingcode: kortingcode},
            success: function (result) {
                var resultaat = result;
                if (result != "false") {
                    $("#kortingcode").css('border-color', 'green');
                    $("#kortingcode").css('color', 'green');
                    if (result.indexOf("%") > 0) {
                        //$("#kortingTekst").html(result.toString()); 
                        // korting bij totaalprijs %
                        addKortingProcent(result);
                    } else {
                        if (result == "Deze code is niet meer geldig!") {
                            $("#kortingTekst").html(result.toString());
                            $("#kortingTekstTd").show();
                            $("#kortingcode").css('border-color', 'red');
                            $("#kortingcode").css('color', 'red');
                        } else {
                            $("#kortingTekst").html(result.toString());
                            $("#kortingTekstTd").show();
                            addKortingFixed(result);
                        }
                    }
                    // doe functie die prijs aanpast

                } else {
                    $("#kortingcode").css('border-color', 'red');
                    $("#kortingcode").css('color', 'red');
                    $("#kortingTekst").html("");
                }
            }
        });
    }

    function addKortingProcent(kortingString) {
        var totaalPrijsVoorKorting = $("#totaalPrijsNumber").html();
        var kortingPercentage = kortingString.substring(0, kortingString.length - 1);
        var totaalPrijsNaKorting = totaalPrijsVoorKorting - (totaalPrijsVoorKorting * kortingPercentage / 100);

        $("#kortingTekst").html("Totaalprijs met kortingscode: &euro;" + totaalPrijsNaKorting.toFixed(2));
        $("#kortingTekstTd").show();
    }

    function addKortingFixed(kortingString) {
        var totaalPrijsVoorKorting = $("#totaalPrijsNumber").html();
        var kortingFixed = kortingString.substring(0, kortingString.length - 5);
        var totaalPrijsNaKorting = totaalPrijsVoorKorting - kortingFixed;

        if (totaalPrijsNaKorting < 0) {
            totaalPrijsNaKorting = 0;
        }

        $("#kortingTekst").html("Totaalprijs met kortingscode: &euro;" + totaalPrijsNaKorting.toFixed(2));
        $("#kortingTekstTd").show();
    }

    $(document).keypress("enter", function (e) {
        if (e.ctrlKey)
            $("#molliebetalen").click();
    });

    function startbpostbestelling() {
        /*$('#bpostdata').scrollView();*/

        var email = $("#email").val();
        if (email != "") {
            var betaalmethode = "online";
            if ($('#online-betalen').is(':checked')) {
                /* Gekozen voor online betalen */
                betaalmethode = "online";
            } else {
                /* Gekozen voor betalen bij levering */
                betaalmethode = "levering";
                $("#betalendiv").html('<?php echo anchor('winkelmandje/bedanktbpostrembours', 'Bevestig bestelling', array('class' => 'button big icon fa-check')); ?>');
            }


            var geboortedatum = $("#geboortedatum").val();
            var kortingcode = $("#kortingcode").val();
            var land = $("#landen").val();
            var opmerkingen = $("#opmerkingen").val();
            var device = checkDevice();
            $.ajax({type: "GET",
                url: site_url + "/winkelmandje/createBestellingBeforeBpost",
                data: {kortingcode: kortingcode, email: email, geboortedatum: geboortedatum, land: land, opmerkingen: opmerkingen, betaalmethode: betaalmethode, device: device},
                success: function (result) {
                    if (result != "Mail niet ingevuld") {
                        $("#bpostdata").html(result);
                        $("#bpostdata").show();
                        $('html, body').animate({
                            scrollTop: $("#gegevens").offset().top
                        }, 2000);
                        $("#pre-init-data-bestelling").fadeOut("slow", function () {
                            $("#pre-init-data-bestelling").html("<h3>1. Klantgegevens invullen <img src='<?php echo base_url() . APPPATH; ?>images/icons/check.png'/> </h3> ");
                            $("#pre-init-data-bestelling").fadeIn("slow");
                        });
                    }

                }
            });


        } else {
            $("#email").addClass('wrongInput');
            $("#email").focus();
        }
    }

    function showinfo(val) {
        if (val == "postbode") {
            $("#small-dialog").html("<b>Let op</b>: Voor deze optie wordt er <i>5 EURO</i> aangerekend. <br/>Uw bestelling wordt aan huis geleverd en u betaalt het verschuldigde bedrag aan de postpode. Indien u afwezig bent op het moment van levering laat de postbode een nota achter in uw brievenbus. Met deze nota kan u uw pakketje alsnog gaan afhalen en betalen in een afhaalpunt in uw buurt.");

        } else if (val == "online") {
            $("#small-dialog").html("U betaalt online met uw bankkaart, visa of paypal. Uw bestelling wordt op het gekozen adres geleverd of beschikbaar gesteld in één van de BPOST automaten of afhaalpunten.");
        }

    }

    /* Function to check what kind of device the user is using to make this order */
    function checkDevice() {
        var isAndroid = /android/i.test(navigator.userAgent.toLowerCase());
        var device = "PC";
        if (isAndroid)
        {
            device = 'Android';
        }
        var isiPad = /ipad/i.test(navigator.userAgent.toLowerCase());
        if (isiPad)
        {
            device = 'iPad';
        }
        var isiPhone = /iphone/i.test(navigator.userAgent.toLowerCase());
        if (isiPhone)
        {
            device = 'iPhone';
        }
        var isBlackBerry = /blackberry/i.test(navigator.userAgent.toLowerCase());
        if (isBlackBerry)
        {
            device = 'BlackBerry';
        }
        var isWebOS = /webos/i.test(navigator.userAgent.toLowerCase());
        if (isWebOS)
        {
            device = 'WebOS';
        }
        var isWindowsPhone = /windows phone/i.test(navigator.userAgent.toLowerCase());
        if (isWindowsPhone)
        {
            device = 'Windows Phone';
        }
        return device;
    }


    /* Code needed to show popup (CSS and JS also required) */
    $(document).ready(function () {
        $('.popup-with-zoom-anim').magnificPopup({
            type: 'inline',
            fixedContentPos: false,
            fixedBgPos: true,
            overflowY: 'auto',
            closeBtnInside: true,
            preloader: false,
            midClick: true,
            removalDelay: 300,
            mainClass: 'my-mfp-zoom-in'
        });
    });

</script>
<style>
    .overzichtBpost td {
        text-align: left;
        line-height: 2em;
        height: auto;        
    }
    .wrongInput {
        border-color: #ff0000 !important;
        border-width: 1px !important;
        border-style: solid !important;
        box-shadow: inset 0 0 0 2px red !important;
    }
    .wrongInput:focus {
        box-shadow: inset 0 0 0 2px red !important;
        outline: none;
        /*border: none !important;*/
    }

    .correctInput {
        border-color: #00ff00 !important;
        border-width: 1px !important;
        border-style: solid !important;
    }

    .correctInput:focus {
        box-shadow: inset 0 0 0 0px green !important;
    }


    .container2{       
        margin: auto;
        padding-bottom: 40px;
        //max-height: 450px; 
        display: inline-block;        
    }

    .container2 ul{
        list-style: none;
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
    }


    .container2 ul li{
        color: #AAAAAA;
        display: block;
        position: relative;
        float: left;
        width: 100%;
        height: 100px;

    }

    .container2 ul li input[type=radio]{
        position: absolute;
        visibility: hidden;
    }

    .container2 ul li label{
        display: block;
        position: relative;
        font-weight: 300;
        font-size: 1.35em;
        padding: 25px 25px 25px 100px;
        margin: 10px auto;
        height: 30px;
        z-index: 9;
        cursor: pointer;
        -webkit-transition: all 0.25s linear;
    }

    .container2 ul li:hover label{
        color: #b1965d;
    }

    .container2 ul li .check{
        display: block;
        position: absolute;
        border: 5px solid #AAA;
        border-radius: 100%;
        height: 50px;
        width: 50px;
        top: 30px;
        left: 20px;
        z-index: 5;
        transition: border .25s linear;
        -webkit-transition: border .25s linear;
    }

    .container2 ul li:hover .check {
        border: 5px solid #b1965d;
    }

    .container2 ul li .check::before {
        display: block;
        position: absolute;
        content: '';
        border-radius: 100%;
        height: 34px;
        width: 34px;
        top: 3px;
        left: 3px;
        margin: auto;
        transition: background 0.25s linear;
        -webkit-transition: background 0.25s linear;          
    }

    .container2 ul li .check::after {        
        -webkit-box-sizing: initial !important;
        box-sizing: initial !important;

    }

    .container2 input[type=radio]:checked ~ .check {
        border: 5px solid #b1965d;
    }

    .container2 input[type=radio]:checked ~ .check::before{
        background: #b1965d;
    }

    .container2 input[type=radio]:checked ~ label{
        color: #b1965d;
    }

    /* popup */
    /* Styles for dialog window */
    #small-dialog {
        background: white;
        padding: 20px 30px;
        text-align: left;
        max-width: 400px;
        margin: 40px auto;
        position: relative;
    }


    /**
     * Fade-zoom animation for first dialog
     */

    /* start state */
    .my-mfp-zoom-in .zoom-anim-dialog {
        opacity: 0;

        -webkit-transition: all 0.2s ease-in-out; 
        -moz-transition: all 0.2s ease-in-out; 
        -o-transition: all 0.2s ease-in-out; 
        transition: all 0.2s ease-in-out; 



        -webkit-transform: scale(0.8); 
        -moz-transform: scale(0.8); 
        -ms-transform: scale(0.8); 
        -o-transform: scale(0.8); 
        transform: scale(0.8); 
    }

    /* animate in */
    .my-mfp-zoom-in.mfp-ready .zoom-anim-dialog {
        opacity: 1;

        -webkit-transform: scale(1); 
        -moz-transform: scale(1); 
        -ms-transform: scale(1); 
        -o-transform: scale(1); 
        transform: scale(1); 
    }

    /* animate out */
    .my-mfp-zoom-in.mfp-removing .zoom-anim-dialog {
        -webkit-transform: scale(0.8); 
        -moz-transform: scale(0.8); 
        -ms-transform: scale(0.8); 
        -o-transform: scale(0.8); 
        transform: scale(0.8); 

        opacity: 0;
    }

    /* Dark overlay, start state */
    .my-mfp-zoom-in.mfp-bg {
        opacity: 0;
        -webkit-transition: opacity 0.3s ease-out; 
        -moz-transition: opacity 0.3s ease-out; 
        -o-transition: opacity 0.3s ease-out; 
        transition: opacity 0.3s ease-out;
    }
    /* animate in */
    .my-mfp-zoom-in.mfp-ready.mfp-bg {
        opacity: 0.8;
    }
    /* animate out */
    .my-mfp-zoom-in.mfp-removing.mfp-bg {
        opacity: 0;
    }



    /**
     * Fade-move animation for second dialog
     */

    /* at start */
    .my-mfp-slide-bottom .zoom-anim-dialog {
        opacity: 0;
        -webkit-transition: all 0.2s ease-out;
        -moz-transition: all 0.2s ease-out;
        -o-transition: all 0.2s ease-out;
        transition: all 0.2s ease-out;

        -webkit-transform: translateY(-20px) perspective( 600px ) rotateX( 10deg );
        -moz-transform: translateY(-20px) perspective( 600px ) rotateX( 10deg );
        -ms-transform: translateY(-20px) perspective( 600px ) rotateX( 10deg );
        -o-transform: translateY(-20px) perspective( 600px ) rotateX( 10deg );
        transform: translateY(-20px) perspective( 600px ) rotateX( 10deg );

    }

    /* animate in */
    .my-mfp-slide-bottom.mfp-ready .zoom-anim-dialog {
        opacity: 1;
        -webkit-transform: translateY(0) perspective( 600px ) rotateX( 0 ); 
        -moz-transform: translateY(0) perspective( 600px ) rotateX( 0 ); 
        -ms-transform: translateY(0) perspective( 600px ) rotateX( 0 ); 
        -o-transform: translateY(0) perspective( 600px ) rotateX( 0 ); 
        transform: translateY(0) perspective( 600px ) rotateX( 0 ); 
    }

    /* animate out */
    .my-mfp-slide-bottom.mfp-removing .zoom-anim-dialog {
        opacity: 0;

        -webkit-transform: translateY(-10px) perspective( 600px ) rotateX( 10deg ); 
        -moz-transform: translateY(-10px) perspective( 600px ) rotateX( 10deg ); 
        -ms-transform: translateY(-10px) perspective( 600px ) rotateX( 10deg ); 
        -o-transform: translateY(-10px) perspective( 600px ) rotateX( 10deg ); 
        transform: translateY(-10px) perspective( 600px ) rotateX( 10deg ); 
    }

    /* Dark overlay, start state */
    .my-mfp-slide-bottom.mfp-bg {
        opacity: 0;

        -webkit-transition: opacity 0.3s ease-out; 
        -moz-transition: opacity 0.3s ease-out; 
        -o-transition: opacity 0.3s ease-out; 
        transition: opacity 0.3s ease-out;
    }
    /* animate in */
    .my-mfp-slide-bottom.mfp-ready.mfp-bg {
        opacity: 0.8;
    }
    /* animate out */
    .my-mfp-slide-bottom.mfp-removing.mfp-bg {
        opacity: 0;
    }

</style>

<script src="<?php echo base_url() . APPPATH; ?>js/magnific.js"></script>
<link rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>css/magnific.css" />
<!--<script src="https://shippingmanager.bpost.be/ShmFrontEnd/shm.js">-->
<script src="<?php echo base_url() . APPPATH; ?>js/bpost.js"></script>

<!-- Banner -->
<div id="banner-wrapper">
    <div id="banner" class="box container">
        <div class="row">
            <div class="12u">
                <h2><?php echo global_bedrijfsnaam; ?> webshop</h2>
                <p>Jouw winkelmandje!</p>
                <div class="pcOnly">
                    <?php
                    $totaalprijs = 0.00;
                    $incl = FALSE; // inclusief transport
                    if (count($karretje) == 0) {
                        echo "<div>Geen producten in winkelmandje!</div>";
                    } else {
                        echo "<table><tr><th>Product</th><th>Prijs</th><th>Aantal</th><th>Totaal</th></tr>";
                        foreach ($karretje as $karitem) {
                            if ($karitem->artikel->korting > 0) {
                                $prijsZonderKorting = $karitem->artikel->prijs;
                                $karitem->artikel->prijs = ($karitem->artikel->prijs - ($karitem->artikel->prijs * $karitem->artikel->korting / 100));
                            }
                            $totaalprijs += ($karitem->aantal * $karitem->artikel->prijs);
                            echo "<tr><td>" . anchor("artikels/details/" . $karitem->artikel->id, $karitem->artikel->naam) . " (" . $karitem->maat->maat . ")</td><td>&euro; " . str_replace('.', ',', number_format($karitem->artikel->prijs, 2)) . "</td><td>" . $karitem->aantal . "</td><td>&euro; " . str_replace('.', ',', number_format(($karitem->aantal * $karitem->artikel->prijs), 2)) . "</td><td>" . anchor('winkelmandje/verwijder/' . $karitem->createid, 'Verwijderen') . "</td></tr>";

                            // PRIJS TERUG ZETTE
                            if ($karitem->artikel->korting > 0) {
                                $karitem->artikel->prijs = $prijsZonderKorting;
                            }
                        }
                        echo "</table>";
                        echo "<input type='hidden' id='hiddenSubPrijs' value='$totaalprijs' />";
                        $incl = FALSE; // inclusief transport
                        if ($totaalprijs < $taxvrijlimiet) {
                            $totaalprijs+=$transportkost;
                            $incl = TRUE;
                        }
                        echo "<p id='totaalPrijs'>Totaalprijs: &euro; <label id='totaalPrijsNumber'>" . number_format($totaalprijs, 2) . "</label></p>";
                        if ($incl) {
                            echo "<p class='smalltext'>Inclusief verzendingskosten: &euro; " . $transportkost . " (vanaf &euro;" . number_format($taxvrijlimiet, 2) . " gratis verzonden in België!)</p>";
                        } else {
                            echo "<p class='smalltext'>Inclusief gratis verzending</p>";
                        }
                    }
                    ?>
                </div>
                <div class="phoneOnly">

                    <?php
                    if (count($karretje) == 0) {
                        echo "<div>Geen producten in winkelmandje!</div>";
                    } else {
                        foreach ($karretje as $karitem) {
                            ?>  

                            <?php if ($counter == 0) { ?> <div class="12u" style="text-align: center; padding: 5%;"> <?php } ?>

                                <div class="6u" style="float: left; margin: 0px; padding-right: 1%; padding-left: 1%;">						
                                    <!-- Box -->
                                    <section class="box feature">
                                        <?php if ($karitem->artikel->korting > 0) { ?>
                                            <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $karitem->artikel->korting . "&#37;"; ?></div></div>
                                        <?php } ?>
                                        <?php echo anchor('artikels/details/' . $karitem->artikel->id, '<img src="' . base_url() . APPPATH . $karitem->artikel->imagePath . '" alt="" />', array('class' => 'image featured shopimg')); ?>
                                        <div class="inner">
                                            <header>

                                                <?php if ($karitem->artikel->korting == 0) { ?>
                                                    <label><?php echo $karitem->aantal . "x "; ?>&euro;<?php echo number_format($karitem->artikel->prijs, 2) ?></label>
                                                <?php } else { ?>
                                                    <label><?php echo $karitem->aantal . "x "; ?><span style="text-decoration:line-through;">&euro;<?php echo number_format($karitem->artikel->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($karitem->artikel->prijs - ($karitem->artikel->prijs * $karitem->artikel->korting / 100)), 2) ?></label>
                                                <?php } ?>
                                                <br/><label><?php echo anchor('winkelmandje/verwijder/' . $karitem->createid, 'Verwijderen'); ?></label>

                                            </header>

                                        </div>
                                    </section>

                                </div>						
                                <?php if ($counter == 1 || count($karretje) == $fullcounter + 1) { ?></div> <?php } ?>
                            <?php
                            $counter++;
                            $fullcounter++;
                            if ($counter > 1) {
                                $counter = 0;
                            }
                            ?>
                        <?php } ?>
                        <div class="row">
                            <?php
                            echo "<p>Totaalprijs: &euro; " . number_format($totaalprijs, 2) . "</p>";
                            if ($incl) {
                                echo "<p class='smalltext'>Inclusief verzendingskosten: &euro; " . $transportkost . " (vanaf &euro;" . number_format($taxvrijlimiet, 2) . " gratis verzonden in België!)</p>";
                            } else {
                                echo "<p class='smalltext'>Inclusief gratis verzending</p>";
                            }
                        } // einde IF karretje is leeg
                        ?>
                    </div>
                </div>
            </div>
            <div class="12u">
                <h2 id="gegevens">Uw gegevens</h2>
                <div id="pre-init-data-bestelling">                    
                    <?php
                    $data = array('onSubmit' => 'voorwaarden()', 'class' => 'form-field');
                    echo form_open('winkelmandje/bestellingplaatsen', $data);
                    ?>

                    <table border="0" class="afrekenTable">           
                        <tr>
                            <td><?php echo form_label('Land*: ', 'land'); ?></td>
                            <td>
                                <?php
                                $options = array(
                                    'BE' => 'België',
                                    'NL' => 'Nederland',
                                    'FR' => 'Frankrijk',
                                    'LU' => 'Luxemburg',
                                    'DE' => 'Duitsland',
                                );

                                echo form_dropdown('land', $options, 'BE', 'id="landen" onchange="checkPrijsLandChange(this);"');
                                ?>
                            </td>         
                            <td colspan="2">
                                <span id="prijsland" style="color: #E61D80; line-height: 2; display: block;"></span>
                            </td>                  
                        </tr>
                        <tr>
                            <td><?php echo form_label('E-mail*: ', 'email'); ?></td>
                            <td>
                                <?php
                                $data = array('type' => 'email', 'name' => 'email', 'id' => 'email', 'required' => 'required', 'placeholder' => 'E-mail', 'class' => 'form-fieldinput email');
                                //$data = array('name' => 'email', 'id' => 'email', 'required' => 'required', 'placeholder' => 'E-mail', 'class' => 'form-fieldinput', 'value' => 'jeroen_vinken@hotmail.com');
                                echo form_input($data);
                                ?>
                            </td>
                            <td><?php echo form_label('Geboortedatum: ', 'geboortedatum'); ?></td>
                            <td>
                                <?php
                                $data = array('name' => 'geboortedatum', 'id' => 'geboortedatum', 'placeholder' => 'Geboortedatum', 'type' => 'date', 'class' => 'form-fieldinput');
                                //$data = array('name' => 'geboortedatum', 'id' => 'geboortedatum', 'placeholder' => 'Geboortedatum', 'type' => 'date', 'class' => 'form-fieldinput','value' => '16/07/1993');
                                echo form_input($data);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo form_label('Opmerkingen: ', 'opmerkingen'); ?></td>
                            <td colspan="5">
                                <?php
                                $data = array('name' => 'opmerkingen', 'id' => 'opmerkingen', 'cols' => '50', 'rows' => '6', 'placeholder' => 'Vul hier je opmerkingen in.');
                                //$data = array('name' => 'opmerkingen', 'id' => 'opmerkingen', 'cols' => '50', 'rows' => '6', 'placeholder' => 'Vul hier je opmerkingen in.', 'value' => 'test auto');
                                echo form_textarea($data);
                                ?> 
                            </td>                        
                        </tr>
                        <tr style="border-top: 1px solid #eee;">
                            <td style="padding-top:30px;"><?php echo form_label('Kortingscode: ', 'kortingcode'); ?></td>
                            <td style="padding-top:30px;" colspan="3">
                                <?php
                                $data = array('name' => 'kortingcode', 'id' => 'kortingcode', 'placeholder' => 'Kortingscode', 'class' => 'form-fieldinput', 'onkeyup' => 'checkIfCodeExists()', 'autocomplete' => 'off');
                                echo form_input($data);
                                ?>
                            </td>
                        </tr>
                        <tr id="kortingTekstTd" style="display: none;">
                            <td colspan="4"><p id="kortingTekst"></p></td>
                        </tr>
                    </table>

                    <div class="container2"> 
                        <ul>
                            <li>
                                <input type="radio" id="online-betalen" name="selector" checked="checked">
                                <label for="online-betalen">Betaal online <a class="popup-with-zoom-anim" href="#small-dialog"><i class="fa fa-info-circle" aria-hidden="true" onclick='showinfo("online");'></i></a></label>
                                <div class="check"></div>
                            </li>

                            <li>
                                <input type="radio" id="postbode-betalen" name="selector">
                                <label for="postbode-betalen">Betalen bij levering (+ &euro;5) <a class="popup-with-zoom-anim" href="#small-dialog"><i class="fa fa-info-circle" aria-hidden="true" onclick='showinfo("postbode");'></i></a></label>
                                <div class="check"><div class="inside"></div></div>
                            </li>                          
                        </ul>
                    </div>
                </div>     

                <div id="bpost-overzicht" style="display:none;"></div>
                <!-- BPOST INLINE DIV -->
                <div id="bpostdata" style="display:block; width: auto;"><p style='font-size: 100%; padding-bottom: 10px;'>Klik op onderstaande knop om door te gaan naar de volgende stap waar u kan kiezen hoe wij uw bestelling moeten verzenden.</p><a href="javascript:void(0)"  onclick="startbpostbestelling()" class="button big icon fa-truck">Leveringswijze kiezen</a></div>
                <!-- EINDE BPOST INLINE DIV -->

                <div id="betalendiv" style="display: none;">
                    <?php
                    echo '<label>Door te bevestigen ga je akkoord met de <a href="../../application/files/voorwaarden.pdf" target="_blank">gebruiksvoorwaarden</a>. </label>';
                    echo "<br/><br/>";
                    // so BPOST does not like http and requires https so we are going to work around this issue here by using ajax...
                    //echo "<input type='button' value='Bevestig en betaal met uw bank- of creditcard' onclick='bevestigbpostbestelling(" . $bestellingid . ")'/>";
                    echo anchor('winkelmandje/bevestigbpostbestelling', 'Klik hier om te betalen*', array('class' => 'button big icon fa-credit-card'));
                    echo "<p style='font-size: 100%;'>*Door op bovenstaande betaalknop te drukken wordt u afgeleid naar ons beveiligd betaalplatvorm. Na het ingeven van uw bankgegevens komt u terecht op de pagina van uw eigen bank.</p>";
                    echo '<div style="margin-top:20px;">';
                    echo '<img src="' . base_url() . APPPATH . 'images/paypal-logo.png" alt="" height="50px" align="left" />';
                    echo '<img src="' . base_url() . APPPATH . 'images/bancontact-logo.png" alt="" height="50px" align="left" />';
                    echo '<img src="' . base_url() . APPPATH . 'images/visa-master-maestro-logo.png" alt="" height="50px" align="left" />';
                    echo '</div>'
                    ?>  
                </div>

                <?php
                echo form_close();
                ?>
            </div>            
        </div>        
    </div>
</div>

<!-- dialog itself, mfp-hide class is required to make dialog hidden -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide">
    <h1>Dialog example</h1>
    <p>This is dummy copy. It is not meant to be read. It has been placed here solely to demonstrate the look and feel of finished, typeset text. Only for show. He who searches for meaning here will be sorely disappointed.</p>
</div>