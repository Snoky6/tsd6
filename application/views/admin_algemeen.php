<!-- Main -->
<script src="<?php echo base_url(); ?>application/js/Chart.js"></script>
<script>
    $(document).ready(function () {
        checkDevice();
        
        function hideAllDivs() {
            $("#teksten").hide();
            $("#gegevens").hide();
            $("#grafieken").hide();
            $("#resize").hide();
            $("#settings").hide();
            $("#kortingscodes").hide();
            $("#home").hide();
        }

        $("#btnteksten").click(function () {
            hideAllDivs();
            $("#teksten").show();
        });
        $("#btngegevens").click(function () {
            hideAllDivs();
            $("#gegevens").show();
        });
        $("#btnkortingscodes").click(function () {
            hideAllDivs();
            $("#kortingscodes").show();
            $.ajax({type: "GET",
                url: site_url + "/admin/kortingscodes",
                data: {},
                success: function (result) {
                    $("#kortingscodes").html(result);
                }
            });
        });
        $("#btnsettings").click(function () {
            hideAllDivs();
            document.getElementById("timerdate").value = "<?php echo date("Y-m-d", strtotime($setting->countdownEndDate)) . 'T' . date("H:i:s", strtotime($setting->countdownEndDate)); ?>";
            //document.getElementById("timerdate").innerHTML = x;
            $("#settings").show();
        });
        $("#btngrafieken").click(function () {
            hideAllDivs();
            $("#grafieken").show();
            $("#grafieken").css({height: "auto"});
            $.ajax({type: "GET",
                url: site_url + "/admin/grafiekbezoekersperuur",
                data: {},
                success: function (result) {
                    $("#bezoekersperuur").html(result);
                }
            });
            $.ajax({type: "GET",
                url: site_url + "/admin/grafiekbestellingenpermaand",
                data: {},
                success: function (result) {
                    $("#bestellingenpermaand").html(result);
                }
            });
            $.ajax({type: "GET",
                url: site_url + "/admin/grafiekleeftijdenbestellers",
                data: {},
                success: function (result) {
                    $("#leeftijdenbestellers").html(result);
                }
            });
            $.ajax({type: "GET",
                url: site_url + "/admin/topproducten",
                data: {},
                success: function (result) {
                    $("#topproducten").html(result);
                }
            });
        });
        $("#btnresize").click(function () {
            hideAllDivs();
            $("#resize").show();
            $("#resize").css({height: "auto"});
            $.ajax({type: "GET",
                url: site_url + "/admin/resize",
                data: {},
                success: function (result) {
                    $("#resize").html(result);
                }
            });
        });
        $("#timerenabled").change(function () {
            var date = $("#timerdate").val();
            $.ajax({type: "GET",
                url: site_url + "/admin/savetimer",
                data: {date: date},
                success: function (result) {

                }
            });
        });
        $("#savetransport").click(function () {
            var transportkost = $("#transportkost").val();
            var taxvrijlimiet = $("#taxvrijlimiet").val();
            //alert(transportkost + ", " + taxvrijlimiet);
            $.ajax({type: "GET",
                url: site_url + "/admin/savetransport",
                data: {transportkost: transportkost, taxvrijlimiet: taxvrijlimiet},
                success: function (result) {
                    $("#savetransport").val("Opgeslagen!");
                }
            });
        });
        $("#savealgemenekorting").click(function () {
            var algemenekorting = $("#algemenekorting").val();
            //alert(transportkost + ", " + taxvrijlimiet);
            $.ajax({type: "GET",
                url: site_url + "/admin/savealgemenekorting",
                data: {algemenekorting: algemenekorting},
                success: function (result) {
                    $("#savealgemenekorting").val("Opgeslagen!");
                }
            });
        });
    });
    function savenewkortingscode() {
        var code = $("#kortingCodeCode").val();
        var korting = $("#kortingCodeAmount").val();
        var procent = $("#kortingCodeProcent").prop('checked');
        var multiuse = $("#kortingCodeMultiUse").prop('checked');
        $.ajax({type: "GET",
            url: site_url + "/admin/savekortingscode",
            data: {code: code, korting: korting, procent: procent, multiuse: multiuse},
            success: function (result) {
                $("#savekortingscode").val("Opgeslagen!");
                $("#kortingCodeCode").val("");
                $("#kortingCodeAmount").val("");
                $("#kortingCodeProcent").prop('checked', false);
                $("#kortingCodeMultiUse").prop('checked', false);
                $("#btnkortingscodes").click();
            }
        });
    }

    function deletekortingcode(id) {
        $.ajax({type: "GET",
            url: site_url + "/admin/deletekortingscodebyid",
            data: {id: id},
            success: function (result) {
                $("#kortingcoderow" + id).remove();
            }
        });
    }


    function checkDevice() {
        //test
        var isAndroid = /android/i.test(navigator.userAgent.toLowerCase());
        var device = "PC";
        if (isAndroid)
        {
            device = 'Android';
        }
        var isiPad = /ipad/i.test(navigator.userAgent.toLowerCase());
        if (isiPad)
        {
            device = 'iPad';
        }
        var isiPhone = /iphone/i.test(navigator.userAgent.toLowerCase());
        if (isiPhone)
        {
            device = 'iPhone';
        }
        var isBlackBerry = /blackberry/i.test(navigator.userAgent.toLowerCase());
        if (isBlackBerry)
        {
            device = 'BlackBerry';
        }
        var isWebOS = /webos/i.test(navigator.userAgent.toLowerCase());
        if (isWebOS)
        {
            device = 'WebOS';
        }
        var isWindowsPhone = /windows phone/i.test(navigator.userAgent.toLowerCase());
        if (isWindowsPhone)
        {
            device = 'Windows Phone';
        }

        $('#device').html(device);
    }
