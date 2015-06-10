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
        $data = API::getList($app->db, 'solution AS s INNER JOIN model as m, instance as i', array(
            'fields' => array(
                's.*',
                'm.label as model_label',
                'i.label as instance_label'
            ),
            'where' => 's.model_id=m.id and s.instance_id=i.id and s.status=1',
            'json' => false
        ));
    } else {
        // simple call with all cols
        $data = API::getList($app->db, $type, array('json' => false));
    }

    $body = 'list_' . $type . '.php';
    $bodyParams = compact('app', 'data');
    $jsParams = array('scripts' => array('/js/list.js'));

    Utils::renderLayout($app, $body, $bodyParams, $jsParams);
})->name('list');

$app->get('/create/:type', function($type) use ($app) {
    $body = 'creation_' . $type . '.php';
    $bodyParams = array();
    $jsParams = array('scripts' => array('/js/form.js'));

    Utils::renderLayout($app, $body, $bodyParams, $jsParams);
});

$app->post('/create/:type', function($type) use ($app) {
    FormActions::create($app, $type);
    $app->redirectTo('list', compact('type'));
});

$app->get('/edit/:type/:id', function($type, $id) use ($app) {
    //var_dump($app->request);die;
    $data = API::getOne($app->db, $type, array(
        'where' => 'id=' . $id,
        'json'  => false
    ));
    if (!$data) {
        Utils::yflash('error', 'Failed to load the data for edit.');
        $app->redirect('/');
    }

    $body = 'creation_' . $type . '.php';
    $bodyParams = compact('data');
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
            $data = API::getOne($app->db, 'solution AS s INNER JOIN instance as i', array(
                'fields' => array(
                    's.*',
                    'i.filename as instance_filename'
                ),
                'where'  => 's.instance_id=i.id and s.id=' . $id,
                'json'   => false
            ));
            $data['instance_url'] = $app->urlFor('getdat', array('filename' => $data['instance_filename']));
            $data['url'] = $app->urlFor('getsol', array('filename' => $data['filename']));
            break;
        case TYPE_INSTANCE:
            // simple call with all cols
            $data = API::getOne($app->db, 'instance', array(
                'where' => 'id=' . $id,
                'json'  => false
            ));
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
    echo API::getList($app->db, 'solution');
});

// Run app
$app->run();

// END /public/index.php
