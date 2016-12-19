<h3>Top 20 artikels (niet gearchiveerd)</h3>
<ol style='margin-left: 30px; list-style: inherit; list-style: decimal !important;'>
    <?php
    foreach ($artikels as $artikel) {
        echo "<li>" . anchor('artikels/details/' . $artikel->id, $artikel->naam . " (" . $artikel->bekeken . "x bekeken)") . "</li>";
    }
    ?>
</ol>