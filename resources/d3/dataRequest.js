/**
 * Created by cristiprg on 25-3-16.
 */

function getDataForPerson(){
    var personID = document.getElementById("personID").value;
    // var queryJSON = {
    //     "query" : "MATCH (p:Person {personID:'{personID}'})-[f:HAS*2]-(p2:Person) RETURN p,f,p2",
    //     "params" : {
    //         "personID" : personID
    //     }
    // };

    var queryJSON = {
        "query" : "MATCH (p:Person {personID:'1'})-[f:HAS*2]-(p2:Person) RETURN p2" // dam toti prietenii lui 1
    };

    $.post("http://localhost:7474/db/data/cypher", queryJSON, bypassDataToGraphVisualization);
}

function bypassDataToGraphVisualization(response, status){
    var ids = [];
    response.data.forEach(function (element) {
        ids.push(element[0].data.personID);
    });

    console.log(ids);

    //TODO: change ids to the JSON the force directed layout wants
}