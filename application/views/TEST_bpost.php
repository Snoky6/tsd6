<?php
$land = 'BE';
$bestellingId = "test-order111";
// BPOST HACK
$deliveryMethodOverrides = "Parcels depot|VISIBLE";
//$deliveryMethodOverrides = "Parcels depot|INVISIBLE&deliveryMethodOverrides=Pugo|INVISIBLE";
$hashstring = 'accountId=' . global_bpostid . '&action=START&customerCountry=' . $land . '&deliveryMethodOverrides='. $deliveryMethodOverrides . '&orderReference=' . $bestellingId . '&' . global_bpostww;
$hash = hash('sha256', $hashstring);
$data["hash"] = $hash;

$deliveryMethodOverridesForJava = $deliveryMethodOverrides;
            if ($deliveryMethodOverrides != "") {
                $deliveryMethodOverridesForJava = "['Parcels depot|INVISIBLE', 'Pugo|INVISIBLE']";
            }
           $deliveryMethodOverridesForJava;
?>

<html>
    <head><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <script src="https://shippingmanager.bpost.be/ShmFrontEnd/shm.js"></script>
        <script>function loadShm() {
                SHM.open({
                    integrationType: 'FULLSCREEN',
                    parameters: {
                        accountId: '<?php echo global_bpostid; ?>',
                        action: 'START',
                        checksum: '<?php echo $hash; ?>',
                        customerCountry: 'BE',
                        deliveryMethodOverrides: <?php if ($deliveryMethodOverrides == "Parcels depot|VISIBLE") { echo "'" . $deliveryMethodOverrides . "'"; } else { echo $deliveryMethodOverrides; } ?>,
                        orderReference: 'test-order111',
                    }
                });
            }</script>
    </head><body>
        <div><input type="button" onclick="loadShm();" value="Load fullscreen"></div>
    </body></html>