<?php
//echo "huy";
//include 'a.php';
function get_link()
{
    $link = mysqli_connect("localhost", "al", "111", "intspeed");
//    $link = mysqli_connect("localhost", "al", "111", "aaa");
    if (!$link) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit ();
    }

    mysqli_set_charset($link, 'utf8'); // https://www.php.net/manual/en/mysqli.set-charset.php
    return $link;
}

$link = get_link();
$sql = "SELECT ttime, download FROM speed";
//$sql = "SELECT da, du FROM da";
//$sql = "SELECT cats FROM kak";
//$sql = "SELECT hyip, url, perf FROM graph WHERE ttime like '" . $ttime . "' AND perf > 0";

$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $htime, $perf);
    while (mysqli_stmt_fetch($stmt)) {
        $graph[] = array('date' => $htime, 'value' => $perf);
    }
    mysqli_close($link);
}

$data = json_encode($graph);

?>

<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<meta charset="UTF-8">
<title>Title</title>
<meta http-equiv="refresh" content="60">

<!-- Resources -->
<!--<script src="https://cdn.amcharts.com/lib/4/core.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>-->
<script src="/assets/cdn.amcharts.com_lib_4_core.js"></script>
<script src="/assets/cdn.amcharts.com_lib_4_charts.js"></script>
<script src="/assets/cdn.amcharts.com_lib_4_themes_animated.js"></script>
<!--</head>-->
<!--<body>-->

<!-- Chart code -->
<script>
    am4core.ready(function () {

// Themes begin
        am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
        var chart = am4core.create("chartdiv", am4charts.XYChart);

// Add data
        chart.data = JSON.parse('<?= $data ?>');

// Set input format for the dates
//                     chart.dateFormatter.inputDateFormat = "yyyy-MM-dd-hh-mm";
        chart.dateFormatter.inputDateFormat = "yyyy-MM-dd HH-mm";

// Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "date";
        series.tooltipText = "{value}"
        series.strokeWidth = 2;
        series.minBulletDistance = 15;

// Drop-shaped tooltips
        series.tooltip.background.cornerRadius = 20;
        series.tooltip.background.strokeOpacity = 0;
        series.tooltip.pointerOrientation = "vertical";
        series.tooltip.label.minWidth = 40;
        series.tooltip.label.minHeight = 40;
        series.tooltip.label.textAlign = "middle";
        series.tooltip.label.textValign = "middle";

// Make bullets grow on hover
        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.strokeWidth = 2;
        bullet.circle.radius = 4;
        bullet.circle.fill = am4core.color("#fff");

        var bullethover = bullet.states.create("hover");
        bullethover.properties.scale = 1.3;

// Make a panning cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.behavior = "panXY";
        chart.cursor.xAxis = dateAxis;
        chart.cursor.snapToSeries = series;

// Create vertical scrollbar and place it before the value axis
        chart.scrollbarY = new am4core.Scrollbar();
        chart.scrollbarY.parent = chart.leftAxesContainer;
        chart.scrollbarY.toBack();

// Create a horizontal scrollbar with previe and place it underneath the date axis
        chart.scrollbarX = new am4charts.XYChartScrollbar();
        chart.scrollbarX.series.push(series);
        chart.scrollbarX.parent = chart.bottomAxesContainer;

        // show whole graph: dateAxis.start = 0.0;
        // dateAxis.start = 0.79;
        // dateAxis.start = 0.0;
        dateAxis.start = 0.9;
        dateAxis.keepSelection = true;


    }); // end am4core.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>
<!--</body>-->
<!--</html>-->


