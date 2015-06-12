<?php

session_start();

require '../vendor/autoload.php';

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => '../templates',
));

// Create monolog logger and store logger in container as singleton 
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('motelo');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('../logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

// Database PDO
$app->db = new PDO('sqlite:' . DB_DIR . DB_FILENAME);
$app->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$app->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// Define routes
$app->get('/', function() use ($app) {
    $app->redirectTo('list', array('type' => 'solution'));
});

$app->get('/list/:type', function($type) use ($app) {
    $data = array();
    if ($type === TYPE_SOLUTION) {
        // complex call with joins on model and instance
        $data = API::getSolutionList($app->db, array('json' => false));
    } else {
        // simple call with all cols
        $data = API::getList($app->db, $type, array('json' => false));
    }
    $metadata = array(
        'nb' => count($data)
    );

    $body = 'list_' . $type . '.php';
    $bodyParams = compact('app', 'data', 'metadata');
    $jsParams = array('scripts' => array('/js/list.js'));

    Utils::renderLayout($app, $body, $bodyParams, $jsParams);
})->name('list');

$app->get('/create/:type', function($type) use ($app) {
    $body = 'creation_' . $type . '.php';
    $bodyParams = compact('app');
    $jsParams = array('scripts' => array('/js/form.js'));

    Utils::renderLayout($app, $body, $bodyParams, $jsParams);
})->name('create');

$app->post('/create/:type', function($type) use ($app) {
    FormActions::create($app, $type);
    $app->redirectTo('list', compact('type'));
});

$app->get('/edit/:type/:id', function($type, $id) use ($app) {
    //var_dump($app->request);die;
    $data = API::getOneById($app->db, $type, $id, array('json'  => false));
    if (!$data) {
        Utils::yflash('error', 'Failed to load the data for edit.');
        $app->redirect('/');
    }

    $body = 'creation_' . $type . '.php';
    $bodyParams = compact('app', 'data');
    $jsParams = array('scripts' => array('/js/form.js'));

    Utils::renderLayout($app, $body, $bodyParams, $jsParams);
})->name('edit');

$app->put('/edit/:type/:id', function($type, $id) use ($app) {
    FormActions::update($app, $type, $id);
    $app->redirectTo('list', compact('type'));
});

$app->delete('/delete/:type/:id', function($type, $id) use ($app) {
    FormActions::delete($app, $type, $id);
    $app->redirectTo('list', compact('type'));
})->name('delete');

$app->get('/view/:type/:id', function($type, $id) use ($app) {
    switch ($type) {
        default:
        case TYPE_SOLUTION:
            // complex call with joins on model and instance
            $data = API::getOneSolution($app->db, $id, array('json' => false));
            $data['instance_url'] = $app->urlFor('getdat', array('filename' => $data['instance_filename']));
            $data['url'] = $app->urlFor('getsol', array('filename' => $data['filename']));
            break;
        case TYPE_INSTANCE:
            // simple call with all cols
            $data = API::getOneById($app->db, 'instance', $id, array('json'  => false));
            $data['url'] = $app->urlFor('getdat', array('filename' => $data['filename']));
            break;
    }

    $body = 'view_' . $type . '.php';
    $bodyParams = compact('app', 'data');
    $jsParams = array(
        'libs'    => array(
            '/js/lib/cytoscape.min.js'
        ),
        'scripts' => array(
            '/js/view.js'
        )
    );

    Utils::renderLayout($app, $body, $bodyParams, $jsParams);
})->name('view');

// API
$app->get('/getcontent/:type/:filename/', function($type, $filename) use ($app) {
    echo API::getFileContent($type, $filename);
})->name('getcontent');

$app->get('/getdat/:filename', function($filename) use ($app) {
    echo API::dat2json(API::getFileContent('instance', $filename));
})->name('getdat');

$app->get('/getsol/:filename', function($filename) use ($app) {
    echo API::sol2json(API::getFileContent('solution', $filename));
})->name('getsol');

$app->get('/getmodels', function() use ($app) {
    echo API::getList($app->db, 'model');
});

$app->get('/getinstances', function() use ($app) {
    echo API::getList($app->db, 'instance');
});

$app->get('/getsolutions', function() use ($app) {
    echo API::getSolutionList($app->db);
});

// Run app
$app->run();

// END /public/index.php
