<!-- Banner -->
<script type="text/javascript">

    function setSubCategorien() {
        var categorieNaam = $("#categorie").val();
        $.ajax({type: "GET",
            url: site_url + "/categorie/getsubcategorieadmin",
            data: {categorieNaam: categorieNaam},
            success: function (result) {
                $("#subcategorieResult").html(result);
            }
        });
    }

    function checkIfCodeExists() {
        var barcode = $("#barcode").val();
        $.ajax({type: "GET",
            url: site_url + "/admin/checkbarcode",
            data: {barcode: barcode},
            success: function (result) {
                if (result == "true") {
                    $("#barcode").css('border-color', 'green');
                    $("#barcode").css('color', 'green');
                } else {
                    $("#barcode").css('border-color', 'red');
                    $("#barcode").css('color', 'red');
                }
            }
        });
    }

    function checkForMultiplePhotos() {
        var files = $(".multiphoto").val();

        if (files === '') {
            $(".multiphoto").remove();
        }
        $("#blurry").fadeIn("slow", function () {
            // Animation complete.

        });

    }

    function deleteExtraFoto(extraFotoId) {
        $.ajax({type: "GET",
            url: site_url + "/admin/deleteextrafoto",
            data: {id: extraFotoId},
            success: function (result) {
                $("#extraFoto" + extraFotoId).fadeTo("slow", 0.1, function () {
                    // Animation complete.
                    $("#extraFoto" + extraFotoId).remove();
                });
            }
        });
    }

    $(document).ready(function () {
<?php
if (isset($artikel)) {
    echo 'setSubCategorien();';
    if ($artikel->categorie->hoofdcategorieId != null) {
        echo 'setTimeout(function(){ $("#subcategorie").val("' . $artikel->categorie->naam . '"); }, 500);';
    }
}
?>
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                if (!(event.shiftKey)) {
                    event.preventDefault();
                    return false;
                }
            }
        });
    });
</script>

