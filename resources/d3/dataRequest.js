/**
 * Created by cristiprg on 25-3-16.
 */

function getNetworkForPerson(){
    var personID = document.getElementById("personID").value;
    var e  = document.getElementById("friendsDegree");
    var friendsDegree = e.options[e.selectedIndex].value;

    $.get('graph?personID=' + personID + "&friendsDegree=" + friendsDegree, bypassDataToGraphVisualization);
}

function bypassDataToGraphVisualization(response, status) {
    forceDirectedLayout(response);
}