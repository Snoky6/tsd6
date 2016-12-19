<script src="<?php echo base_url() . APPPATH; ?>js/bpost.js"></script>
<script>
function loadShm() {
        SHM.open({   
            integrationType: 'INLINE',
            inlineContainerId: 'shm-inline-container',
            parameters: {
                accountId: '<?php echo $bpostid; ?>',
                action: 'CONFIRM',
                customerCountry: '<?php echo $customerCountry; ?>',
                orderReference: '<?php echo $orderReference; ?>',                 
                checksum: '<?php echo $hash; ?>'                           
            }
        });
        
    }
    
    $(document).ready(function () {
        setTimeout(function () {
            loadShm();
        }, 500);
    });
</script>

<div id="bpost-wrapper">
    <div id="shm-inline-container" class="" style="width: 100%; height: 700px;"></div>
</div>