</script>

<style>
    table tr td label{ font-weight: normal; font-size: 100%;}

    .spinner {
        margin: 100px auto 0;
        width: 70px;
        text-align: center;
    }

    .spinner > div {
        width: 18px;
        height: 18px;
        background-color: #333;

        border-radius: 100%;
        display: inline-block;
        -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
        animation: sk-bouncedelay 1.4s infinite ease-in-out both;
    }

    .spinner .bounce1 {
        -webkit-animation-delay: -0.32s;
        animation-delay: -0.32s;
    }

    .spinner .bounce2 {
        -webkit-animation-delay: -0.16s;
        animation-delay: -0.16s;
    }

    @-webkit-keyframes sk-bouncedelay {
        0%, 80%, 100% { -webkit-transform: scale(0) }
        40% { -webkit-transform: scale(1.0) }
    }

    @keyframes sk-bouncedelay {
        0%, 80%, 100% { 
            -webkit-transform: scale(0);
            transform: scale(0);
        } 40% { 
            -webkit-transform: scale(1.0);
            transform: scale(1.0);
        }
    }

    .eventImg {
        position: relative;

        top: 0;
        right: 0px;
        float: right;
    }

    .eventbg {
        background-image: url('<?php echo base_url() . APPPATH; ?>images/event.png');
        background-repeat: no-repeat;    
        background-position: top right;
    }
</style>
<script src="<?php echo base_url() . APPPATH; ?>editor/ckeditor.js"></script>

