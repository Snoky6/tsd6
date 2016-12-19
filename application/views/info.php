<!-- Main -->
<div id="banner-wrapper">
    <div id="" class="box container">
        <div class="row">

            <!-- Content -->                           
            <!--<div id='mainCenter'>-->            
                <div class="4u">
                    <h3>Locatie</h3>
                    <?php
                    foreach ($teksten as $tekst) {
                        if ($tekst->naam == "Locatie") {
                            echo $tekst->tekst;
                        }
                    }
                    ?>


                </div>
                <div class="5u">
                    <h3>Bedrijfsinformatie</h3>
                    <?php
                    foreach ($teksten as $tekst) {
                        if ($tekst->naam == "Bedrijfsinformatie") {
                            echo $tekst->tekst;
                        }
                    }
                    ?>
                    <a href="<?php echo base_url() . APPPATH; ?>files/voorwaarden.pdf">Klik hier om de algemene voorwaarden te bekijken.</a><br/>
                    <a href="<?php echo base_url() . APPPATH; ?>files/GEGEVENSBESCHERMING.pdf">Klik hier als u meer wilt weten over gegevensbescherming.</a>
                </div>
                <div class="3u">
                    <h3>Openingsuren</h3>
                    <?php
                    foreach ($teksten as $tekst) {
                        if ($tekst->naam == "Contact openingsuren") {
                            echo $tekst->tekst;
                        }
                    }
                    ?>
                </div>
            <div class="12u">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d80478.56929976399!2d5.243425833169276!3d50.92454405183552!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c12183ded75db7%3A0xf7cb7b027e7e2181!2sHasselt!5e0!3m2!1snl!2sbe!4v1479406651987" width="100%" height="450" frameborder="0" style="border:0; margin-top:20px;"></iframe>
            </div>
            <!--</div>-->


        </div>
    </div>
