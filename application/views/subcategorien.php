<datalist id="subcat">
    <?php
    foreach ($subcategorien as $subcategorie) {
        echo '<option value="' . $subcategorie->naam . '">';
    }
    ?>
</datalist>
<?php
$data = array('name' => 'subcategorie', 'id' => 'subcategorie', 'placeholder' => 'Subcategorie', 'list' => 'subcat', 'autocomplete' => 'on');
echo form_input($data);
?>