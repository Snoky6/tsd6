<!-- Main -->
<div id="banner-wrapper">
    <div id="" class="box container">
        <div class="row">

            <!-- Content -->
            <article>
                <h2>Over <?php echo global_bedrijfsnaam; ?></h2>

                <p><?php
                    foreach ($teksten as $tekst) {
                        if ($tekst->naam == "Over ons tekst") {
                            echo $tekst->tekst;
                        }
                    }
                    ?>
                </p>
            </article>

        </div>
    </div>
