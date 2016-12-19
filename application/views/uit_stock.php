<?php ?>
<!-- Main -->
<div id="main-wrapper">
    <div class="container">
        <div id="content">

            <!-- Content -->
            <article>

                <h2>Oeps!</h2>
                <p>Het ziet er naar uit dat er van een artikel (<?php echo $artikel->naam . " - maat: " . $maat->maat; ?>) in uw winkelmandje ondertussen niet voldoende stock meer is!</p>
                <?php if ($aantalInStock < 1) { ?>
                    <p>Helaas, het artikel is volledig uitverkocht...</p>
                <?php } elseif ($aantalInStock == 1) { ?>
                    <p>Er is er nog slechts 1 in voorraad!</p>
                <?php } elseif ($aantalInStock > 1) { ?>
                    <p>Er zijn er nog slechts <?php echo $aantalInStock; ?> in voorraad!</p>
                <?php } ?>
            </article>

        </div>
    </div>
</div>