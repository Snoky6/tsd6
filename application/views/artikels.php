<script>
/* Code needed to show popup (CSS and JS also required) */
    $(document).ready(function () {
        $('.popup-with-zoom-anim').magnificPopup({
            type: 'inline',
            fixedContentPos: false,
            fixedBgPos: true,
            overflowY: 'auto',
            closeBtnInside: true,
            preloader: false,
            midClick: true,
            removalDelay: 300,
            mainClass: 'my-mfp-zoom-in'
        });
    });
    
    function showSmallCartPopup(id) {
        $.ajax({type: "GET",
            url: site_url + "/artikels/getMatenForCartPopup",
            data: {id: id},
            success: function (result) {               
                $("#small-dialog").html(result);
                $(".popup-with-zoom-anim").click();
            }
        });

    }
</script>

<style>
    .homeSmallCart i {
        font-size: 130%;
        position: absolute;
        bottom: 15px;
        right: 15px;
        padding: 12px;
        width: 45px;
        height: 45px;
        color: #fff;
        /*background-color: rgba(230,29,128,0.8);*/
        background-color: rgba(0,0,0,0.1);
        box-shadow: 0 0 5px 1px rgba(0,0,0,0.15);
        transition: background-color .25s ease-in-out;        
        border-radius: 50%;
    }

    .homeSmallCart i:hover {        
        color: #fff;
        /*background-color: rgba(0,144,197,0.8);*/
        background-color: rgba(230,29,128,0.8);
        transition: background-color .25s ease-in-out;        
    }

    .clickable {
        cursor: pointer;
    }
</style>

<style>    
    /* popup */
    /* Styles for dialog window */
    #small-dialog {
        background: white;
        padding: 20px 30px;
        text-align: left;
        max-width: 400px;
        min-width: 50%;
        max-height: 2000px;
        margin: 40px auto;
        position: relative;
    }


    /**
     * Fade-zoom animation for first dialog
     */

    /* start state */
    .my-mfp-zoom-in .zoom-anim-dialog {
        opacity: 0;

        -webkit-transition: all 0.2s ease-in-out; 
        -moz-transition: all 0.2s ease-in-out; 
        -o-transition: all 0.2s ease-in-out; 
        transition: all 0.2s ease-in-out; 



        -webkit-transform: scale(0.8); 
        -moz-transform: scale(0.8); 
        -ms-transform: scale(0.8); 
        -o-transform: scale(0.8); 
        transform: scale(0.8); 
    }

    /* animate in */
    .my-mfp-zoom-in.mfp-ready .zoom-anim-dialog {
        opacity: 1;

        -webkit-transform: scale(1); 
        -moz-transform: scale(1); 
        -ms-transform: scale(1); 
        -o-transform: scale(1); 
        transform: scale(1); 
    }

    /* animate out */
    .my-mfp-zoom-in.mfp-removing .zoom-anim-dialog {
        -webkit-transform: scale(0.8); 
        -moz-transform: scale(0.8); 
        -ms-transform: scale(0.8); 
        -o-transform: scale(0.8); 
        transform: scale(0.8); 

        opacity: 0;
    }

    /* Dark overlay, start state */
    .my-mfp-zoom-in.mfp-bg {
        opacity: 0;
        -webkit-transition: opacity 0.3s ease-out; 
        -moz-transition: opacity 0.3s ease-out; 
        -o-transition: opacity 0.3s ease-out; 
        transition: opacity 0.3s ease-out;
    }
    /* animate in */
    .my-mfp-zoom-in.mfp-ready.mfp-bg {
        opacity: 0.8;
    }
    /* animate out */
    .my-mfp-zoom-in.mfp-removing.mfp-bg {
        opacity: 0;
    }



    /**
     * Fade-move animation for second dialog
     */

    /* at start */
    .my-mfp-slide-bottom .zoom-anim-dialog {
        opacity: 0;
        -webkit-transition: all 0.2s ease-out;
        -moz-transition: all 0.2s ease-out;
        -o-transition: all 0.2s ease-out;
        transition: all 0.2s ease-out;

        -webkit-transform: translateY(-20px) perspective( 600px ) rotateX( 10deg );
        -moz-transform: translateY(-20px) perspective( 600px ) rotateX( 10deg );
        -ms-transform: translateY(-20px) perspective( 600px ) rotateX( 10deg );
        -o-transform: translateY(-20px) perspective( 600px ) rotateX( 10deg );
        transform: translateY(-20px) perspective( 600px ) rotateX( 10deg );

    }

    /* animate in */
    .my-mfp-slide-bottom.mfp-ready .zoom-anim-dialog {
        opacity: 1;
        -webkit-transform: translateY(0) perspective( 600px ) rotateX( 0 ); 
        -moz-transform: translateY(0) perspective( 600px ) rotateX( 0 ); 
        -ms-transform: translateY(0) perspective( 600px ) rotateX( 0 ); 
        -o-transform: translateY(0) perspective( 600px ) rotateX( 0 ); 
        transform: translateY(0) perspective( 600px ) rotateX( 0 ); 
    }

    /* animate out */
    .my-mfp-slide-bottom.mfp-removing .zoom-anim-dialog {
        opacity: 0;

        -webkit-transform: translateY(-10px) perspective( 600px ) rotateX( 10deg ); 
        -moz-transform: translateY(-10px) perspective( 600px ) rotateX( 10deg ); 
        -ms-transform: translateY(-10px) perspective( 600px ) rotateX( 10deg ); 
        -o-transform: translateY(-10px) perspective( 600px ) rotateX( 10deg ); 
        transform: translateY(-10px) perspective( 600px ) rotateX( 10deg ); 
    }

    /* Dark overlay, start state */
    .my-mfp-slide-bottom.mfp-bg {
        opacity: 0;

        -webkit-transition: opacity 0.3s ease-out; 
        -moz-transition: opacity 0.3s ease-out; 
        -o-transition: opacity 0.3s ease-out; 
        transition: opacity 0.3s ease-out;
    }
    /* animate in */
    .my-mfp-slide-bottom.mfp-ready.mfp-bg {
        opacity: 0.8;
    }
    /* animate out */
    .my-mfp-slide-bottom.mfp-removing.mfp-bg {
        opacity: 0;
    }

