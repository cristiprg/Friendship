/**
 * Created by cristiprg on 29-3-16.
 */

var timeDistributionChart;
var avgDegreeCentralityChart;



function displayTimeDistributionChart(){
    document.getElementById("timeDistributionChart").style.height = "200px";

    $.get('timeDistribution', function(response){
        var chartData = JSON.parse(response);
        chartData.per_hour.unshift('per_hour');
        chartData.per_week_day.unshift('per_week_day');

        console.log(chartData.per_day);

        timeDistributionChart = c3.generate({
            bindto: '#timeDistributionChart',
            data: {
                columns: [chartData.per_hour, chartData.per_week_day],
                type: 'bar'
            }
        });

    });
}

function displayAvgDegreeCentralityChart(){
    document.getElementById("avgDegreeCentralityChart").style.height = "200px";

    $.get('timeDistribution', function(response){
        var chartData = JSON.parse(response);
        chartData.cumulative.ticks.unshift('x');
        chartData.cumulative.values.unshift('friendships');
        console.log(chartData);

        avgDegreeCentralityChart = c3.generate({
            bindto: '#avgDegreeCentralityChart',
            data: {
                x: 'x',
                columns: [chartData.cumulative.ticks,chartData.cumulative.values]
            },
            axis: {
                x: {
                    type: 'timeseries'
                }
            }
        });
    });
}
