<div id="banner-wrapper" style='padding-bottom: 20px;'>
    <div id="banner" class="box container">
        <div class="row" >
            <div class="12u">
                <h2>Dulani webshop</h2>
                <p><?php echo $pagina; ?></p>
            </div>

        </div>
    </div>
</div>

<?php echo form_open_multipart('admin/testupload'); ?>



<?php
$data = array('name' => 'userfile[]', 'id' => 'userfile', 'accept' => 'image/*', 'style' => 'style="float:left;', 'multiple' => '');
echo form_upload($data);
?>


<?php
echo form_submit('submit', 'upload!');

echo form_close();
?>

