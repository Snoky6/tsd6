<?php
if (isset($bezoekers)) {
    // alle 24 u in label zetten
    $labels = '';
    $data = '';
    $datahitsvandaag = '';
    $dataGisteren = '';
    for ($x = 0; $x <= 24; $x++) {
        $labels .= '"' . $x . 'u", ';

        $aantalopuur = $bezoekers->daydata[$x];
        $aantalopuurGisteren = $bezoekersGisteren->daydata[$x];
        $aantalhitsopuur = $bezoekerhitsvandaag->daydata[$x];
        /*foreach ($bezoekers as $bezoeker) {
            $time = strtotime($bezoeker->bezoekdatum);
            $newformat = date('H', $time);
            if ($newformat == $x) {
                $aantalopuur++;                
            }
        }*/
        $data .= $aantalopuur . ', ';        
        
        /*foreach ($bezoekersGisteren as $bezoeker) {
            $time = strtotime($bezoeker->bezoekdatum);
            $newformat = date('H', $time);
            if ($newformat == $x) {                
                $aantalopuurGisteren++;
            }
        }*/
        $dataGisteren .= $aantalopuurGisteren . ', ';
        
        /*foreach ($bezoekerhitsvandaag as $bezoeker) {
            $time = strtotime($bezoeker->bezoekdatum);
            $newformat = date('H', $time);
            if ($newformat == $x) {                
                $aantalhitsopuur++;
            }
        }*/
        $datahitsvandaag .= $aantalhitsopuur . ', ';
    }
    $labels = substr($labels, 0, -2);
    $data = substr($data, 0, -2);
    $datahitsvandaag = substr($datahitsvandaag, 0, -2);
    $dataGisteren = substr($dataGisteren, 0, -2);
    
    
    $datahits = '';
    $databezoekersall = '';
    
    $datahitsvorigjaar = '';
    $databezoekersallvorigjaar = '';
    
    // oude code met data overload
    /*for ($x = 1; $x <= 12; $x++) {
        $aantalhitsopmaand = 0;
        $aantalhitsopmaandvorigjaar = 0;
        foreach ($bezoekerhitsall as $bezoeker) {
            $time = strtotime($bezoeker->bezoekdatum);
            $newformat = date('m', $time);
            $year = date('Y', $time);
            $currentYear = date("Y");
            if ($newformat == $x && $currentYear == $year) { // stond eerst $currentYear = $year met enkele =
                $aantalhitsopmaand++;                
            } elseif ($newformat == $x && ($currentYear - 1) == $year) {
                $aantalhitsopmaandvorigjaar++;
            }
        }
        $datahits .= $aantalhitsopmaand . ', ';
        $datahitsvorigjaar .= $aantalhitsopmaandvorigjaar . ', ';
        
        $aantalbezoekersopmaand = 0;
        $aantalbezoekersopmaandvorigjaar = 0;
        foreach ($bezoekersall as $bezoeker) {
            $time = strtotime($bezoeker->bezoekdatum);
            $newformat = date('m', $time);
            $year = date('Y', $time);
            $currentYear = date("Y");
            if ($newformat == $x && $currentYear == $year) { // stond eerst $currentYear = $year met enkele =
                $aantalbezoekersopmaand++;                
            } elseif ($newformat == $x && ($currentYear - 1) == $year) {
                $aantalbezoekersopmaandvorigjaar++;
            }
        }
        $databezoekersall .= $aantalbezoekersopmaand . ', ';
        $databezoekersallvorigjaar .= $aantalbezoekersopmaandvorigjaar . ', ';
    }*/ // einde oude code dataoverload
    
    for ($x = 1; $x <= 12; $x++) {
        $aantalhitsopmaand = $bezoekerhitsallCount->monthdata[$x];
        $aantalhitsopmaandvorigjaar = $bezoekerhitsallCount->lastyearmonthdata[$x];
        /*foreach ($bezoekerhitsallCount->monthdata[$x] as $bezoeker) {
            $time = strtotime($bezoeker->bezoekdatum);
            $newformat = date('m', $time);
            $year = date('Y', $time);
            $currentYear = date("Y");
            if ($newformat == $x && $currentYear == $year) { // stond eerst $currentYear = $year met enkele =
                $aantalhitsopmaand++;                
            } elseif ($newformat == $x && ($currentYear - 1) == $year) {
                $aantalhitsopmaandvorigjaar++;
            }
        }*/
        $datahits .= $aantalhitsopmaand . ', ';
        $datahitsvorigjaar .= $aantalhitsopmaandvorigjaar . ', ';
        
        $aantalbezoekersopmaand = $bezoekersallCount->monthdata[$x];
        $aantalbezoekersopmaandvorigjaar = $bezoekersallCount->lastyearmonthdata[$x];
        /*foreach ($bezoekersallCount->monthdata[$x] as $bezoeker) {
            $time = strtotime($bezoeker->bezoekdatum);
            $newformat = date('m', $time);
            $year = date('Y', $time);
            $currentYear = date("Y");
            if ($newformat == $x && $currentYear == $year) { // stond eerst $currentYear = $year met enkele =
                $aantalbezoekersopmaand++;                
            } elseif ($newformat == $x && ($currentYear - 1) == $year) {
                $aantalbezoekersopmaandvorigjaar++;
            }
        }*/
        $databezoekersall .= $aantalbezoekersopmaand . ', ';
        $databezoekersallvorigjaar .= $aantalbezoekersopmaandvorigjaar . ', ';
    }
    
    $datahits = substr($datahits, 0, -2);
    $databezoekersall = substr($databezoekersall, 0, -2);
    $datahitsvorigjaar = substr($datahitsvorigjaar, 0, -2);
    $databezoekersallvorigjaar = substr($databezoekersallvorigjaar, 0, -2);
}

