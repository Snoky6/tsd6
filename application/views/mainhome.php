<style>
    .mainhome-ul a {
        background-color: black;
        color: white;
        border: 1px solid #b1965d;
    }

    .mainhome-ul a:hover {
        background-color: #fff;
        color: black;
        border: 1px solid #b1965d;
    }

    .center-content {
        width: 100%;
        text-align: center;
        margin: auto;
    }

    .smallbutton li a{
        padding-top: 0.1em;
        padding-bottom: 0.1em;
    }

    .nav {
        font-size: 1.1em;
        width: 100%;
        text-transform: uppercase;
        text-align: center;
        color: white;
    }

    .nav li{
        display: inline;
        //padding-left: 1.5em;
        padding-bottom: 1em;
    }
</style>
<!-- Banner -->
<div id="banner-wrapper">
    <div id="" class="container">
        <div class="row">
            <div class="center-content nav">
                <ul class="mainhome-ul" >
                    <li><?php echo anchor('welcome/index', 'Webshop', 'class="button"') ?></li>            
                    <li><?php echo anchor('welcome/beauty', 'Beauty', 'class="button"') ?></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="center-content" style="padding-top:1em;">
                <?php echo anchor('info/index', '<img src="' . base_url() . APPPATH . 'images/logo_bloem_500.png" class="1u" style="margin: auto; display: block; min-width: 100px;" />'); ?>
                <ul class="mainhome-ul smallbutton">
                    <li style="font-size: 150%; color: black; -webkit-text-stroke: 0.1px white;">More about us?</li>
                    <li><?php echo anchor('info/index', 'CONTACT', 'class="button"') ?></li>                    
                </ul>
            </div>
        </div>
    </div>
</div>