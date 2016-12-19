<style>
    .loadingartikelsanimation {
        position: relative;
        left: 50%;
        top: 50%;
        z-index: 9;
        margin-top: 25px !important;
        margin: -75px 0 0 -75px;

        /* border background */
        border: 8px solid #d6c194;               
        border-radius: 50%;        
        /* Border color */
        border-top: 8px solid #b1965d;         

        width: 60px;
        height: 60px;
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
    var mainSort = "nieuw";
    var load =
<?php
if (isset($setting)) {
    echo $setting->homepagerows * 2;
} else {
    echo 8;
}
?>;
    var lastLoad = 0;
    var allLoaded = true;
    var loadedimages = 0;
    var autosearchfor = "<?php
if (isset($autosearchfor)) {
    echo $autosearchfor;
}
?>";

    $(document).ready(function () {
        if (autosearchfor === "") {
            lazyload();
        } else {
            $("#zoekartikel").val(autosearchfor);
            $("#zoekartikel").text(autosearchfor);

            var element = $(".loadingartikels");
            var animationelement = $(".loadingartikelsanimation");
            animationelement.fadeOut("slow", function () {
                // Animation complete
                animationelement.remove();
                element.fadeIn("slow", function () {

                });
            });

            element.removeClass("loadingartikels");

            zoekArtikel();
        }
    });

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1000) {
            //alert("near bottom!");
            if (($("#zoekartikel").val() === '')) {
                if (allLoaded) {
                    lastLoad = load;
                    load += 9;
                    lazyload();
                }
            }
        }
    });



    function lazyload() {
        allLoaded = false;
        var sort = $("#sorteer").val();
        var categorieNaam = $("#categorie").val();
        $.ajax({type: "GET",
            url: site_url + "/welcome/lazyload",
            data: {amount: load, lastamount: lastLoad, sort: sort},
            success: function (result) {
                //$("#artikels").html(result);
                var element = $(".loadingartikels");
                var animationelement = $(".loadingartikelsanimation");
                animationelement.fadeOut("slow", function () {
                    // Animation complete
                    element.html(result);
                    animationelement.remove();
                    element.fadeIn(1500);
                });


                element.removeClass("loadingartikels");
            }
        });
    }

    var key_count_global = 0;
    //zoekArtikel();
    function zoekArtikel() {
        key_count_global++;
        setTimeout("lookup(" + key_count_global + ")", 1500)

    }
    function lookup(key_count) {
        /*var sort = $("#sorteer").val();*/
        var sort = mainSort;
        var input = $("#zoekartikel").val();
        if (key_count == key_count_global && input != "") {
            $.ajax({type: "GET",
                url: site_url + "/welcome/searchArtikelsByInputAndSort",
                data: {input: input, sort: sort},
                success: function (result) {
                    $("#artikels").html(result);
                }
            });
        }
    }

    /* Old function replaced by nice dropdownlist */
    function sorteer() {
        var sort = $("#sorteer").val();
        mainSort = sort;
        var input = $("#zoekartikel").val();
        $.ajax({type: "GET",
            url: site_url + "/welcome/searchArtikelsByInputAndSort",
            data: {input: input, sort: sort},
            success: function (result) {
                $("#artikels").html(result);
            }
        });
    }

    function sorteernieuw(sort, sender) {
        /* set main optie content */
        mainSort = sort;
        $("#main-option").html(sender.innerHTML);

        // also close that dropdownlist after this click (small timeout needed because computers are so damn fast) 
        $("#options-content").addClass('hideOptions');
        // Make sure it has desired effect on mobile
        $("#options-content").addClass('noTouchMobile');
        setTimeout(function () {
            $("#options-content").removeClass('hideOptions');
        }, 10);

        var input = $("#zoekartikel").val();
        $.ajax({type: "GET",
            url: site_url + "/welcome/searchArtikelsByInputAndSort",
            data: {input: input, sort: sort},
            success: function (result) {
                $("#artikels").html(result);
            }
        });
    }

    function openDropdownMobile() {
        setTimeout(function () {
            $("#options-content").removeClass('noTouchMobile');
        }, 10);
    }

    // shortcut voor gemak
    $(document).keypress("enter", function (e) {
        if (e.ctrlKey)
            window.location.href = site_url + "/admin";
    });

    function openOverlayCart(result) {
        $('body').css('overflow', 'hidden');
        $("#overlay-cart").fadeIn("fast");
        $("#overlay-sidebar-cart-content").html(result);
        setTimeout(function () {
            $("#overlay-sidebar-cart").css("min-width", "300px");
            $("#overlay-sidebar-cart").css("width", "25%");

        }, 100);

    }

    function closeOverlayCart() {
        $("#overlay-sidebar-cart").css("width", "0%");
        $("#overlay-sidebar-cart").css("min-width", "0px");
        setTimeout(function () {
            $("#overlay-cart").fadeOut("slow", function () {
                $('body').css('overflow', 'scroll');
            });
        }, 100);
    }

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
        min-width: 50%;

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

