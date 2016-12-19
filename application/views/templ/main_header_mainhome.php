<style>
    .homeimage {
        background: url(<?php echo base_url() . APPPATH ?>images/mainhomebg.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
</style>

<body class="homepage homeimage">   
    <div id="page-wrapper">
        <!-- Header -->
        <div id="header-wrapper">
            <header id="header" class="container">
                <!--<div style="padding-top:4em;">
                    <?php echo anchor('welcome/index', '<img src="' . base_url() . APPPATH . 'images/logo_bloem.png" class="2u" />'); ?>
                </div>-->
                <div id="logo" class="mainhomelogo" style="padding-top:15%;">							
                    <?php echo anchor('welcome/index', '<img src="' . base_url() . APPPATH . 'images/logo2.png" class="8u" />'); ?>
                </div>

                <!-- Nav -->
                <!--<nav id="nav" class="mainhomenav">
                    <ul>
                        <li><?php echo anchor('welcome/index', 'Fashion', 'class="button"') ?></li>            
                        <li><?php echo anchor('welcome/beauty', 'Beauty', 'class="button"') ?></li>
                    </ul>
                </nav>-->

            </header>
        </div>	