<style>
    .alwaysVisibleSaveBox {    
        background-color: rgba(200,200,200,0.6);
        position: fixed;
        bottom: 5%;
        right: 5%;        
        border-radius: 25px;
        //border: 1px solid white;
        box-shadow: 0px 0px 10px 5px rgba(200,200,200,0.6);
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
    // message artikel toegevoegd
    if (isset($toegevoegd)) {
        echo "<div class='toegevoegd'><p>" . $toegevoegd . "</p></div>";
    }
    ?>    
    <div id="banner" class="box container">
        <div class="row">
            <div class="12u"> 
                <h2>Admin Panel</h2>
                <p><?php
                    if (isset($artikel)) {
                        echo 'Bewerking ' . $artikel->naam;
                    } else {
                        echo 'Maak nieuw artikel aan!';
                    }
                    ?>  </p>                                                       
                <?php
                if (isset($artikel)) {
                    echo form_open_multipart('admin/bewerktartikelopslaan');
                } else {
                    echo form_open_multipart('admin/nieuwartikel');
                }
                ?>
                <div class="alwaysVisibleSaveBox" id="alwaysVisibleSaveBox">
                    <?php
                    $js = "onclick='checkForMultiplePhotos();'";
                    if (isset($artikel)) {
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
                            $data = array('name' => 'naam', 'id' => 'naam', 'placeholder' => 'Naam', 'required' => 'required', 'value' => (isset($artikel)) ? $artikel->naam : '');
                            echo form_input($data);
                            ?>
                        </td>
                        <td><?php echo form_label('Categorie*: ', 'categorie'); ?></td>
                        <td colspan="3">
                            <datalist id="cat">
                                <?php
                                foreach ($categorien as $categorie) {
                                    echo '<option value="' . $categorie->naam . '">';
                                }
                                ?>
                            </datalist>
                            <?php
                            $val = '';
                            if (isset($artikel)) {
                                if ($artikel->categorie->hoofdcategorieId == NULL) {
                                    // is hoofdartikel
                                    $val = $artikel->categorie->naam;
                                } else {
                                    // is subartikel
                                    $val = $artikel->categorie->hoofdcategorie->naam;
                                }
                            }
                            $data = array('name' => 'categorie', 'id' => 'categorie', 'placeholder' => 'Categorie', 'required' => 'required', 'list' => 'cat', 'value' => $val);
                            $js = 'onBlur="setSubCategorien();" autocomplete="on"';
                            echo form_input($data, '', $js);
                            ?>
                        </td>  
                    </tr>                
                    <tr>                        
                        <td><?php echo form_label('Prijs*: ', 'prijs'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'prijs', 'id' => 'prijs', 'required' => 'required', 'placeholder' => 'Prijs', 'value' => (isset($artikel)) ? $artikel->prijs : '');
                            echo form_input($data);
                            ?>
                        </td>
                        <td><?php echo form_label('Korting: ', 'korting'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'korting', 'id' => 'korting', 'placeholder' => 'Korting', 'value' => (isset($artikel)) ? $artikel->korting : '');
                            echo form_input($data);
                            ?>
                        </td>
                        <td><?php echo form_label('Subcategorie: ', 'categorie'); ?></td>
                        <td>
                            <label id="subcategorieResult">
                        </td>
                    </tr>
                    <tr>                                          
                        <td><?php echo form_label('Maten*: ', 'xs'); ?></td>
                        <td colspan="7" width="100%" style="">
                            <?php
                            if (isset($artikel)) {
                                foreach ($artikel->artikelMaten as $maat) {
                                    echo form_label($maat->maat->maat . ': ', strtolower($maat->maat->maat));
                                    $data = array('name' => strtolower($maat->maat->maat), 'id' => strtolower($maat->maat->maat), 'placeholder' => '0', 'class' => 'smallinput', 'value' => $maat->voorraad);
                                    echo form_input($data);
                                }
                            } else {
                                foreach ($maten as $maat) {
                                    echo form_label($maat->maat . ': ', strtolower($maat->maat));
                                    $data = array('name' => strtolower($maat->maat), 'id' => strtolower($maat->maat), 'placeholder' => '0', 'class' => 'smallinput');
                                    echo form_input($data);
                                }
                            }
                            ?>
                        </td>  
                    </tr> 
                    <tr>                        
                        <td><?php echo form_label('Foto: ', 'userfile'); ?></td>
                        <td colspan="7">
                            <?php
                            if (isset($artikel)) {
                                echo '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" width="300px" style="clear:both;" /><br/>';
                            }
                            ?>                            
                            <?php
                            $js = 'required = "required"';
                            $data = array('name' => 'userfile[]', 'id' => 'userfile', 'accept' => 'image/*', 'style' => 'style="float:left;');
                            if (isset($artikel)) {
                                echo form_upload($data);
                            } else {
                                echo form_upload($data, null, $js);
                            }
                            ?>
                        </td>                        
                    </tr>
                    <tr>                        
                        <td><?php echo form_label('Extra foto\'s: ', 'userfile'); ?></td>
                        <td colspan="7">
                            <?php
                            if (isset($artikel->extraFotos)) {
                                foreach ($artikel->extraFotos as $foto) {
                                    echo '<img src="' . base_url() . APPPATH . $foto->imagePath . '" alt="" width="170px" title="verwijder deze foto" style="cursor:pointer; padding: 5px;" onclick="deleteExtraFoto(' . $foto->id . ')" id="extraFoto' . $foto->id . '" />';
                                }
                                echo "<br/>";
                            }
                            ?>                            
                            <?php
                            $data = array('name' => 'userfileextra[]', 'id' => 'userfileextra', 'accept' => 'image/*', 'style' => 'style="float:left;', 'multiple' => '', 'class' => 'multiphoto');
                            echo form_upload($data);
                            echo "<b style='color: red; margin-left:20px;'>Max. 3 foto's per upload</b>";
                            ?>
                        </td>                        
                    </tr>
                    <tr>                        
                        <td><?php echo form_label('Barcode: ', 'barcode'); ?></td>
                        <td colspan="">                                                      
                            <?php
                            $data = array('name' => 'barcode', 'id' => 'barcode', 'placeholder' => 'Barcode', 'value' => (isset($artikel)) ? $artikel->barcode : '', 'onkeyup' => 'checkIfCodeExists()');
                            echo form_input($data);
                            ?>
                        </td>
                        <?php if (isset($artikel)) { ?>
                            <td><?php echo form_label('Archiveer: ', 'archief'); ?></td>
                            <td colspan="">                                                      
                                <?php
                                $data = array('name' => 'archief', 'id' => 'archief', 'value' => 'true');
                                echo form_checkbox($data);
                                ?>
                            </td> 
                        <?php } ?>
                    </tr>
                    <tr>
                        <td><?php echo form_label('Omschrijving: ', 'omschrijving'); ?></td>
                        <td colspan="7">
                            <?php
                            $data = array('name' => 'omschrijving', 'id' => 'omschrijving', 'cols' => '50', 'rows' => '6', 'placeholder' => 'Vul hier de omschrijving in.', 'value' => (isset($artikel)) ? $artikel->omschrijving : '');
                            echo form_textarea($data);
                            ?> 
                        </td>                        
                    </tr>
                </table>                


                <?php
                $js = "onclick='checkForMultiplePhotos();'";
                if (isset($artikel)) {
                    echo form_hidden('bewerktartikelid', $artikel->id);
                    echo form_submit('submit', 'Sla de wijzigingen op!', $js);
                } else {
                    echo form_submit('submit', 'Maak nieuw artikel aan!', $js);
                }
                echo form_close();
                ?>
            </div>            
        </div>        
    </div>
</div>