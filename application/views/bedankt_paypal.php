<script>
    $(document).ready(function () {
        $("#paypalpay").click();
    });    
    
</script>

<!-- Main -->
<div id="main-wrapper">
    <div class="container">
        <div id="content">

            <!-- Content -->
            <article>

                <h2>Bedankt!</h2>
                <p>U wordt doorverwezen naar paypal om hier uw betaling af te ronden.</p><br/>        
                <div style="display:none;" id="paypalform">
                    <form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                        <input type="hidden" name="cmd" value="_xclick">
                        <input type="hidden" name="business" value="webshop@dulani.be">
                        <input type="hidden" name="currency_code" value="EUR">
                        <input type="hidden" name="NOSHIPPING" value="1">
                        <input type="hidden" name="no_shipping" value="1">
                        <input type="hidden" value="http://www.dulani.be/index.php/winkelmandje/bestellingplaatsenpaypal" name="return">
                        <input type="hidden" name="item_name" value="Dulani webshop artikels: <?php echo $artikelString;//echo $this->session->userdata('artikelString'); ?>">
                        <input type="hidden" name="amount" value="<?php echo $totaalprijs; //echo $this->session->userdata('totaalprijs'); ?>">
                        <input type="image" id="paypalpay" src="http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
                    </form>
                </div>
            </article>
        </div>
    </div>
</div>