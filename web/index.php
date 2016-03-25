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
 * Endpoint for the network of one person.
 * @param personID:
 * @return the JSON specific for the D3 force graph layout
 *
 */
$app->get('/graph', function (Request $request) use ($app){

    // Extract the personID from the request.
    $personID = $request->query->get('personID');
    if ($personID == null)
        throw new \Exception( 'Expected personID parameter not found in GET /graph request' );

    // Send the request to Neo4j server with the corresponding Cypher query.
    $data = array('query' => 'MATCH (p:Person {personID:\''. $personID .'\'})-[f:HAS*2]-(p2:Person) RETURN p2');
    $result = CallAPI("POST", "http://localhost:7474/db/data/cypher", json_encode($data));

    // We are interested only in the personIDs and the next for loop iterates through the JSON
    // response and collects all the personIDs in $ids
    $ids = array();
    foreach (json_decode($result, true)['data'] as $item){
        array_push($ids, $item[0]['data']['personID']);
    }

    // Construct the D3-friendly json with the two arrays: nodes and links.
    $nodes = array();
    $links = array();
    foreach ($ids as $id){
        $nodes[] = array(
            'id' => $id,
            'size' => 3,
            'type' => 'circle'
        );

    }

    $response = array();
    $response['nodes'] = $nodes;
    $response['links'] = $links;
    return json_encode($response);
});

$app->run();

































