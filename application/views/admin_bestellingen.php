<!-- Main -->
<script>
    function archiveer(id)
    {
        $.ajax({type: "GET",
            url: site_url + "/admin/archiveerbestelling",
            data: {bestellingid: id},
            success: function (result) {
                $("#bestelling" + id).fadeTo("slow", 0.1, function () {
                    // Animation complete.
                    $("#bestelling" + id).remove();
                });
            }
        });
    }

    function verzonden(id)
    {
        $.ajax({type: "GET",
            url: site_url + "/admin/verzendbestelling",
            data: {bestellingid: id},
            success: function (result) {

            }
        });
    }

    function betaald(id)
    {
        $.ajax({type: "GET",
            url: site_url + "/admin/betaalbestelling",
            data: {bestellingid: id},
            success: function (result) {

            }
        });
    }

    function geannuleerd(id)
    {
        $.ajax({type: "GET",
            url: site_url + "/admin/annuleerbestelling",
            data: {bestellingid: id},
            success: function (result) {

            }
        });
    }

    function gereserveerd(id, land)
    {
        /* Copy Rekeningnummer to clipboard for use in shippingmanager */
        var textarea = document.getElementById("tocopy");
        $("#tocopy").show();
        textarea.select();
        var successful = document.execCommand('copy');
        $("#tocopy").hide();

        $.ajax({type: "GET",
            url: site_url + "/admin/reserveerbestelling",
            data: {bestellingid: id},
            success: function (result) {
                // Bevestig BPOST bestelling (OPEN)
                $.ajax({type: "GET",
                    url: site_url + "/admin/confirmOrderForBpostAjax",
                    data: {bestellingId: id, land: land},
                    success: function (result) {
                        /* Load shippingmanager as a hack to confirm an order */
                        $("#bpostBevestigingAjax").html(result);
                    }
                });
            }
        });


    }

    function remind(id)
    {
        $.ajax({type: "GET",
            url: site_url + "/admin/remindbestelling",
            data: {bestellingid: id},
            success: function (result) {

            }
        });
    }

    function refundClient(bestellingId) {
        var terugbetaalbedrag = $("#terugbetaalbedrag").val();
        alert(bestellingId + ", " + terugbetaalbedrag);
        $.ajax({type: "GET",
            url: site_url + "/admin/betalingMetMollieTerugbetalen",
            data: {bestellingId: bestellingId, terugbetaalbedrag: terugbetaalbedrag},
            success: function (result) {
                $("#refundstatus").html(result);
            }
        });
    }

    var key_count_global = 0;
    //zoekArtikel();
    function zoekBestelling() {
        key_count_global++;
        setTimeout("lookup(" + key_count_global + ")", 1000)

    }
    function lookup(key_count) {
        var input = $("#zoekbestelling").val();
        if (key_count == key_count_global && input != "") {
            var input = $("#zoekbestelling").val();
            $.ajax({type: "GET",
                url: site_url + "/admin/searchBestellingenByInput",
                data: {input: input},
                success: function (result) {
                    $("#bestellingen").html(result);
                }
            });
        }
    }
</script>
<script src="<?php echo base_url() . APPPATH; ?>js/bpost.js"></script>
<style>
    .eventbg {
        background-image: url('<?php echo base_url() . APPPATH; ?>images/event.png');
        background-repeat: no-repeat;    
        background-position: top right;
    }
