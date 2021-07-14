import $ from "jquery"
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

$(() => {

    am4core.ready(function () {
        
        const bandStats = JSON.parse($('meta[name="bands_stats"]').attr('content'));
        displayTagsGraph(bandStats.band_tags);
        displayBandEvolutionGraph(bandStats.band_evolution);

    });

});

function displayTagsGraph(data) {

    // Themes begin
    am4core.useTheme(am4themes_animated);

    // Create chart instance
    let chart = am4core.create("stats_tags", am4charts.PieChart);

    // Add data
    chart.data = data;

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

function displayBandEvolutionGraph(data) {
    
}