<?php
// message tekst aangepast
if (isset($bewerkt)) {
    echo "<div class='toegevoegd'><p>" . $bewerkt . "</p></div>";
}
?>
<div id="main-wrapper">       
    <div class="container">
        <div id="content">

            <!-- Content -->
            <article>

                <h2>Admin algemeen - <span id="device"></span></h2>
                <a href="" class=""></a>
                <input type="button" id="btnteksten" value="Teksten"/>
                <input type="button" id="btnsettings" value="Settings"/>                
                <input type="button" id="btnkortingscodes" value="Kortingscodes"/>
                <input type="button" id="btngrafieken" value="Grafieken"/>                
                <br><br>
                <div id="home">
                    <h2>Changelog</h2>
                    <ul>
                        <li><b>V4.1.1 - 17/11/2016</b></li>
                        <ul>    
                            <li>Add: Complete layout remake</li> 
                            <li>Change: Bedrijfgerelateerde data is geupdatet</li>                                
                        </ul>                        
                    </ul>

                </div>
                <div id="teksten" style="display:none;">                    
                    <?php echo form_open('admin/tekstenaanpassen'); ?>
                    <?php
                    foreach ($teksten as $tekst) {
                        echo "<h3>$tekst->naam</h3>";
                        $data = array('name' => str_replace(' ', '', $tekst->naam), 'id' => str_replace(' ', '', $tekst->naam), 'cols' => '50', 'rows' => '6', "style" => 'font-size:' . $tekst->tekstgrootte . '%;', 'placeholder' => 'Vul hier je tekst in.', 'value' => $tekst->tekst);
                        echo form_textarea($data);
                        echo "<br/>";
                        echo "<script>CKEDITOR.replace('" . str_replace(' ', '', $tekst->naam) . "');</script>";
                        echo "<br/>";
                    }
                    
                    echo form_submit('submit', 'Bewaar gegevens!');
                    echo form_close();
                   ?>

                </div>

                <div id="settings" style="display:none;">
                    <h3>Timer</h3>
                    <table style="width: 300px;">
                        <tr>
                            <td><input type="datetime-local" name="timerdate" id="timerdate" /></td>
                            <td><input type="checkbox" name="timerenabled" id="timerenabled" style="width: 30px; height: 30px;" <?php
                                if ($setting->countdownEnabled == 1) {
                                    echo "checked";
                                }
                                ?>></td>
                        </tr>
                    </table>                    

                    <h3>Transport</h3>
                    <table style="width: 300px;">                        
                        <tr>
                            <td><label for="transportkost">Transportkost: </label></td>
                            <td><input type="text" name="transportkost" id="transportkost" value="<?php echo $setting->transportkost ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="taxvrijlimiet">Taxvrijlimiet: </label></td>
                            <td><input type="text" name="taxvrijlimiet" id="taxvrijlimiet" value="<?php echo $setting->taxvrijlimiet ?>" /></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="button" name="savetransport" id="savetransport" value="Opslaan" /></td>
                        </tr>
                    </table>                    

                    <h3>Solden korting</h3>
                    <table style="width: 400px;">
                        <tr>
                            <td><label for="algemenekorting">Algemene korting: </label></td>
                            <td><input type="text" name="algemenekorting" id="algemenekorting" value="<?php echo $setting->algemenekorting ?>" /></td>
                        </tr>                        
                        <tr>
                            <td colspan="2"><input type="button" name="savealgemenekorting" id="savealgemenekorting" value="Opslaan" /></td>
                        </tr>
                    </table> 

                </div>

                <div id="grafieken" style='height: 0px; overflow: hidden;'>                    
                    <div id="bezoekersperuur">
                        <div class="spinner">
                            <div class="bounce1"></div>
                            <div class="bounce2"></div>
                            <div class="bounce3"></div>
                        </div>
                    </div>   
                    <div id="bestellingenpermaand">
                        <div class="spinner">
                            <div class="bounce1"></div>
                            <div class="bounce2"></div>
                            <div class="bounce3"></div>
                        </div>
                    </div>
                    <div id="leeftijdenbestellers">
                        <div class="spinner">
                            <div class="bounce1"></div>
                            <div class="bounce2"></div>
                            <div class="bounce3"></div>
                        </div>
                    </div>
                    <div id="topproducten">
                        <div class="spinner">
                            <div class="bounce1"></div>
                            <div class="bounce2"></div>
                            <div class="bounce3"></div>
                        </div>
                    </div>
                </div>

                <div id="kortingscodes" style="display:none;">
                    <p>Laden...</p>
                </div>
            </article>

        </div>
    </div>
</div>