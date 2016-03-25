<?php

require __DIR__ . '/../bootstrap.php';

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

$app->get('/network', function() use ($app) {
    return $app['twig']->render('network.html.twig');
});

$app->run();

