<style>
    .dropdown {
        position: relative;
        display: inline-block;
        cursor: pointer;
        text-align: left;
        background-color: white;
    }

    .dropdown i{
        width: 15%;
        max-width: 30px;
    }

    .dropdown-content {
        display: block;
        overflow: hidden;
        position: absolute;
        max-height: 0px;
        background-color: #f9f9f9;
        z-index: 10;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        text-align: left;

        -moz-transition: max-height 1s;
        -ms-transition: max-height 1s;
        -o-transition: max-height 1s;
        -webkit-transition: max-height 1s;
        transition: max-height 1s;
    }

    .dropdown:hover .dropdown-content {   
        //display: block;
        max-height: 1000px;        
    }

    .desc {
        padding: 15px;    
        border-bottom: 1px solid rgba(0,0,0,0.5);
        font-size: 110%;
        line-height: 200%;

        -webkit-transition: background-color 0.3s ease-out; 
        -moz-transition: background-color 0.3s ease-out; 
        -o-transition: background-color 0.3s ease-out; 
        transition: background-color 0.3s ease-out;
    }

    .desc:hover {
        background-color: #eee;
    }

    .main-option {
        padding: 12px;
        font-size: 120%;
        line-height: 200%;
        /*box-shadow: 0 0 1px 1px rgba(0,0,0,0.5);*/
        border: 1px solid #a9a9a9;
    }

    .hideOptions {
        display: none;
        max-height: 0px;
    }

    /* If mobile don't show again after click because the hover is still in effect even when hidden */
    .dropdown:hover .dropdown-content.noTouchMobile {   
        //display: block;
        max-height: 0px;   
    }

    /* Sidebar for cart */
    #overlay-sidebar-cart {
        right: 0px;
        top: 0px;        
        position: fixed;
        overflow: hidden;
        width: 0%;
        box-shadow: -1px 0 10px 5px rgba(0,0,0,0.5);
        background-color: white;
        height: 100%;
        z-index: 12;
        overflow-y: scroll;
        -webkit-transition: width 0.3s ease-out; 
        -moz-transition: width 0.3s ease-out; 
        -o-transition: width 0.3s ease-out; 
        transition: width 0.3s ease-out;
    }

    /* Sidebar for cart */
    #overlay-cart {
        left: 0px;
        top: 0px;
        display: none;
        position: fixed;        
        width: 100%;        
        background-color: rgba(0,0,0,0.5);
        height: 100%;
        z-index: 11;       
    }

    #overlay-sidebar-cart-header {
        position: relative;
        top:0;
        left: 0;
        padding: 1em;
        background-color: #444;
        width: 100%;       
        text-align: center;
        font-size: 150%;
        font-weight: 700;
        color:white;
    }

    #overlay-sidebar-cart-content {
        padding: 1.3em;        
    }

    /* override box styling */
    .box {
        background: transparent;        
    }

    .box h2 {
        color: #3e3e3a !important;
    }

    .box p, .box b {
        color: #a8a8a7 !important;
    }
    
    .container {
        width: 80%;
        min-width: 995px;
    }
</style>

<script src="<?php echo base_url() . APPPATH; ?>js/magnific.js"></script>
<link async rel="stylesheet" href="<?php echo base_url() . APPPATH; ?>css/magnific.css" />

<!-- needed for nice popup -->
<a class="popup-with-zoom-anim" href="#small-dialog" style="display:none;"></a>
<!-- dialog itself, mfp-hide class is required to make dialog hidden -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide"></div>
<div id="overlay-cart" onclick="closeOverlayCart();"></div>
<div id="overlay-sidebar-cart" class=""><div id="overlay-sidebar-cart-header"><i class="fa fa-times clickable" style="margin-right: 5%;" title="Sluiten" aria-hidden="true" onclick="closeOverlayCart();"></i> &nbsp; Winkelmandje</div><div id="overlay-sidebar-cart-content"></div></div>

<!-- Banner -->
<div id="banner-wrapper">
    <div id="" class="box container" style="min-height: 25em;">
        <div class="row">
            <div class="9u">
                <input type="text" placeholder="Zoek een artikel" id="zoekartikel" class="zoekartikelhome" onkeyup="zoekArtikel()" />
            </div>
            <div class="3u">
                <div class="dropdown 12u">
                    <div id="main-option" class="main-option" onmouseenter="openDropdownMobile();" onclick="openDropdownMobile();"><i class="fa fa-clock-o" aria-hidden="true"></i><span>Sorteer op nieuwste</span></div>
                    <div id="options-content" class="dropdown-content 12u">                        
                        <div class="desc" onclick="sorteernieuw('nieuw', this)"><i class="fa fa-clock-o" aria-hidden="true"></i><span>Sorteer op nieuwste</span></div>
                        <div class="desc" onclick="sorteernieuw('prijs', this)"><i class="fa fa-eur" aria-hidden="true"></i><span>Sorteer op prijs</span></div>
                        <div class="desc" onclick="sorteernieuw('korting', this)"><i class="fa fa-star" aria-hidden="true"></i><span>Sorteer op korting</span></div>
                        <div class="desc" onclick="sorteernieuw('populair', this)"><i class="fa fa-fire" aria-hidden="true"></i><span>Sorteer op populariteit</span></div>
                    </div>
                </div>

<!--<select id="sorteer" onchange="sorteer()" class="zoekartikelhome" style="padding:17px;">
    <option value="nieuw">Sorteer op nieuw</option>
    <option value="naam">Sorteer op naam</option>
    <option value="prijs">Sorteer op prijs</option>   
    <option value="korting">Sorteer op korting</option>  
    <option value="populair">Sorteer op populariteit</option>                    
</select>      -->          
            </div>
        </div>


        <div id="" class="loadingartikelsanimation"></div>
        <div class="loadingartikels" id="artikels" style="display:none;">

        </div>

    </div>