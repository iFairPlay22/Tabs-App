import $ from "jquery"
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

let bandStats;

$(() => {

    am4core.ready(function () {
        
        bandStats = JSON.parse($('meta[name="band_stats"]').attr('content'));

        for (const id in bandStats) {
            console.count(`display_${id}("${id}")`);
            eval(`display_${id}("${id}")`);
        }
    });

});

function display_band_tags(dataId) {

    // Themes begin
    am4core.useTheme(am4themes_animated);

    // Create chart instance
    let chart = am4core.create(dataId, am4charts.PieChart);

    // Add data
    chart.data = bandStats[dataId].data;

    // Add and configure Series
    let pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "songs_nb";
    pieSeries.dataFields.category = "tag_label";
    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeWidth = 2;
    pieSeries.slices.template.strokeOpacity = 1;

    // This creates initial animation
    pieSeries.hiddenState.properties.opacity = 1;
    pieSeries.hiddenState.properties.endAngle = -90;
    pieSeries.hiddenState.properties.startAngle = -90;

    // Disable chart logo
    chart.logo.disabled = true;
}

function display_band_history(dataId) {

    // Themes begin
    am4core.useTheme(am4themes_animated);

    let chart = am4core.create(dataId, am4charts.XYChart);

    chart.data = bandStats[dataId].data.map(({ date, nb_songs }) => {
        return {
            date: new Date(date * 1000), nb_songs
        }
    });

    console.log(chart.data);

    // Create axes
    let dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    dateAxis.renderer.minGridDistance = 60;

    let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    let series = chart.series.push(new am4charts.LineSeries());
    series.dataFields.valueY = "nb_songs";
    series.dataFields.dateX = "date";
    series.tooltipText = "{value}"

    series.tooltip.pointerOrientation = "vertical";

    chart.cursor = new am4charts.XYCursor();
    chart.cursor.snapToSeries = series;
    chart.cursor.xAxis = dateAxis;

    // chart.scrollbarY = new am4core.Scrollbar();
    // chart.scrollbarX = new am4core.Scrollbar();

    // Disable chart logo
    chart.logo.disabled = true;
}
