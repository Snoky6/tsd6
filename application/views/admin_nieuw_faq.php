<!-- Main -->
<script src="<?php echo base_url() . APPPATH; ?>editor/ckeditor.js"></script>
<?php
// message tekst aangepast
if (isset($bewerkt)) {
    echo "<div class='toegevoegd'><p>" . $bewerkt . "</p></div>";
}
?>
<div id="main-wrapper">    
    <div class="container">
        <div id="content">

            <!-- Content -->
            <article>

                <h2>Admin FAQ</h2>                    
                    <?php echo form_open('admin/faqtoevoegen'); ?>
                <h3>De vaak gestelde vraag:</h3>
                    <?php
                        $data = array('name' => 'vraag', 'id' => 'vraag', 'cols' => '50', 'rows' => '6', 'placeholder' => 'Vul hier je vraag in.');
                        echo form_textarea($data);
                        echo "<br/>";
                        echo "<script>CKEDITOR.replace('vraag');</script>";
                        echo "<br/>";
                        ?>
                <h3>Antwoord:</h3>
                    <?php
                        $data = array('name' => 'antwoord', 'id' => 'antwoord', 'cols' => '50', 'rows' => '6', 'placeholder' => 'Vul hier het antwoord in.');
                        echo form_textarea($data);
                        echo "<br/>";
                        echo "<script>CKEDITOR.replace('antwoord');</script>";
                        echo "<br/>";
                        ?>
                    <?php
                    echo form_submit('submit', 'Bewaar gegevens!');
                    echo form_close();
                    ?>                      
                
            </article>

        </div>
    </div>
</div>