/**
 * Created by cristiprg on 29-3-16.
 */

var timeDistributionChart;
var avgDegreeCentralityChart;

function displayTimeDistributionChart(){
    document.getElementById("timeDistributionChart").style.height = "200px";

    timeDistributionChart = c3.generate({
        bindto: '#timeDistributionChart',
        data: {
            columns: [
                ['data1', 30, 200, 100, 400, 150, 250],
                ['data2', 50, 20, 10, 40, 15, 25]
            ],
            type: 'bar'
        }
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