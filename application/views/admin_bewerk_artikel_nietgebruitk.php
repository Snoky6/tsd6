<!-- Banner -->
<script type="text/javascript">
    //alert("fokeee");
    //$('datalist#cat').change(setSubCategorien());
    function setSubCategorien() {        
        var categorieNaam = $("#categorie").val();
        //alert(categorieNaam);
        $.ajax({type: "GET",
            url: site_url + "/categorie/getsubcategorie",
            data: {categorieNaam: categorieNaam},
            success: function (result) {
                $("#subcategorieResult").html(result);
            }
        });
    }
</script>
<div id="banner-wrapper">
    <?php
    // message artikel toegevoegd
    if (isset($toegevoegd)) {
        echo "<div class='toegevoegd'>" . $toegevoegd . "</div>";
    }
    ?>
    <div id="banner" class="box container">
        <div class="row">
            <div class="12u">
                <h2>Admin Panel</h2>
                <p>Voeg een artikel toe</p>                                                        
                <?php echo form_open_multipart('admin/nieuwartikel'); ?>

                <table border="0">           
                    <tr>                    
                        <td><?php echo form_label('Naam*: ', 'naam'); ?></td>
                        <td colspan="3">
                            <?php
                            $data = array('name' => 'naam', 'id' => 'naam', 'placeholder' => 'Naam', 'required' => 'required');
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
                            $data = array('name' => 'categorie', 'id' => 'categorie', 'placeholder' => 'Categorie', 'required' => 'required', 'list' => 'cat');
                            $js = 'onBlur="setSubCategorien()"';
                            echo form_input($data, '', $js);
                            //$options[0] = '-- Selecteer --';
                            foreach ($categorien as $categorie) {
                                //$options[$categorie->id] = $categorie->naam;
                            }
                            //echo form_dropdown('categorie', $options, '0');
                            ?>
                        </td>  
                    </tr>                
                    <tr>                        
                        <td><?php echo form_label('Prijs*: ', 'prijs'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'prijs', 'id' => 'prijs', 'required' => 'required', 'placeholder' => 'Prijs');
                            echo form_input($data);
                            ?>
                        </td>
                        <td><?php echo form_label('Korting: ', 'korting'); ?></td>
                        <td>
                            <?php
                            $data = array('name' => 'korting', 'id' => 'korting', 'placeholder' => 'Korting');
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
                            foreach ($maten as $maat) {
                                echo form_label($maat->maat . ': ', strtolower($maat->maat));
                                $data = array('name' => strtolower($maat->maat), 'id' => strtolower($maat->maat), 'placeholder' => '0', 'class' => 'smallinput');
                                echo form_input($data);
                            }
                            ?>
                        </td>  
                    </tr> 
                    <tr>                        
                        <td><?php echo form_label('Foto: ', 'userfile'); ?></td>
                        <td colspan="7">
                            <?php
                            $data = array('name' => 'userfile', 'id' => 'userfile', 'accept' => 'image/*');
                            echo form_upload($data);
                            ?>
                        </td>                        
                    </tr>
                    <tr>
                        <td><?php echo form_label('Omschrijving: ', 'omschrijving'); ?></td>
                        <td colspan="7">
                            <?php
                            $data = array('name' => 'omschrijving', 'id' => 'omschrijving', 'cols' => '50', 'rows' => '6', 'placeholder' => 'Vul hier de omschrijving in.');
                            echo form_textarea($data);
                            ?> 
                        </td>                        
                    </tr>
                </table>                


                <?php
                echo form_submit('submit', 'Maak nieuw artikel aan!');
                echo form_close();
                ?>
            </div>            
        </div>        
    </div>
</div>