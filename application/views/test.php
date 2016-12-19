<!-- Banner -->
<div id="banner-wrapper">
    <div id="banner" class="box container">
        <div class="row">
            <div class="8u">
                <h2>Dulani webshop</h2>
                <p>Jouw winkelmandje!</p>                                                        
                <?php
                //echo $artikel->naam .  " met maatid:" . $maatid . ", met maat:" . $maat->maat . "<br>";     
                //echo $karartikel->artikel->naam .  " aantal:" . $karartikel->aantal . ", met maat:" . $karartikel->maat->maat . "<br>";  
                echo "unieke items in kar: " . count($karretje) . "<br>";
                
                foreach($karretje as $kar){
                    echo "<b>".$kar->artikel->naam .  
                            " aantal:" . $kar->aantal . ", met maat:" . 
                            $kar->maat->maat . ":: met testid:" . 
                            $kar->createid . "." . "<br></b>";  
                           //echo $kar->artikel->naam;
                } 
                //echo "en zat er in: " . $zaterin . " want id = " . $createId . ".<br>";
                //echo "" . $zaterin2 ."<br>";
                //echo "if- " . $zaterin3 ."<br><br>";
                //echo "waar: " . $waar ."<br>";
                ?>
            </div>
            <div class="4u">
                <ul>
                    <li><?php echo anchor('welcome/index', 'Verder winkelen', array('class' => 'button big icon fa-arrow-circle-right')); ?></li>
                    <li><?php echo anchor('winkelmandje/leeg', 'Maak leeg', array('class' => 'button alt big icon fa-arrow-circle-right')); ?></li>
                    <li><?php echo anchor('winkelmandje/betalen', 'Afrekenen', array('class' => 'button alt big icon fa-arrow-circle-right')); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
