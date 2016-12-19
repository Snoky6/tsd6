<style>
    .loadingartikelsanimation {
        position: relative;
        left: 50%;
        top: 50%;
        z-index: 989898;
        margin-top: 25px !important;
        margin: -75px 0 0 -75px;

        /* border background */
        border: 16px solid #fdcce2; /* bgcolor dulani*/
        /*border: 16px solid #ffcec9; /* bgcolor Modehuis*/
        /*border: 16px solid #f9ffcc; /* bgcolor Implosion*/         
        border-radius: 50%;        
        /* Border color */
        border-top: 16px solid #E61D80; /* color Dulani?  */      
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
</style>
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


    function showImage(src) {
        /* show popup with links */
        $("#small-dialog").html("<img src='<?php echo base_url() . APPPATH; ?>" + src + "' style='max-width: 400px;'/>");
        $(".popup-with-zoom-anim").click();
    }


    function zoekStockChanges() {
        lookup();
    }
    function lookup() {
        var barcode = $("#barcode").val();
        var enddate = $("#enddate").val();
        var startdate = $("#startdate").val();

        $.ajax({type: "GET",
            url: site_url + "/admin/stockchangesajax",
            data: {barcode: barcode, startdate: startdate, enddate: enddate},
            success: function (result) {
                $("#stockchanges").html(result);
                $("#printbutton").show();
            }
        });
    }

    function printData()
    {
        var divToPrint = document.getElementById("stockchanges");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }

</script>

<style>    
    /* popup */
    /* Styles for dialog window */
    #small-dialog {
        background: white;
        padding: 20px 30px;
        text-align: left;
        max-width: 400px;
        /*min-width: 50%;*/
        display: table;
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

    .stockchange td {
        height: auto;
        line-height: 40px
    }

</style>

<script src="<?php echo base_url() . APPPATH; ?>js/magnific.js"></script>
<link rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>css/magnific.css" />


<a class="popup-with-zoom-anim" href="#small-dialog" style="display:none;"></a>
<!-- dialog itself, mfp-hide class is required to make dialog hidden -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide"></div>

<!-- Banner -->
<div id="banner-wrapper">
    <?php
    // message artikel bewerkt
    if (isset($bewerkt)) {
        echo "<div class='toegevoegd'><p>" . $bewerkt . "</p></div>";
    }
    ?>
    <div id="banner" class="box container">        
        <div class="row">
            <div class="12u">
                <h2><?php echo global_bedrijfsnaam; ?> webshop</h2>
                <p>Alle stockveranderingen zijn weergegeven</p>                
            </div>            
        </div>
        <div class="row">
            <div class="12u">               
                <input type="text" placeholder="Zoek op barcode" id="barcode" class="zoekartikelhome" style="width: 20%;" />
                <input type="date" id="startdate" class="" style="width: 20%; line-height: 200%; padding: 12px; font-size: 120%;"/>
                <input type="date" id="enddate" class="" style="width: 20%; line-height: 200%; padding: 12px; font-size: 120%;"/>
                <a href="javascript:void(0)" onclick="zoekStockChanges()" class="button big icon fa-arrow-circle-right" style="width: 20%; padding: 15px; margin-left: 10px; position: absolute;">Zoek</a>

            </div>            
        </div>
        <!-- Features -->

        <div id="stockchanges">

        </div>
        <a href="javascript:void(0)" onclick="printData()" style="display:none;" id="printbutton" class="button big icon fa-print">Druk document af</a>
    </div>
</div>

