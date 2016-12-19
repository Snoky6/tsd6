<!-- Banner -->
<script type="text/javascript">
    var outfitId = <?php echo $outfitId; ?>


    function checkForMultiplePhotos() {
        var files = $(".multiphoto").val();

        if (files === '') {
            $(".multiphoto").remove();
        }
        $("#blurry").fadeIn("slow", function () {
            // Animation complete.

        });
    }

    $(document).ready(function () {
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                if (!(event.shiftKey)) {
                    event.preventDefault();
                    return false;
                }
            }
        });


        loadOutfitArtikels();
    });

    var key_count_global = 0;
    //zoekArtikel();
    function zoekArtikel() {
        key_count_global++;
        setTimeout("lookup(" + key_count_global + ")", 1000);
    }
    function lookup(key_count) {
        var input = $("#zoekartikel").val();
        if (key_count == key_count_global && input != "") {
            $.ajax({type: "GET",
                url: site_url + "/admin/searchArtikelsByInput/outfits",
                data: {input: input},
                success: function (result) {
                    $("#artikels").html(result);
                }
            });
        }
    }

    function voegArtikelToeAanOutfit(id) {
        var artikelId = id;

        $.ajax({type: "GET",
            url: site_url + "/admin/voegArtikelToeAanOutfit",
            data: {artikelId: artikelId, outfitId: outfitId},
            success: function (result) {
                $("#outfitartikels").html(result);
            }
        });
    }

    function verwijderArtikelVanOutfit(id) {
        var outfitArtikelId = id;

        $.ajax({type: "GET",
            url: site_url + "/admin/verwijderArtikelVanOutfit",
            data: {outfitArtikelId: outfitArtikelId},
            success: function (result) {
                $("#gekozenOutfitArtikel" + outfitArtikelId).fadeTo("slow", 0.1, function () {
                    // Animation complete.
                    $("#gekozenOutfitArtikel" + outfitArtikelId).remove();
                });
            }
        });
    }

    function loadOutfitArtikels() {
        $.ajax({type: "GET",
            url: site_url + "/admin/loadOutfitArtikels",
            data: {outfitId: outfitId},
            success: function (result) {
                $("#outfitartikels").html(result);
            }
        });
    }

    function deleteExtraOutfitFoto(extraFotoId) {
        $.ajax({type: "GET",
            url: site_url + "/admin/deleteextraoutfitfoto",
            data: {id: extraFotoId},
            success: function (result) {
                $("#extraFoto" + extraFotoId).fadeTo("slow", 0.1, function () {
                    // Animation complete.
                    $("#extraFoto" + extraFotoId).remove();
                });
            }
        });
    }

</script>

<style>
    .alwaysVisibleSaveBox {    
        background-color: rgba(255,255,255,0.6);
        position: fixed;
        bottom: 5%;
        right: 5%;        
        border-radius: 25px;
        border: 1px solid white;
        margin-left: auto;
        margin-right: auto;
        color: white;        
        text-align: center;
        padding: 20px;
    }

    .alwaysVisibleSaveBox p{
        padding: 10px;
        margin: 0px;
    }
