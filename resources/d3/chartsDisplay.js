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

        //console.log(chartData.per_day);

        timeDistributionChart = c3.generate({
            bindto: '#timeDistributionChart',
            data: {
                columns: [chartData.per_hour, chartData.per_week_day],
                type: 'bar'
            }
        });

        addChartTitle("#timeDistributionChart", "Distribution over day-hour/week-day (histogram)");

    });
}
// extermely misleading name LOL, it's just cumulative distribution
function displayAvgDegreeCentralityChart(){
    document.getElementById("avgDegreeCentralityChart").style.height = "200px";

    $.get('timeDistribution', function(response){
        var chartData = JSON.parse(response);
        chartData.cumulative.ticks.unshift('x');
        chartData.cumulative.values.unshift('friendships');
        //console.log(chartData);

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

        addChartTitle("#avgDegreeCentralityChart", "Cumulative Distribution");
    });
}

function displayPersonDegreeCentrality (personID){
    document.getElementById("personDegreeCentrality").style.height = "200px";

    if (parseInt(personID) == -1) {
        displayPlaceholder("#personDegreeCentrality");
    }
    else {


        $.get('personDegreeCentrality?personID=' + parseInt(personID), function (response) {
            var chartData = JSON.parse(response);

            // replace unix timestamps by dates
            chartData.degree_centrality.time.forEach(function(part, index, theArray) {
                theArray[index] = tm(theArray[index]);
            });

            chartData.degree_centrality.time.unshift('x');
            chartData.degree_centrality.values.unshift('centrality');
            console.log(chartData);


            personDegreeCentrality = c3.generate({
                bindto: '#personDegreeCentrality',
                data: {
                    x: 'x',
                    columns: [chartData.degree_centrality.time, chartData.degree_centrality.values]
                },
                axis: {
                    x: {
                        type: 'timeseries'
                    }
                }
            });
        });
    }

    addChartTitle("#personDegreeCentrality", "Node Centrality");
}

function displayPlaceholder(chartID){
    c3.generate({
        bindto: chartID,
        data: {
            x: 'x',
            columns: []
        }
    });

}

function tm(unix_tm) {
    var dt = new Date(unix_tm*1000);
    return dt.getFullYear() + '-' + dt.getMonth() + '-' + dt.getDay();

}

function addChartTitle(chartID, title){
    d3.select(chartID +' svg').append('text')
        .attr('x', d3.select(chartID +' svg').node().getBoundingClientRect().width / 2)
        .attr('y', 16)
        .attr('text-anchor', 'middle')
        .style('font-size', '1.4em')
        .text(title);
}