$procentUniek = round(($bezoekersallCount->totaal / $bezoekerhitsallCount->totaal) * 100, 2);
?>
<style>
    .chart-legend li span{
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;
}
</style>

<h3>Bezoekers per uur (vandaag) - Totaal unieke bezoekers: <?php echo $bezoekersallCount->totaal . " (" . $procentUniek . "% unieke bezoekers)"; ?></h3>
<div style="width:100%">
    <div>
        <canvas id="canvas" height="auto" width="auto"></canvas>
    </div>
</div>
<div id="bezoekersperuur">

</div>
<div id="bezoekersperuurlegend" class="chart-legend"></div>

<!-- Bezoekers TOTAAL -->

<h3>Bezoekers per maand - Totaal bezoekers: <?php echo $bezoekerhitsallCount->totaal; ?></h3>
<div style="width:100%">
    <div>
        <canvas id="canvas2" height="auto" width="auto"></canvas>
    </div>
</div>
<div id="bezoekerspermaand">

</div> 
<div id="bezoekerspermaandlegend" class="chart-legend"></div>

<script>
    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100)
    };
    var lineChartData = {
        labels: [<?php echo $labels; ?>],
        datasets: [
            {
                label: "Totaal bezoekers vandaag",
                fillColor: "rgba(16,160,213,0.2)",
                strokeColor: "rgba(16,160,213,1)",
                pointColor: "rgba(16,160,213,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(16,160,213,1)",
                data: [<?php echo $datahitsvandaag; ?>]
            },
            {
                label: "Unieke bezoekers vandaag",
                fillColor: "rgba(16,224,11,0.2)",
                strokeColor: "rgba(16,224,11,1)",
                pointColor: "rgba(16,224,11,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(16,224,11,1)",
                data: [<?php echo $data; ?>]
            },
                    /*{
                label: "hits op implosion",
                fillColor: "rgba(0,0,0,0.2)",
                strokeColor: "rgba(0,0,0,1)",
                pointColor: "rgba(0,0,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(16,224,11,1)",
                data: [0,0,0,0,1,0,2,5,4,8,12,13,18,7,8,7,9,8,4,0]
            },*/
            {
                label: "Unieke bezoekers gisteren",
                fillColor: "rgba(230,29,128,0.2)",
                strokeColor: "rgba(230,29,128,1)",
                pointColor: "rgba(230,29,128,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(230,29,128,1)",
                data: [<?php echo $dataGisteren; ?>]
            }
        ]

    };

    var ctx = document.getElementById("canvas").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData, {
        responsive: true
    });
    document.getElementById('bezoekersperuurlegend').innerHTML = window.myLine.generateLegend();
    
    var lineChartData2 = {
        labels: ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Aug","Sep","Okt","Nov","Dec"],
        datasets: [
            {
                label: "Totaal hits per maand",
                fillColor: "rgba(16,160,213,0.2)",
                strokeColor: "rgba(16,160,213,1)",
                pointColor: "rgba(16,160,213,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(16,160,213,1)", 
                data: [<?php echo $datahits; ?>]
            },
            {
                label: "Unieke bezoekers per maand",
                fillColor: "rgba(16,224,11,0.2)",
                strokeColor: "rgba(16,224,11,1)",
                pointColor: "rgba(16,224,11,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(16,224,11,1)",                             
                data: [<?php echo $databezoekersall; ?>]
            },
            {
                label: "Totaal hits per maand (vorig jaar)",
                fillColor: "rgba(255,150,0,0.2)",
                strokeColor: "rgba(255,150,0,1)",
                pointColor: "rgba(255,150,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,150,0,1)",
                data: [<?php echo $datahitsvorigjaar; ?>]
            },
            {
                label: "Unieke bezoekers per maand (vorig jaar)",
                fillColor: "rgba(255,0,0,0.2)",
                strokeColor: "rgba(255,0,0,1)",
                pointColor: "rgba(255,0,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,0,0,1)",                          
                data: [<?php echo $databezoekersallvorigjaar; ?>]
            }
            
        ]

    };

    var ctx = document.getElementById("canvas2").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData2, {
        responsive: true
    });
    document.getElementById('bezoekerspermaandlegend').innerHTML = window.myLine.generateLegend();
</script>