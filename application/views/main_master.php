<?php //error_reporting(0); @ini_set('display_errors', 0);     ?>
<!DOCTYPE HTML>
<!--
        Verti by HTML5 UP
        html5up.net | @n33co
        Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
    <head>
        <title><?php echo $title ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="K-design, Toxik3, jumpsuit, kleedje, handtas, sneaker, Amelie, js millenium, Onado, Zac & Zoe" />
        <meta name="author" content="Jeroen Vinken">
        
        <meta name="theme-color" content="#b1965d">
        <!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
        <script src="<?php echo base_url() . APPPATH; ?>js/jquery.min.js"></script>
        <script src="<?php echo base_url() . APPPATH; ?>js/jquery.dropotron.min.js" defer></script>
        <script src="<?php echo base_url() . APPPATH; ?>js/skel.min.js" defer></script>
        <script src="<?php echo base_url() . APPPATH; ?>js/skel-layers.min.js" defer></script>
        <script src="<?php echo base_url() . APPPATH; ?>js/init.js" defer></script>
        <noscript>
        <link rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>css/skel.css" />
        <link rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>css/style.css" />
        <link rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>css/style-desktop.css" />
        </noscript>
        <link rel="shortcut icon" href="<?php echo base_url() . APPPATH; ?>images/logo.ico">

        <style>
            /* Center the loader */
            #loader {
                position: relative;
                left: 50%;
                top: 50%;
                z-index: 989898;
                width: 150px;
                height: 150px;
                margin: -75px 0 0 -75px;
                border: 16px solid #d6c194;
                border-radius: 50%;
                border-top: 16px solid #b1965d; /* color Dulani?  */      
                /*border-top: 16px solid #C51200; /* color modehuis */
                /*border-top: 16px solid #C5E105; /* color implosion */   
                width: 120px;
                height: 120px;
                -webkit-animation: spin 2s linear infinite;
                animation: spin 2s linear infinite;
            }

            @-webkit-keyframes spin {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            /* Add animation to "page content" */
            .animate-bottom {
                position: relative;
                -webkit-animation-name: animatebottom;
                -webkit-animation-duration: 1s;
                animation-name: animatebottom;
                animation-duration: 1s
            }

            @-webkit-keyframes animatebottom {
                from { bottom:-100px; opacity:0 }
                to { bottom:0px; opacity:1 }
            }

            @keyframes animatebottom {
                from{ bottom:-100px; opacity:0 }
                to{ bottom:0; opacity:1 }
            }

            #myDiv {
                display: none;
                text-align: center;
            }

            #blurry {
                position: fixed;
                top:0;
                left:0;
                z-index: 9999999999999;
                width: 100%;
                height: 100%;
                background-color: rgba(255,255,255,0.97);
            }
        </style>

        <script type="text/javascript">
            var site_url = '<?php echo site_url(); ?>';
            var img_url = '<?php echo base_url() . APPPATH; ?>';
        </script>

<!--[if lte IE 8]><link rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>css/ie/v8.css" /><![endif]-->
        <script type="text/javascript">
            function scrollToShop() {
                $('#features-wrapper').scrollView();
            }
            $.fn.scrollView = function () {
                return this.each(function () {
                    $('html, body').animate({
                        scrollTop: $(this).offset().top
                    }, 1000);
                });
            };

            $(document).ready(function () {
                $("#blurry").fadeOut("slow", function () {
                    // Animation complete.
                    $("#blurry").hide();
                });
            });

            /*setTimeout(function () {
                $("#blurry").fadeOut("slow", function () {
                    // Animation complete.
                    $("#blurry").hide();
                });

            }, 800);*/

        </script>
    </head>
    <?php echo $header; ?>
    <div id="blurry"><div id="loader" class=""></div></div>
        <?php echo $content; ?>
        <?php echo $footer; ?>


</body>
</html>