</style>
<div id="banner-wrapper">
    <?php
    // message outfit toegevoegd
    if (isset($toegevoegd)) {
        echo "<div class='toegevoegd'><p>" . $toegevoegd . "</p></div>";
    }
    ?>
    <div id="banner" class="box container">
        <div class="row">
            <div class="12u">
                <h2>Admin Panel</h2>
                <p><?php
                    if (isset($outfit)) {
                        echo 'Bewerking ' . $outfit->naam;
                    } else {
                        echo 'Maak nieuwe outfit aan!';
                    }
                    ?>  </p>                                                       
                <?php
                if (isset($outfit)) {
                    echo form_open_multipart('admin/bewerkteoutfitopslaan');
                } else {
                    echo form_open_multipart('admin/nieuweoutfit');
                }
                ?>

                <div class="alwaysVisibleSaveBox" id="alwaysVisibleSaveBox">
                    <?php
                    $js = "onclick='checkForMultiplePhotos();'";
                    if (isset($outfit)) {
                        echo form_submit('submit', 'Sla de wijzigingen op!', $js);
                    } else {
                        
                    }
                    ?>
                </div>

                <table border="0">           
                    <tr>                    
                        <td><?php echo form_label('Naam*: ', 'naam'); ?></td>
                        <td colspan="3">
                            <?php
                            $data = array('name' => 'naam', 'id' => 'naam', 'placeholder' => 'Naam', 'required' => 'required', 'value' => (isset($outfit)) ? $outfit->naam : '');
                            echo form_input($data);
                            ?>
                        </td>                          
                    </tr>                    
                    <tr>                        
                        <td><?php echo form_label('Foto: ', 'userfile'); ?></td>
                        <td colspan="7">
                            <?php
                            if (isset($outfit)) {
                                echo '<img src="' . base_url() . APPPATH . $outfit->imagePath . '" alt="" width="300px" style="cleat:both;" /><br/>';
                            }
                            ?>                            
                            <?php
                            $data = array('name' => 'userfile[]', 'id' => 'userfile', 'accept' => 'image/*', 'style' => 'style="float:left;');
                            echo form_upload($data);
                            ?>
                        </td>                        
                    </tr>
                    <tr>                        
                        <td><?php echo form_label('Extra foto\'s: ', 'userfile'); ?></td>
                        <td colspan="7">
                            <?php
                            if (isset($outfit->extraFotos)) {
                                foreach ($outfit->extraFotos as $foto) {
                                    echo '<img src="' . base_url() . APPPATH . $foto->imagePath . '" alt="" width="170px" title="verwijder deze foto" style="cursor:pointer; padding: 5px;" onclick="deleteExtraOutfitFoto(' . $foto->id . ')" id="extraFoto' . $foto->id . '" />';
                                }
                                echo "<br/>";
                            }
                            ?>                            
                            <?php
                            $data = array('name' => 'userfileextra[]', 'id' => 'userfileextra', 'accept' => 'image/*', 'style' => 'style="float:left;', 'multiple' => '', 'class' => 'multiphoto');
                            echo form_upload($data);
                            ?>
                        </td>                        
                    </tr>
                    <tr>                        
                        <td><?php echo form_label('Archiveer: ', 'archief'); ?></td>
                        <td colspan="">                                                      
                            <?php
                            $data = array('name' => 'archief', 'id' => 'archief', 'value' => 'true');
                            echo form_checkbox($data);
                            ?>
                        </td> 
                    </tr>
                    <tr>
                        <td><?php echo form_label('Omschrijving: ', 'omschrijving'); ?></td>
                        <td colspan="7">
                            <?php
                            $data = array('name' => 'omschrijving', 'id' => 'omschrijving', 'cols' => '50', 'rows' => '6', 'placeholder' => 'Vul hier de omschrijving in.', 'value' => (isset($outfit)) ? $outfit->omschrijving : '');
                            echo form_textarea($data);
                            ?> 
                        </td>                        
                    </tr>
                </table>

                <h3>Gekozen artikels</h3>
                <div id="outfitartikels">

                </div>

                <!-- artikels laden voor te selecteren -->
                <h3>Zoek artikels om aan de outfit toe te voegen</h3>
                <div class="row">
                    <div class="12u">               
                        <input type="text" placeholder="Zoek een artikel op barcode of naam" id="zoekartikel" class="zoekartikelhome" onkeyup="zoekArtikel()" />
                    </div>
                </div>
                <br>
                <?php
                $js = "onclick='checkForMultiplePhotos();'";
                if (isset($outfit)) {
                    echo form_hidden('bewerkteoutfitid', $outfit->id);
                    echo form_submit('submit', 'Sla de wijzigingen op!');
                } else {
                    echo form_submit('submit', 'Maak nieuwe outfit aan!', $js);
                }
                echo form_close();
                ?>
            </div>            
        </div>        
    </div>
</div>

<div id="features-wrapper">
    <div class="container">

        <h3>Gezochte artikels</h3>
        <div id="artikels">

        </div>
    </div>
</div>