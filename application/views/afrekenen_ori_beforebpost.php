<?php //session_start();     ?>
<script>
    $(document).ready(function () {
        scrollToGegevens();
        function scrollToGegevens() {
            $('#gegevens').scrollView();
        }

        function voorwaarden() {
            if (!($('voorwaarden').checked())) {
                //return false;
            }
        }
    });
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
        if ($("#landen").val() !== "belgie") {
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
                            $("#kortingcode").css('border-color', 'red');
                            $("#kortingcode").css('color', 'red');
                        } else {
                            $("#kortingTekst").html(result.toString());
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
    }

    function addKortingFixed(kortingString) {
        var totaalPrijsVoorKorting = $("#totaalPrijsNumber").html();
        var kortingFixed = kortingString.substring(0, kortingString.length - 5);
        var totaalPrijsNaKorting = totaalPrijsVoorKorting - kortingFixed;

        if (totaalPrijsNaKorting < 0) {
            totaalPrijsNaKorting = 0;
        }

        $("#kortingTekst").html("Totaalprijs met kortingscode: &euro;" + totaalPrijsNaKorting.toFixed(2));
    }
        
    $(document).keypress("enter", function (e) {
        if (e.ctrlKey)
            $("#molliebetalen").click();
    });

</script>

<!-- Banner -->
<div id="banner-wrapper">
    <div id="banner" class="box container">
        <div class="row">
            <div class="12u">
                <h2><?php echo global_bedrijfsnaam; ?> webshop</h2>
                <p>Jouw winkelmandje!</p>                                                        
                <?php
                $totaalprijs = 0.00;
                $artikelString = "";
                if (count($karretje) == 0) {
                    echo "<div>Geen producten in winkelmandje!</div>";
                } else {
                    echo "<table><tr><th>Product</th><th>Prijs</th><th>Aantal</th><th>Totaal</th></tr>";
                    foreach ($karretje as $karitem) {
                        if ($karitem->artikel->korting > 0) {
                            $karitem->artikel->prijs = ($karitem->artikel->prijs - ($karitem->artikel->prijs * $karitem->artikel->korting / 100));
                        }
                        $totaalprijs += ($karitem->aantal * $karitem->artikel->prijs);
                        echo "<tr><td>" . anchor("artikels/details/" . $karitem->artikel->id, $karitem->artikel->naam) . " (" . $karitem->maat->maat . ")</td><td>&euro; " . str_replace('.', ',', number_format($karitem->artikel->prijs, 2)) . "</td><td>" . $karitem->aantal . "</td><td>&euro; " . str_replace('.', ',', number_format(($karitem->aantal * $karitem->artikel->prijs), 2)) . "</td><td>" . anchor('winkelmandje/verwijder/' . $karitem->createid, 'Verwijderen') . "</td></tr>";

                        $artikelString .= $karitem->artikel->naam . " (" . $karitem->aantal . ") - ";
                    }
                    $artikelString = substr($artikelString, 0, -3);
                    //$this->session->set_userdata('artikelString', $artikelString);
                    echo "</table>";
                    echo "<input type='hidden' id='hiddenSubPrijs' value='$totaalprijs' />";
                    $incl = FALSE; // inclusief transport
                    if ($totaalprijs < $taxvrijlimiet) {
                        $totaalprijs+=$transportkost; // hier aanpassen voor geen transportkosten
                        $incl = TRUE;
                    }
                    echo "<p id='totaalPrijs'>Totaalprijs: &euro; <label id='totaalPrijsNumber'>" . number_format($totaalprijs, 2) . "</label></p>";
                    if ($incl) {
                        echo "<p class='smalltext' id='leveringTekst'>Inclusief verzendingskosten: &euro; $transportkost (vanaf &euro;" . number_format($taxvrijlimiet, 2) . " gratis verzonden in België!)</p>";
                    } else {
                        echo "<p class='smalltext' id='leveringTekst'>Inclusief gratis verzending</p>";
                    }
                    //$this->session->set_userdata('totaalprijs', $totaalprijs); //niemand weet waarom da hier crasht, vuilen brol
                    //$_SESSION["totaalprijs"] = $totaalprijs;
                }
                ?>
            </div>            
        </div>
        <br>
        <hr>
        <div class="row">
            <div class="12u">
                <h2 id="gegevens">Uw gegevens</h2>
                <?php
                $data = array('onSubmit' => 'voorwaarden()', 'class' => 'form-field');
                echo form_open('winkelmandje/bestellingplaatsen', $data);
                ?>

                <table border="0" class="afrekenTable">           
                    <tr>                    
                        <td><?php echo form_label('Voornaam + naam*: ', 'naam'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'naam', 'id' => 'naam', 'placeholder' => 'Vooraam naam', 'class' => 'form-fieldinput', 'required' => 'required');
                            //$data = array('name' => 'naam', 'id' => 'naam', 'placeholder' => 'Vooraam naam', 'class' => 'form-fieldinput', 'value' => 'Jeroen Vinken');
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
                        <td><?php echo form_label('Land*: ', 'land'); ?></td>
                        <td>
                            <?php
                            $options = array(
                                'belgie' => 'België',
                                'nederland' => 'Nederland',
                                'frankrijk' => 'Frankrijk',
                                'luxemburg' => 'Luxemburg',
                                'duitsland' => 'Duitsland',
                            );

                            echo form_dropdown('land', $options, 'belgie', 'id="landen" onchange="checkPrijsLandChange(this);"');
                            ?>
                        </td>         
                        <td colspan="2">
                            <span id="prijsland" style="color: #E61D80; line-height: 2; display: block;"></span>
                        </td>                  
                    </tr>                    
                    <tr>
                        <td><?php echo form_label('Straat*: ', 'straat'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'straat', 'id' => 'straat', 'required' => 'required', 'placeholder' => 'Straat', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'straat', 'id' => 'straat', 'required' => 'required', 'placeholder' => 'Straat', 'class' => 'form-fieldinput', 'value' => 'Hertog Janplein');
                            echo form_input($data);
                            ?>
                        </td>
                        <td><?php echo form_label('Huisnummer*: ', 'nr'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'nr', 'id' => 'nr', 'required' => 'required', 'placeholder' => 'Huisnummer', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'nr', 'id' => 'nr', 'required' => 'required', 'placeholder' => 'Huisnummer', 'class' => 'form-fieldinput', 'value' => '14d');
                            echo form_input($data);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo form_label('Postcode*: ', 'postcode'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'postcode', 'id' => 'postcode', 'required' => 'required', 'placeholder' => 'Bv. 3500', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'postcode', 'id' => 'postcode', 'required' => 'required', 'placeholder' => 'Bv. 3500', 'class' => 'form-fieldinput', 'value' => '3920');
                            echo form_input($data);
                            ?>
                        </td>
                        <td><?php echo form_label('Woonplaats*: ', 'woonplaats'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'woonplaats', 'id' => 'woonplaats', 'required' => 'required', 'placeholder' => 'Bv. Hasselt', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'woonplaats', 'id' => 'woonplaats', 'required' => 'required', 'placeholder' => 'Bv. Hasselt', 'class' => 'form-fieldinput', 'value' => 'Lommel');
                            echo form_input($data);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo form_label('Tel.*: ', 'telefoon'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'telefoon', 'id' => 'telefoon', 'required' => 'required', 'placeholder' => 'Telefoonnummer', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'telefoon', 'id' => 'telefoon', 'required' => 'required', 'placeholder' => 'Telefoonnummer', 'class' => 'form-fieldinput', 'value' => '0473137332');
                            echo form_input($data);
                            ?>
                        </td>                    
                        <td><?php echo form_label('E-mail*: ', 'email'); ?></td>
                        <td>
                            <?php
                            $data = array('type' => 'email', 'name' => 'email', 'id' => 'email', 'required' => 'required', 'placeholder' => 'E-mail', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'email', 'id' => 'email', 'required' => 'required', 'placeholder' => 'E-mail', 'class' => 'form-fieldinput', 'value' => 'jeroen_vinken@hotmail.com');
                            echo form_input($data);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="nieuwsbrief" style="line-height: 2; display: block;">Inschrijven <br>voor nieuwsbrief</label></td>
                        <td>
                            <?php
                            echo form_checkbox('nieuwsbrief', 'ja', FALSE);
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
                    <tr><td colspan="4"><a href="#" id="klikLever" onclick="showLeverOpties();
                            return false;">Klik hier voor een alternatief leveradres op te geven.</a></td></tr>
                    <tr class="leverAdres" style="display:none;">
                        <td><?php echo form_label('Naam: ', 'levnaam'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'levnaam', 'id' => 'levnaam', 'placeholder' => 'Naam', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'postcode', 'id' => 'postcode', 'required' => 'required', 'placeholder' => 'Bv. 3500', 'class' => 'form-fieldinput', 'value' => '3920');
                            echo form_input($data);
                            ?>
                        </td>
                        <td><?php echo form_label('ContactPersoon: ', 'levcontactpersoon'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'levcontactpersoon', 'id' => 'levcontactpersoon', 'placeholder' => 'Contactpersoon', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'woonplaats', 'id' => 'woonplaats', 'required' => 'required', 'placeholder' => 'Bv. Hasselt', 'class' => 'form-fieldinput', 'value' => 'Lommel');
                            echo form_input($data);
                            ?>
                        </td>
                    </tr>
                    <tr class="leverAdres" style="display:none;">
                        <td><?php echo form_label('Land: ', 'levland'); ?></td>
                        <td>
                            <?php
                            $options = array(
                                'belgie' => 'België',
                                'nederland' => 'Nederland',
                                'frankrijk' => 'Frankrijk',
                                'luxemburg' => 'Luxemburg',
                                'duitsland' => 'Duitsland',
                            );

                            echo form_dropdown('levland', $options, 'belgie', 'id="levlanden" onchange="checkPrijsLevLandChange(this);"');
                            ?>
                        </td>         
                        <td colspan="2">
                            <span id="levprijsland" style="color: #E61D80; line-height: 2; display: block;"></span>
                        </td>    
                    </tr>
                    <tr class="leverAdres" style="display:none;">
                        <td><?php echo form_label('Straat: ', 'straat'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'levstraat', 'id' => 'levstraat', 'placeholder' => 'Straat', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'straat', 'id' => 'straat', 'required' => 'required', 'placeholder' => 'Straat', 'class' => 'form-fieldinput', 'value' => 'Hertog Janplein');
                            echo form_input($data);
                            ?>
                        </td>
                        <td><?php echo form_label('Huisnummer: ', 'nr'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'levnr', 'id' => 'levnr', 'placeholder' => 'Huisnummer', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'nr', 'id' => 'nr', 'required' => 'required', 'placeholder' => 'Huisnummer', 'class' => 'form-fieldinput', 'value' => '14d');
                            echo form_input($data);
                            ?>
                        </td>
                    </tr>
                    <tr class="leverAdres" style="display:none;">
                        <td><?php echo form_label('Postcode: ', 'levpostcode'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'levpostcode', 'id' => 'levpostcode', 'placeholder' => 'Bv. 3500', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'postcode', 'id' => 'postcode', 'required' => 'required', 'placeholder' => 'Bv. 3500', 'class' => 'form-fieldinput', 'value' => '3920');
                            echo form_input($data);
                            ?>
                        </td>
                        <td><?php echo form_label('Woonplaats: ', 'levwoonplaats'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'levwoonplaats', 'id' => 'levwoonplaats', 'placeholder' => 'Bv. Hasselt', 'class' => 'form-fieldinput');
                            //$data = array('name' => 'woonplaats', 'id' => 'woonplaats', 'required' => 'required', 'placeholder' => 'Bv. Hasselt', 'class' => 'form-fieldinput', 'value' => 'Lommel');
                            echo form_input($data);
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
                    <tr>
                        <td colspan="4"><p id="kortingTekst"></p></td>
                    </tr>
                </table>                


                <?php
                //echo form_label('Ik ga akkoord met de gebruiksvoorwaarden: ', 'voorwaarden');
                //$data = array('name' => 'voorwaarden', 'id' => 'voorwaarden', 'class' => 'smallcheckbox');
                //echo form_checkbox($data);
                echo '<label>Door te bevestigen ga je akkoord met de <a href="../../application/files/voorwaarden.pdf" target="_blank">gebruiksvoorwaarden</a>. </label>';
                echo "<br/><br/>";
                //echo form_submit('submit', 'Bevestig en betaal via overschrijving');
                $js = 'onClick=""';
                //echo form_submit('submitpaypal', 'Bevestig en betaal via paypal', $js);
                //$js = 'style="display: none" id="molliebetalen"';
                echo form_submit('submitmollie', 'Bevestig en betaal met uw bank- of creditcard', $js);
                
                echo '<div style="margin-top:20px;">';
                echo '<img src="' . base_url() . APPPATH . 'images/paypal-logo.png" alt="" height="50px" align="left" />';
                echo '<img src="' . base_url() . APPPATH . 'images/bancontact-logo.png" alt="" height="50px" align="left" />';
                echo '<img src="' . base_url() . APPPATH . 'images/visa-master-maestro-logo.png" alt="" height="50px" align="left" />';
                echo '</div>'
                ?>  
                
                
                <?php
                echo form_close();
                ?>

            </div>            
        </div>
    </div>
</div>

<br/>

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
                            <?php foreach ($onlangsbekeken as $artikel) { ?>  

                                <?php if ($counter == 0) { ?> <div class="row no-collapse 50%"> <?php } ?>
                                    <div class="6u">
                                        <?php echo anchor('artikels/details/' . $artikel->id, '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" />', array('class' => 'image fit')); ?>


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
                                echo "</p>Geen onlangs bekeken artikels.</p>";
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
                        <p>Wil je graag op de hoogte gehouden worden van de laatste nieuwtjes bij <?php echo global_bedrijfsnaam; ?>? Schrijf je dan snel in voor onze nieuwsbrief!</p>
                        <?php echo anchor('nieuwsbrief/index', 'Ga verder', array('class' => 'button icon fa-arrow-circle-right')); ?>
                    </section>
                </div>

            </div>
        </div>
    </div>
</div>