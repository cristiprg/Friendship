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
        chartData.per_month.unshift('per_month');
        chartData.per_day.unshift('per_day');

        console.log(chartData.per_day);

        timeDistributionChart = c3.generate({
            bindto: '#timeDistributionChart',
            data: {
                columns: [chartData.per_hour, chartData.per_month, chartData.per_day],
                type: 'bar'
            }
        });

    });
}

function displayAvgDegreeCentralityChart(){
    document.getElementById("avgDegreeCentralityChart").style.height = "200px";

    avgDegreeCentralityChart = c3.generate({
        bindto: '#avgDegreeCentralityChart',
        data: {
            columns: [
                ['data1', 30, 200, 100, 400, 150, 250],
                ['data2', 50, 20, 10, 40, 15, 25]
            ]
        }
    });
}
