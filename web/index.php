<?php

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../resources/util/util.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


// Declare our primary action
$app->get( '/', function() use ($app) {
   return $app['twig']->render('upload_form.html.twig');
});

$app->post('/', function (Request $request) use ($app) {
    $file_bag = $request->files;

    $caca = "nu";
    if ($file_bag->has('image')){
        $image = $file_bag->get('image');
        $caca = "da";

        if (!file_exists($app['upload_folder'])){
            mkdir($app['upload_folder']);
        }

        $image->move(
            $app['upload_folder'],
            tempnam($app['upload_folder'], 'img_')
        );

    }

    return print_r($file_bag, true);
});

$app->get('/img/{name}', function( $name, Request $request ) use ( $app ) {
    if ( !file_exists( $app['upload_folder'] . '/' . $name ) )
    {
        throw new \Exception( 'File not found' );
    }

    $out = new BinaryFileResponse($app['upload_folder'] . '/' . $name );

    return $out;
});

$app->get('/view', function() use ($app) {
    $image_glob = glob($app['upload_folder'] . '/img*');

    $images = array_map( function($val) {return basename($val);},
        $image_glob);

    return $app['twig']->render('gallery.html.twig', array('images' => $images));
});


$app->get('/resources/{fileName}', function ($fileName) use ($app) {
    //return new BinaryFileResponse($app['resource_folder'] . '/' . $fileName);
    return print_r("cacacaca");
})->assert('fileName', '.+');


/**
 * //////////////////////////////////
 * ///People network-related stuff///
 * //////////////////////////////////
 */

/**
 * The main network webpage
 */
$app->get('/network', function() use ($app) {
    return $app['twig']->render('network.html.twig');
});

/**
 * Endpoint for min-max timestamps of the network.
 * @return JSON {min : <min>, max : <max> }
 */
$app->get('/graph/minmax', function () {
    return json_encode(getMinMax());
});

/**
 * Endpoint for the network of one person.
 * @param personID:
 * @param friendsDegree [optional]: max length of path from personID to returned friends; default = 1
 * @return the JSON specific for the D3 force graph layout
 *
 */
$app->get('/graph', function (Request $request) use ($app){

    // Extract the personID from the request.
    $personID = ($request->query->get('personID'));
    if ($personID == null)
        throw new \Exception( 'Expected personID parameter not found in GET /graph request' );

    $friendsDegree = 1;
    if($request->query->has('friendsDegree')){
        $friendsDegree = $request->query->get('friendsDegree');
    }

    // Send the request to Neo4j server with the corresponding Cypher query.
    // $data = array('query' => 'MATCH (p:Person {personID:\''. $personID .'\'})-[f:HAS*2]-(p2:Person) RETURN p2'); //OLD QUERY
    $data = array('query' => 'MATCH (p:Person {personID:\''. $personID .'\'})-[:HAS*1..'.($friendsDegree*2).']-(f:Friendship)-[:HAS]-(p2:Person) WHERE toInt(f.timestamp)>0 RETURN DISTINCT f,p2');
    $result = CallAPI("POST", "http://localhost:7474/db/data/cypher", json_encode($data));

    // We are interested only in the personIDs and timestamps and the next for loop iterates through the JSON
    // response and collects all the personIDs and timestampt in $friends
    // $friends = array (WHO, WHEN)
    $friends = array();
    foreach (json_decode($result, true)['data'] as $item){

        $id = $item[1]['data']['personID'];
        $timestamp = $item[0]['data']['timestamp'];

        $friends[] = array('personID' => $id, 'timestamp' => $timestamp); // note: everything is string here
    }

    // Construct the D3-friendly json with the two arrays: nodes and links.
    $nodes[] = array('id'=>$personID, 'size'=>3, 'type'=>'square', 'score'=>0); // index 0
    $links = array();

    $skipped = 0;
    for ($index = 0; $index < count($friends); ++$index){

        // TODO: this s**t is the mother of hacks
        if (skip($nodes, $friends[$index]['personID'])){
            ++$skipped;
            continue;
        }

        $nodes[] = array(
            'id' => $friends[$index]['personID'],
            'size' => 3,
            'type' => 'circle',
            'score' => intval($friends[$index]['timestamp']) == 0 ? null : intval($friends[$index]['timestamp'])
        );

        // with links, it gets a bit nasty because the indexes of the nodes have to be specified, instead of ids
        $links[] = array(
            'source' => 0,
            'target' => $index + 1 - $skipped
        );
    }

    $response = array();
    $response['nodes'] = $nodes;
    $response['links'] = $links;
    return json_encode($response);
});

$app->run();

function skip($nodes, $personID){
    foreach ($nodes as $node) {
        if ($node['id'] == $personID) {
            return true;
        }
    }
    return false;
}
