</style>

<script src="<?php echo base_url() . APPPATH; ?>js/magnific.js"></script>
<link rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>css/magnific.css" />

<a class="popup-with-zoom-anim" href="#small-dialog" style="display:none;"></a>
    <!-- dialog itself, mfp-hide class is required to make dialog hidden -->
    <div id="small-dialog" class="zoom-anim-dialog mfp-hide"></div>

<div id="banner-wrapper" style='padding-bottom: 20px;'>
    <div id="" class="box container">
        <?php if (count($artikels) != 0) { ?>
                <?php
                $counter = 0;
                $fullcounter = 0;
                ?>
                <?php foreach ($artikels as $artikel) { ?>  

                    <?php if ($counter == 0) { ?> <div class="row"> <?php } ?>
                        <div class="3u">						
                            <!-- Box -->
                            <section class="box feature">
                                <?php if ($artikel->korting > 0) { ?>
                                    <div class="ribbon-wrapper-korting"><div class="ribbon-korting"><?php echo "-" . $artikel->korting . "&#37;"; ?></div></div>
                                <?php } ?>
                                <?php echo anchor('artikels/details/' . $artikel->id, '<img src="' . base_url() . APPPATH . $artikel->imagePath . '" alt="" />', array('class' => 'image featured shopimg', 'style' => 'max-height: 400px;')); ?>
                                <div class="inner" style="height: 11em;">
                                    <header>
                                        <h2><?php echo $artikel->naam ?></h2>
                                        <?php if ($artikel->korting == 0) { ?>
                                            <p style="">&euro;<?php echo number_format($artikel->prijs, 2) ?></p>
                                        <?php } else { ?>
                                            <p style=""><span style="text-decoration:line-through;">&euro;<?php echo number_format($artikel->prijs, 2) ?></span>&nbsp;&nbsp; &euro;<?php echo number_format(($artikel->prijs - ($artikel->prijs * $artikel->korting / 100)), 2) ?></p>
                                        <?php } ?>
                                    </header>
                                    <div class="omschrijving" style="display: none;">
                                        <p>
                                            <?php
                                            if (strlen($artikel->omschrijving) < 75) {
                                                echo $artikel->omschrijving;
                                            } else {
                                                echo substr($artikel->omschrijving, 0, 62) . anchor('artikels/details/' . $artikel->id, '... (lees meer)', array('class' => 'leesmeer'));
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <div class="bottommaten" style="">
                                        <p>
                                            <?php
                                            if (count($artikel->artikelMaten) > 0) {
                                                echo "Verkrijgbaar in: ";
                                                foreach ($artikel->artikelMaten as $artikelMaat) {
                                                    if ($artikelMaat->voorraad != null && $artikelMaat->voorraad != 0) {
                                                        echo "<b>" . $artikelMaat->maat->maat . "</b> ";
                                                    }
                                                }
                                            } else {
                                                echo "<b style='color:red;'>Uitverkocht!</b> ";
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <!--<div class="homeSmallCart">
                                    <i class="fa fa-shopping-cart clickable" aria-hidden="true" onclick="showSmallCartPopup(<?php echo $artikel->id; ?>)"></i>
                                </div>-->
                            </section>

                        </div>						
                        <?php if ($counter == 3 || count($artikels) == $fullcounter + 1) { ?></div> <?php } ?>
                    <?php
                    $counter++;
                    $fullcounter++;
                    if ($counter > 3) {
                        $counter = 0;
                    }
                    ?>
                <?php } ?>
                <?php
            } else {
                echo '<h3>Geen artikels gevonden</h3>';
            }
            ?>
    </div>