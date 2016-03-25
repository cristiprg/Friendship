/**
 * Created by cristiprg on 25-3-16.
 */

function getNetworkForPerson(){
    var personID = document.getElementById("personID").value;

    $.get('graph?personID=' + personID, bypassDataToGraphVisualization);
}

function bypassDataToGraphVisualization(response, status) {
    forceDirectedLayout(response);
}