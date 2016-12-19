<script>
    $('a.opener').on("touchstart", function (e) {
        "use strict"; //satisfy the code inspectors
        var link = $(this); //preselect the link
        if (link.hasClass('opener')) {
            return true;
        } else {
            link.addClass("opener");
            $('a.opener').not(this).removeClass("opener");
            e.preventDefault();
            return false; //extra, and to make sure the function has consistent return points
        }
    });
</script>
<body class="homepage">   

    <!-- Header -->
    <div id="header-wrapper">
        <header id="header" class="container">

            <!-- Logo -->
            <?php echo anchor('welcome/index', '<img src="' . base_url() . APPPATH . 'images/logo.png" alt="" class="logoimg" align="left" />'); ?>
            <div id="logo">							
                <?php echo anchor('welcome/index', '<img src="' . base_url() . APPPATH . 'images/logo2.png" width="250px" alt="" class="" align="left" />'); ?>
            </div>

            <!-- Nav -->
            <nav id="nav">
                <ul>
                    <li <?php if ($pagina == "Home") {
                    echo 'class="current"';
                } ?>><?php echo anchor('welcome/index', 'Home'); ?></li>                    
                    <li <?php if ($pagina == "Over ons") {
                                echo 'class="current"';
                            } ?>><?php echo anchor('info/overons', 'Over ons'); ?></li>
                    <li <?php if ($pagina == "Info") {
                                echo 'class="current"';
                            } ?>><?php echo anchor('info/index', 'Contact'); ?></li>                    
                    <li <?php if ($pagina == "FAQ") {
                                echo 'class="current"';
                            } ?>><?php echo anchor('info/faq', 'FAQ'); ?></li>
                </ul>
            </nav>

        </header>
    </div>	
