<script src="<?php echo base_url() . APPPATH; ?>editor/ckeditor.js"></script>
<script>
function deleteFAQ(id) {
        $.ajax({type: "GET",
            url: site_url + "/admin/deletefaqbyid",
            data: {id: id},
            success: function (result) {                
                $("#faq" + id).fadeTo("slow", 0.1, function () {
                    // Animation complete.
                    $("#faq" + id).remove();
                });
            }
        });
    }
</script>

<style>
    p {
        margin-bottom: 0px !important;
    }
</style>
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
                   <div>
                <?php echo anchor('admin/faq_toevoegen', '&nbsp;Extra FAQ toevoegen', array('class' => 'button icon fa-plus-circle')); ?>
            </div>
            <br/><br/>
            <?php
            foreach ($faqs as $faq) {

                echo "<div style='position: relative; float: none !important; margin-bottom: 15px;' id='faq$faq->id'>";
                echo "<div style='position: absolute; top: 0; right: 0; text-align: right;'><img src='" . base_url() . APPPATH . "/images/icons/delete.png' onclick='deleteFAQ($faq->id);' name='delete' style='width: 30px; height: 30px; cursor: pointer;' />";
                echo "</div>";
                echo "<h3>" . $faq->vraag . "</h3>";
                echo "" . $faq->antwoord . "";
                echo "</div>";
            }
            ?>                   
                
            </article>

        </div>
    </div>
</div>