</style>
<?php
// message tekst aangepast
if (isset($bewerkt)) {
    echo "<div class='toegevoegd'><p>" . $bewerkt . "</p></div>";
}
?>
<div id="main-wrapper">    
    <div class="container">
        <div id="content">

            <!-- Content -->
            <article>

                <h2>Admin Bestellingen</h2>
                <h3>De laatste nieuwe bestellingen staan vanboven! (tijdstip kan een uur veschillen)</h3>
                <?php if (count($bestellingen) > 1) { ?>
                    <div class="row">
                        <div class="12u">               
                            <input type="text" placeholder="Zoek een bestelling op nummer, naam of mollieId" id="zoekbestelling" class="zoekartikelhome" onkeyup="zoekBestelling()" />
                            <br/>
                            <br/>
                        </div>            
                    </div>
                <?php } ?>
                <div id='bestellingen'>
                    <?php
                    foreach ($bestellingen as $bestelling) {
                        // nog archief bij voegen!!!
                        echo "<div style='position: relative;' id='bestelling$bestelling->id'>";
                        echo "<div style='position: absolute; top: 0; right: 0; text-align: right;'><label style='font-size:30px;'>Archief: </label><input type='checkbox' ";
                        if ($bestelling->archief == 1) {
                            echo "checked='checked'";
                        };
                        echo " onchange='archiveer($bestelling->id);' name='archief' style='width: 30px; height: 30px;' />";
                        echo "<br/><label style='font-size:30px;'>Betaald: </label><input type='checkbox' ";
                        if ($bestelling->betaald == 1) {
                            echo "checked='checked'";
                        };
                        echo " onchange='betaald($bestelling->id);' name='betaald' style='width: 30px; height: 30px;' />";
                        echo "<br/><label style='font-size:30px;'>Verzonden: </label><input type='checkbox' ";
                        if ($bestelling->verzonden == 1) {
                            echo "checked='checked'";
                        };
                        echo " onchange='verzonden($bestelling->id);' name='verzonden' style='width: 30px; height: 30px;' />";
                        echo "<br/><label style='font-size:30px;'>Geannuleerd: </label><input type='checkbox' ";
                        if ($bestelling->geannuleerd == 1) {
                            echo "checked='checked'";
                        };
                        echo " onchange='geannuleerd($bestelling->id);' name='geannuleerd' style='width: 30px; height: 30px;' />";
                        echo "<br/><label style='font-size:30px;'>Gereserveerd: </label><input type='checkbox' ";
                        if ($bestelling->gereserveerd == 1) {
                            echo "checked='checked'";
                        };
                        echo " onchange='gereserveerd($bestelling->id, \"$bestelling->leverLand\");' name='gereserveerd' style='width: 30px; height: 30px;' />";
                        echo "<br/><label style='font-size:30px;'>Reminder: </label><input type='checkbox' ";
                        if ($bestelling->reminder == 1) {
                            echo "checked='checked'";
                        };
                        echo " onchange='remind($bestelling->id);' name='reminder' style='width: 30px; height: 30px;' /></div>";
                        if (strpos($bestelling->mollieId, 'REMBOURS') !== false) {
                            echo "<h3 style='color: red;'>";
                        } else {
                            echo "<h3>";
                        }

                        $iconclass = "";
                        switch ($bestelling->device) {
                            case "PC":
                                $iconclass = "laptop";
                                break;
                            case "Android":
                                $iconclass = "android";
                                break;
                            case "iPhone":
                                $iconclass = "apple iPhone";
                                break;
                            case "iPad":
                                $iconclass = "apple iPad";
                                break;
                            case "WebOs":
                                $iconclass = "television";
                                break;
                            case "BlackBerry":
                                $iconclass = "braille blackberry";
                                break;
                            case "Windows Phone":
                                $iconclass = "windows phone";
                                break;
                            default:
                                $iconclass = "laptop other";
                        }

                        echo "<i class='fa fa-$iconclass' title='Besteld met $iconclass' aria-hidden='true'></i>  ";

                        echo anchor('admin/bestelling/' . $bestelling->id, $bestelling->persoon->naam . " op " . $bestelling->datum);
                        if ($bestelling->paypal == 1) {
                            echo " (PayPal)";
                        } else if ((strpos($bestelling->mollieId, 'REMBOURS') !== false)) {
                            echo " (Rembours)";
                        } else if ($bestelling->mollieId != '0' && $bestelling->mollieId != null) {
                            echo " (Mollie)";
                        }
                        if ((strpos($bestelling->mollieId, 'REMBOURS') !== false)) {
                            echo " - &euro;" . number_format(($bestelling->totaalprijs + 5), 2);
                        } else {
                            echo " - &euro;" . number_format($bestelling->totaalprijs, 2);
                        }

                        echo "</h3>";

                        echo "<ul>";
                        echo "<li>Bestelnummer: " . $bestelling->id . "";
                        echo "<li>Adres: " . $bestelling->persoon->straat . " " . $bestelling->persoon->huisnummer . ", " . $bestelling->persoon->postcode . " " . $bestelling->persoon->woonplaats . " (" . $bestelling->persoon->land . ")</li>";
                        echo "<li>Leveradres: " . $bestelling->leverNaam . " (" . $bestelling->leverContactpersoon . ") - " . $bestelling->leverStraat . " " . $bestelling->leverHuisnummer . ", " . $bestelling->leverPostcode . " " . $bestelling->leverGemeente . " (" . $bestelling->leverLand . ")</li>";
                        echo "<li>E-mail: " . $bestelling->persoon->email . "</li>";
                        echo "<li>Telefoon: " . $bestelling->persoon->telefoon . "</li>";
                        echo "<li>Geboortedatum: " . $bestelling->persoon->geboortedatum . "</li>";
                        if (count($bestellingen) == 1 && $bestelling->opmerkingen != '') {
                            echo "<li>Opmerkingen: " . $bestelling->opmerkingen . "</li>";
                        }
                        if (count($bestellingen) == 1) {
                            /* echo '<li><br/></li>';
                              echo '<li><h3>Terugbetalingen</h3></li>';
                              echo '<form><li><input type="text" id="terugbetaalbedrag" class="form-fieldinput" placeholder="Leeg laten voor alles terug te betalen"/></li>';
                              echo '<br/><li><input type="button" id="btnRefund" onclick="refundClient(' . $bestelling->id . ')" value="Bedrag terugbetalen"/></li>';
                              echo '<br/><li><p id="refundstatus">Momenteel &euro;' . $bestelling->terugBetaaldBedrag . ' terugbetaald...</p></li></form>'; */
                        }
                        echo "</ul>";
                        echo "<h4>Bestelde artikels</h4><ul>";
                        foreach ($bestelling->bestellingartikels as $artikel) {
                            echo "<li>" . $artikel->artikel->naam . " (" . $artikel->maat->maat . ") x" . $artikel->aantal . "</li>";
                        }
                        // kortingscode weergeven (indien van toepassing)
                        if ($bestelling->kortingCode != null) {
                            $code = $bestelling->kortingCode;
                            $kortingstring = "";
                            if ($code->kortingBedrag == null) {
                                $kortingPercentage = $code->kortingProcent;
                                $kortingstring = "-" . $kortingPercentage . "%";
                            } else {
                                $kortingBedrag = $code->kortingBedrag;
                                $kortingstring = "-" . $kortingBedrag . " EURO";
                            }

                            echo "KORTINGSCODE: " . $code->code . " (" . $kortingstring . ")";
                        }
                        echo "</ul>";
                        echo "<hr/><br/>";
                        echo "</div>";
                    }

                    if (count($bestellingen) == 0) {
                        echo "<p>Geen nieuwe bestellingen gevonden.</p>";
                    }
                    ?>
                </div>

            </article>

            <article>               

                <?php
                if (count($bestellingen) == 1) {
                    echo "<h2>Artikels</h2>";
                    foreach ($bestellingen as $bestelling) {

                        $counter = 0;
                        $fullcounter = 0;

                        foreach ($bestelling->bestellingartikels as $bestellingartikel) {

                            if ($counter == 0) {
                                ?> <div class="row"> <?php } ?>
                                <div class="4u">						
                                    <!-- Box -->
                                    <section class="box feature"> 
                                        <?php if ($bestellingartikel->artikel->korting > 0) { ?>
                                            <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $bestellingartikel->artikel->korting . "&#37;"; ?></div></div>
                                        <?php } ?>
                                        <?php echo '<a class="image featured shopimg"><img src="' . base_url() . APPPATH . $bestellingartikel->artikel->imagePath . '" alt="" /></a>'; ?>
                                        <div class="inner">
                                            <header>
                                                <h2><?php echo $bestellingartikel->artikel->naam ?></h2>
                                                <?php if ($bestellingartikel->artikel->korting == 0) { ?>
                                                    <p>&euro;<?php echo number_format($bestellingartikel->artikel->prijs, 2) ?></p>
                                                <?php } else { ?>
                                                    <p><span style="text-decoration:line-through;">&euro;<?php echo number_format($bestellingartikel->artikel->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($bestellingartikel->artikel->prijs - ($bestellingartikel->artikel->prijs * $bestellingartikel->artikel->korting / 100)), 2) ?></p>
                                                <?php } ?>
                                            </header>
                                            <div class="omschrijving">
                                                <p>
                                                    <?php
                                                    if (strlen($bestellingartikel->artikel->omschrijving) < 75) {
                                                        echo $bestellingartikel->artikel->omschrijving;
                                                    } else {
                                                        echo substr($bestellingartikel->artikel->omschrijving, 0, 62) . anchor('artikels/details/' . $bestellingartikel->artikel->id, '... (lees meer)', array('class' => 'leesmeer'));
                                                    }
                                                    ?>
                                                </p>
                                                <p>
                                                    <?php echo "Barcode: " . $bestellingartikel->artikel->barcode; ?>
                                                </p>
                                            </div>
                                            <div class="bottommaten">
                                                <p>
                                                    <?php
                                                    echo "Geselecteerde maat: ";
                                                    echo "<b>" . $bestellingartikel->maat->maat . "</b> ";
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </section>
                                </div>						
                                <?php if ($counter == 2 || count($bestelling->bestellingartikels) == $fullcounter + 1) { ?></div> <?php } ?>
                            <?php
                            $counter++;
                            $fullcounter++;
                            if ($counter > 2) {
                                $counter = 0;
                            }
                        }
                    }
                }
                ?>

            </article>

        </div>
    </div>
</div>
<div id="bpostBevestigingAjax"></div>
<textarea id='tocopy' style='display:none'>BE04850848035531</textarea>