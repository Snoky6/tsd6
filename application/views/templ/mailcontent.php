<?php ?>
<html>
    <head> 
    </head>
    <body style="background-color: #F2F2F2">
        <div style="margin-left: auto; margin-right: auto; width: 1200px; padding: 4.5em 0 1em 0;">           
            <header style="position: relative; z-index: 10000;">
                <!-- Logo -->
                <!--<img src="http://buromas-stempels.be/dulani/application/images/logo.png" alt="" style="padding-right: 30px; z-index: 100000;width:150px;" align="left" />-->
                <div id="logo">							
                    <img src="http://www.dulani.be/application/images/logo2.png" width="250" alt="" style="padding-left: 50px; padding-bottom: 20px; overflow: hidden;" class="" align="left" />
                </div>
            </header>
        </div>
        <div style="margin-left: auto; margin-right: auto; width: 1200px; position: relative;">
            <div style="position: relative; background: #fff; border-radius: 6px; box-shadow: 0px 0px 4px 0px rgba(0,0,0,0.015); box-shadow: 0px 3px 0px 0px rgba(0,0,0,0.05); padding:50px;color:#333;">
                <h2 style="clear:both;font-size: 2.8em; margin: 0.1em 0 0.35em 0;color: #444; font-weight: 800;line-height: 1em; display: block;">Dulani webshop</h2>
                <p style="clear:both;font-size: 1.85em;line-height: 1.35em;margin: 0;  display: block;">Beste <?php echo $naam ?></p>              
                <p style="clear:both;font-size: 1.3em;line-height: 1.15em;margin: 0;  display: block;"><?php echo $bericht ?></p>
                <br>
                <p style="clear:both;font-size: 1.3em;line-height: 1.15em;margin: 0;  display: block;">Met vriendelijke groeten<br/>Het <?php echo global_bedrijfsnaam; ?>-team</p>
            </div>
        </div>
    </body>
</html>