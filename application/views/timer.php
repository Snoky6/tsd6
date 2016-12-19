<script>

    // shortcut voor gemak
    $(document).keypress("enter", function (e) {
        if (e.ctrlKey)
            window.location.href = site_url + "/admin";
    });
      


</script>
<link rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>css/countdown/reset.css">
<link rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>css/countdown/style.css">
<link rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>fonts/stylesheet.css">

<script src="<?php echo base_url() . APPPATH; ?>js/modernizr.custom.js"></script>
<!-- Countdown timer and other animations -->
<script src="<?php echo base_url() . APPPATH; ?>js/jquery.countdown.js"></script>
<script src="<?php echo base_url() . APPPATH; ?>js/script.js"></script>

<!-- Banner -->
<div id="banner-wrapper">
    <div id="banner" class="box container">
        <div class="row">
            <div class="12u">
                <h2><?php echo global_bedrijfsnaam; ?> webshop</h2>                
                <?php
                foreach ($teksten as $tekst) {
                    if ($tekst->naam == "Timer tekst") {
                        echo '<p style="font-size:' . $tekst->tekstgrootte . '%">' . $tekst->tekst . '</p>';
                    }
                }
                ?>        
            </div>            
        </div>
        <div class="row">            
            <div class="12u">
                <center>
                    <div id="counter"></div>
                </center>
                
            </div>             
        </div>
    </div>
</div>
