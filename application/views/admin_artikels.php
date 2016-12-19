<script>
    var key_count_global = 0;
    //zoekArtikel();
    function zoekArtikel() {
        key_count_global++;
        setTimeout("lookup(" + key_count_global + ")", 1000);
    }
    function lookup(key_count) {
        var input = $("#zoekartikel").val();
        if (key_count == key_count_global && input != "") {
            var input = $("#zoekartikel").val();
            $.ajax({type: "GET",
                url: site_url + "/admin/searchArtikelsByInput",
                data: {input: input},
                success: function (result) {
                    $("#artikels").html(result);
                }
            });
        }
    }

</script>

<style>
    .bigbanner
{
    padding: 4.5em;
    padding-bottom: 0.5em;
}

.bigbanner h2
{
    font-size: 3.5em;
    margin: 0.1em 0 0.35em 0;
}

.bigbanner p
{
    font-size: 2.75em;dd
    line-height: 1.35em;
    margin: 0;
}
</style>
<!-- Banner -->
<div id="banner-wrapper">
    <?php
    // message artikel bewerkt
    if (isset($bewerkt)) {
        echo "<div class='toegevoegd'><p>" . $bewerkt . "</p></div>";
    }
    ?>
    <div id="" class="box container">        
        <div class="row">
            <div class="12u bigbanner">
                <h2>Admin artikels</h2>
                <p>Alle niet gearchiveerde artikels zijn weergegeven</p>                
            </div>            
        </div>
        <div class="row">
            <div class="12u">               
                <input type="text" placeholder="Zoek een artikel op barcode of naam" id="zoekartikel" class="zoekartikelhome" onkeyup="zoekArtikel()" />
            </div>            
        </div>
       
        <div class="" id="artikels">

        </div>
        
    </div>    
</div>
<!-- Features -->
<!--<div id="features-wrapper">
    <div class="container" id="artikels">

    </div>
</div>-->
