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
<style>
    #lblCartCount {        
        background: #b1965d;
        border-radius: 50%;
        height: 15px;
        width: 15px;
        color: #fff;
        padding: 5px 10px; 
        text-indent: -1px;        
        line-height: 13px;         
    }
</style>

<body class="homepage">   
    <div id="page-wrapper">
        <!-- Header -->
        <div id="header-wrapper">
            <header id="header" class="container">            
                <div id="logo">							
                    <?php echo anchor('welcome/home', '<img src="' . base_url() . APPPATH . 'images/logo_chelsey_fashion.png" style="min-width: 300px" class="4u"/>'); ?>
                </div>

                <!-- Nav -->
                <nav id="nav">
                    <ul>
                        <li <?php
                        if ($pagina == "Home") {
                            echo 'class="current"';
                        }
                        ?>><?php echo anchor('welcome/index', 'Home'); ?></li>  
                           
                        <li <?php
                            if ($pagina == "Artikels") {
                                echo 'class="current"';
                            }
                        ?>>
                            <a href="">Categorieën</a>
                            <ul>                                
                                <?php
                                foreach ($categorien as $categorie) {
                                    echo '<li>';
                                    if ($categorie->hoofdcategorieId == null) {

                                        if ($categorie->subcategorien != null) {
                                            echo "<a href=''>" . $categorie->naam . "</a>";
                                            echo '<ul>';
                                            foreach ($categorie->subcategorien as $subcategorie) {
                                                echo '<li>';
                                                echo anchor('artikels/categorie/' . strtolower($subcategorie->id), $subcategorie->naam);
                                                echo '</li>';
                                            }
                                            echo '</ul>';
                                        } else {
                                            echo anchor('artikels/categorie/' . strtolower($categorie->id), $categorie->naam);
                                        }
                                    }
                                    echo '</li>';
                                }
                                ?>										
                            </ul>
                        </li>

                        <li <?php
                            if ($pagina == "Info" || $pagina == "Over ons" || $pagina == "FAQ") {
                                echo 'class="current"';
                            }
                                ?>>
                            <a href="">Info</a>
                            <ul>
                                <li><?php echo anchor('info/overons', 'Over ons'); ?></li>
                                <li><?php echo anchor('info/index', 'Contact'); ?></li>
                                <li><?php echo anchor('info/faq', 'FAQ'); ?></li>
                            </ul>
                        </li>

                        <li <?php
                                if ($pagina == "Winkelmandje") {
                                    echo 'class="current"';
                                }
                                ?>><?php
                                echo anchor('winkelmandje/index', '&nbsp;', 'class="fa fa-shopping-cart"');?></li>

                        <li <?php
                                if ($pagina == "Winkelmandje") {
                                    echo 'class="current"';
                                }
                                ?>><?php                                
                            $karretje = $this->session->userdata('karretje');
                            if ($karretje != null) {
                                if (count($karretje) < 10) {
                                    echo '<label id="lblCartCount" style="z-index: -20;">' . count($karretje) . '</label>';
                                } else {
                                    echo '<label id="lblCartCount" style="z-index: -20;">...</label>';
                                }
                            }
                            ?></li>


                        <!--<li <?php
                        if ($pagina == "Over ons") {
                            echo 'class="current"';
                        }
                        ?>><?php echo anchor('info/overons', 'Over ons'); ?></li>
                        <li <?php
                        if ($pagina == "Info") {
                            echo 'class="current"';
                        }
                        ?>><?php echo anchor('info/index', 'Contact'); ?></li>
                        <li <?php
                        if ($pagina == "Winkelmandje") {
                            echo 'class="current"';
                        }
                        ?>><?php echo anchor('winkelmandje/index', '&nbsp;', 'class="fa fa-shopping-cart"'); ?></li>  
                        <li <?php
                        if ($pagina == "FAQ") {
                            echo 'class="current"';
                        }
                        ?>><?php echo anchor('info/faq', 'FAQ'); ?></li>-->
                    </ul>
                </nav>

            </header>
        </div>	
