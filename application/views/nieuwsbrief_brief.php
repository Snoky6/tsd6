<!-- Banner -->
<div id="banner-wrapper">
    <?php
    // message artikel toegevoegd
    if (isset($ingeschreven)) {
        echo "<div class='ingeschreven'><p>" . $ingeschreven . "</p></div>";
    }
    ?>
    <div id="banner" class="box container">
        <div class="row">
            <div class="12u">
                <h2>Nieuwsbrief</h2>
                <p>Hier komt de nieuwsbrief ...</p>
            </div>            
        </div>        
    </div>
</div>