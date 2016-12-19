<?php
if (isset($personen)) {
    $personenleeftijden = '';    
    $personenmetleeftijd = '';
    $somleeftijden = 0;
    for ($x = 12; $x <= 70; $x++) {
        $aantalPersonenMetLeeftijd = 0;        
        
        foreach ($personen as $persoon) {
            $time = strtotime($persoon->geboortedatum);            
            $year = date('Y', $time);
            $currentYear = date("Y");
            $leeftijd = $currentYear - $year;
            if ($leeftijd == $x) {
                $aantalPersonenMetLeeftijd++; 
                $somleeftijden += $leeftijd;
            }
        }
        $personenmetleeftijd .= $aantalPersonenMetLeeftijd . ', ';
        $personenleeftijden .= $x . ', ';
    }
    if (count($personen) > 0) {
        $gemleeftijd = $somleeftijden/count($personen);
    } else {
        $gemleeftijd = 'geen personen';
    }
    
    $personenleeftijden= substr($personenleeftijden, 0, -2);
    $personenmetleeftijd = substr($personenmetleeftijd, 0, -2);    
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

<h3>Gemiddelde leeftijd (<?php echo round($gemleeftijd, 2); ?>)</h3>
<div style="width:100%">
    <div>
        <canvas id="canvas4" height="auto" width="auto"></canvas>
    </div>
</div>
<div id="leeftijden">

</div> 
<div id="leeftijdenlegend" class="chart-legend"></div>

<script>
    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100)
    };   
    
    var lineChartData4 = {
        labels: [<?php echo $personenleeftijden; ?>],
        datasets: [
            {
                label: "Totaal personen per maand",
                fillColor: "rgba(16,160,213,0.2)",
                strokeColor: "rgba(16,160,213,1)",
                pointColor: "rgba(16,160,213,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(16,160,213,1)",
                data: [<?php echo $personenmetleeftijd; ?>]
            }
        ]

    };

    var ctx = document.getElementById("canvas4").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData4, {
        responsive: true
    });
    document.getElementById('leeftijdenlegend').innerHTML = window.myLine.generateLegend();
</script>