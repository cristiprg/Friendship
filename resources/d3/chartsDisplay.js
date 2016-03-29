/**
 * Created by cristiprg on 29-3-16.
 */

function displayTimeDistributionChart(){
    document.getElementById("chartsDisplay").style.height = "200px";

    var chart = c3.generate({
        bindto: '#chartsDisplay',
        data: {
            columns: [
                ['data1', 30, 200, 100, 400, 150, 250],
                ['data2', 50, 20, 10, 40, 15, 25]
            ],
            type: 'bar'
        }
    });
}