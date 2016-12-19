<script>

    $(document).ready(function () {
        var lastchecktime = "<?php echo $this->session->userdata("lastchecktimeorders"); ?>";
        if (lastchecktime == "") {
            lastchecktime = "<?php echo date('Y-m-d H:i:s a'); ?>";
        }

        function ajax_check_for_orders() {
            $.ajax({type: "GET",
                url: site_url + "/admin/checkneworders",
                data: {lastchecktime: lastchecktime},
                success: function (result) {
                    var splitArray = result.split("[split]");
                    if (splitArray.length > 0) {
                        if (splitArray.length > 1) {
                            console.log("bestellingen gevonden " + result);
                            /* show popup with links */
                            if ($("#small-dialog").is(":visible")) {
                                /* POPUP is al open, regel toevoegen */
                                $("#popupHead").html(splitArray[2]);
                                $("#popupBody").append(splitArray[1]);
                            } else {
                                $("#small-dialog").html(splitArray[2] + "<div id='popupBody'>" + splitArray[1] + "</div>");
                                $(".popup-with-zoom-anim").click();
                            }
                            /* Sound a small alert as notification */
                            var audioElement = document.createElement('audio');
                            audioElement.setAttribute('src', "<?php echo base_url() . APPPATH; ?>sounds/order_notification.mp3");
                            audioElement.play();
                        }
                        lastchecktime = splitArray[0];
                        console.log("Last checktime: " + lastchecktime);
                    }

                }
            });
        }

        var interval = 1000 * 60 * 0.5; // where X is your every X minutes

        /* Start function once, after that let the setInterval do its magic */
        ajax_check_for_orders();
        /* Use the interval to check for messages */
        setInterval(ajax_check_for_orders, interval);
    });


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

</script>
<style>    
    /* popup */
    /* Styles for dialog window */
    #small-dialog {
        background: white;
        padding: 20px 30px;
        text-align: left;
        max-width: 400px;
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

<body class="homepage">
    <a class="popup-with-zoom-anim" href="#small-dialog" style="display:none;"></a>
    <!-- dialog itself, mfp-hide class is required to make dialog hidden -->
    <div id="small-dialog" class="zoom-anim-dialog mfp-hide"></div>

    <!-- Header -->
    <div id="page-wrapper">
        <!-- Header -->
        <div id="header-wrapper">
            <header id="header" class="container">    

            <div id="logo">							
                <?php echo anchor('welcome/index', '<img src="' . base_url() . APPPATH . 'images/logo2.png" width="400px" />'); ?>
            </div>

            <!-- Nav -->
            <nav id="nav">
                <ul>
                    <li <?php
                    if ($pagina == "Admin - Algemeen") {
                        echo 'class="current"';
                    }
                    ?>><?php echo anchor('admin/algemeen', 'Algemeen'); ?></li>

                    <li <?php
                    if ($pagina == "Admin - Artikels" || $pagina == "Admin - Nieuw artikel") {
                        echo 'class="current"';
                    }
                    ?>>
                        <a href="">Artikels</a>
                        <ul>
                            <li><?php echo anchor('admin/nieuwartikelpage', 'Nieuw artikel'); ?></li>
                            <li><?php echo anchor('admin/artikels', 'Bestaande artikels'); ?></li>
                            <li><?php echo anchor('admin/stockchanges', 'Stock verandering'); ?></li>                     
                        </ul>
                    </li>                    
                    <li <?php
                    if ($pagina == "Admin - FAQ") {
                        echo 'class="current"';
                    }
                    ?>>
                        <a href="">FAQ</a>
                        <ul>
                            <li><?php echo anchor('admin/faq', 'FAQ\'s beheren'); ?></li>
                            <li><?php echo anchor('admin/faq_toevoegen', 'FAQ toevoegen'); ?></li>
                                           
                        </ul>
                    </li>                         
                       
                    <li <?php
                    if ($pagina == "Admin - Bestellingen") {
                        echo 'class="current"';
                    }
                    ?>><?php echo anchor('admin/bestellingen', 'Bestellingen'); ?></li>

                </ul>
            </nav>

        </header>
    </div>	