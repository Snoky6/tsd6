<script>
    bestellingid = <?php echo $bestellingid; ?>;
    function loadShm() {
        SHM.open({
            integrationType: 'INLINE',
            inlineContainerId: 'shm-inline-container',
            parameters: {
                accountId: '<?php echo $bpostid; ?>',
                orderReference: '<?php echo $bestellingid; ?>',
                customerCountry: '<?php echo $land; ?>',
                deliveryMethodOverrides: <?php if ($deliveryMethodOverrides == "Parcels depot|VISIBLE") { echo "'" . $deliveryMethodOverrides . "'"; } else { echo $deliveryMethodOverrides; } ?>,
                orderWeight: <?php echo $weight; ?>,
                checksum: '<?php echo $hash ?>',
                customerEmail: '<?php echo $email; ?>',
                cancelUrl: '<?php echo base_url() . "index.php/winkelmandje/bestellingstoppenbpost/" . $bestellingid; ?>',
                confirmUrl: '<?php
if ($deliveryMethodOverrides == "Parcels depot|VISIBLE") {
    echo base_url() . "index.php/winkelmandje/bestellingplaatsenbpost/" . $bestellingid;
} else {
    echo base_url() . "index.php/winkelmandje/bestellingplaatsenbpost/" . $bestellingid . "/" . TRUE;
}
?>',
                extra: '<?php echo $email; ?>',
            }
        });
    }

    $(document).ready(function () {
        setTimeout(function () {
            loadShm();
        }, 100);
    });
    function bevestigbpostbestelling(bestellingid) {
        $.ajax({type: "GET",
            url: site_url + "/winkelmandje/bevestigbpostbestelling",
            data: {bestellingid: bestellingid},
            success: function (result) {
                window.location = result;
            }
        });
    }

</script>
<style>
    iframe {
        width: 100% !important;
    }
</style>

<div id="bpost-wrapper">
    <div id="shm-inline-container" class="hidden" style="width: 100%; height: 700px;"></div>
</div>