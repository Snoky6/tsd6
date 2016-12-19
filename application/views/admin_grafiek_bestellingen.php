<?php
if (isset($bestellingen)) {
    $databestellingen = '';
    $databestellingenomzet = '';
    $totaalbestellingenomzet = 0;
    $gemOmzetPerBestelling = '';
    $aantalbestellingen = 0;
    
    $totaalbestellingenomzetvorigjaar = 0;
    $databestellingenvorigjaar = '';
    $databestellingenomzetvorigjaar = '';
    $gemOmzetPerBestellingvorigjaar = '';
    $aantalbestellingenvorigjaar = 0;

    for ($x = 1; $x <= 12; $x++) {
        $aantalbestellingenopmaand = 0;
        $databestellingenomzetopmaand = 0;
        
        $aantalbestellingenopmaandvorigjaar = 0;
        $databestellingenomzetopmaandvorigjaar = 0;

        foreach ($bestellingen as $bestelling) {
            $time = strtotime($bestelling->datum);
            $newformat = date('m', $time);
            $year = date('Y', $time);
            $currentYear = date("Y");
            if ($newformat == $x && $currentYear == $year) {
                $aantalbestellingenopmaand++;
                $databestellingenomzetopmaand += $bestelling->totaalprijs;
                $totaalbestellingenomzet += $bestelling->totaalprijs;
                $aantalbestellingen += 1;
            }
            if ($newformat == $x && ($currentYear - 1) == $year) {
                $aantalbestellingenopmaandvorigjaar++;
                $databestellingenomzetopmaandvorigjaar += $bestelling->totaalprijs;
                $totaalbestellingenomzetvorigjaar += $bestelling->totaalprijs;
                $aantalbestellingenvorigjaar += 1;
            }
        }
        $databestellingen .= $aantalbestellingenopmaand . ', ';
        $databestellingenomzet .= round($databestellingenomzetopmaand, 2) . ', ';
        
        $databestellingenvorigjaar .= $aantalbestellingenopmaandvorigjaar . ', ';
        $databestellingenomzetvorigjaar .= round($databestellingenomzetopmaandvorigjaar, 2) . ', ';

        if ($aantalbestellingenopmaand == 0) {
            $gemOmzetPerBestelling .= "0.00,";
        } else {
            $gemOmzetPerBestelling .= round(($databestellingenomzetopmaand / $aantalbestellingenopmaand), 2) . ', ';
        }
        
        if ($aantalbestellingenopmaandvorigjaar == 0) {
            $gemOmzetPerBestellingvorigjaar .= "0.00,";
        } else {
            $gemOmzetPerBestellingvorigjaar .= round(($databestellingenomzetopmaandvorigjaar / $aantalbestellingenopmaandvorigjaar), 2) . ', ';
        }
    }

    if (count($bestellingen) != 0) {
        $laatsteBestelling = $bestellingen[0];
    } else {
        $laatsteBestelling = null;
    }
    

    $databestellingen = substr($databestellingen, 0, -2);
    $databestellingenomzet = substr($databestellingenomzet, 0, -2);
    $gemOmzetPerBestelling = substr($gemOmzetPerBestelling, 0, -2);
    
    $databestellingenvorigjaar = substr($databestellingenvorigjaar, 0, -2);
    $databestellingenomzetvorigjaar = substr($databestellingenomzetvorigjaar, 0, -2);
    $gemOmzetPerBestellingvorigjaar = substr($gemOmzetPerBestellingvorigjaar, 0, -2);
}
?>
<style>
    .chart-legend li span{
        display: inline-block;
        width: 12px;
        height: 12px;
        margin-right: 5px;
    }
</style>
<!-- Bestellingen TOTAAL -->

<h3>Bestellingen per maand - Bestellingen dit jaar: <?php echo $aantalbestellingen . " (&euro; " . round($totaalbestellingenomzet, 2) . ") - Vorig jaar: " . $aantalbestellingenvorigjaar . " (&euro; " . round($totaalbestellingenomzetvorigjaar, 2) . ")"; ?></h3>
<div style="width:100%">
    <div>
        <canvas id="canvas3" height="auto" width="auto"></canvas>
    </div>
</div>
<div id="bestellingenpermaand">

</div> 
<div id="bestellingenpermaandlegend" class="chart-legend"></div>

<script>
    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100)
    };

    var lineChartData3 = {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"],
        datasets: [
            {
                label: "Totaal bestellingen per maand",
                fillColor: "rgba(16,160,213,0.2)",
                strokeColor: "rgba(16,160,213,1)",
                pointColor: "rgba(16,160,213,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(16,160,213,1)",
                data: [<?php echo $databestellingen; ?>]
            },
            {
                label: "Omzet per maand",
                fillColor: "rgba(16,224,11,0.2)",
                strokeColor: "rgba(16,224,11,1)",
                pointColor: "rgba(16,224,11,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(16,224,11,1)",
                data: [<?php echo $databestellingenomzet; ?>]
            },
            {
                label: "Omzet per bestelling",
                fillColor: "rgba(230,29,128,0.2)",
                strokeColor: "rgba(230,29,128,1)",
                pointColor: "rgba(230,29,128,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(230,29,128,1)",
                data: [<?php echo $gemOmzetPerBestelling; ?>]
            },
            {
                label: "Totaal bestellingen per maand (vorig jaar)",
                fillColor: "rgba(255,255,0,0.2)",
                strokeColor: "rgba(255,255,0,1)",
                pointColor: "rgba(255,255,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,255,0,1)",
                data: [<?php echo $databestellingenvorigjaar; ?>]
            },
            {
                label: "Omzet per maand (vorig jaar)",
                fillColor: "rgba(255,150,0,0.2)",
                strokeColor: "rgba(255,150,0,1)",
                pointColor: "rgba(255,150,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,150,0,1)",
                data: [<?php echo $databestellingenomzetvorigjaar; ?>]
            },
            {
                label: "Omzet per bestelling (vorig jaar)",
                fillColor: "rgba(255,0,0,0.2)",
                strokeColor: "rgba(255,0,0,1)",
                pointColor: "rgba(255,0,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,0,0,1)",
                data: [<?php echo $gemOmzetPerBestellingvorigjaar; ?>]
            }
        ]

    };

    var ctx = document.getElementById("canvas3").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData3, {
        responsive: true
    });
    document.getElementById('bestellingenpermaandlegend').innerHTML = window.myLine.generateLegend();
</script>