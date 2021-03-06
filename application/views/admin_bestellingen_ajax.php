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