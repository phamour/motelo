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
    $app->redirect('/view');
});

$app->get('/list/:type', function($type) use ($app) {
    $app->render('header.php');
    $app->render('flash.php');
    switch ($type) {
        default:
        case TYPE_SOLUTION:
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
            $app->render('list_solution.php', compact('app', 'data'));
            break;
        case TYPE_INSTANCE:
            // simple call with all cols
            $data = API::getList($app->db, 'instance', array('json' => false));
            $app->render('list_instance.php', compact('app', 'data'));
            break;
        case TYPE_MODEL:
            // simple call with all cols
            $data = API::getList($app->db, 'model', array('json' => false));
            $app->render('list_model.php', compact('app', 'data'));
            break;
    }
    $app->render('jsincludes.php', array(
        'scripts' => array('/js/list.js')
    ));
    $app->render('footer.php');
});

$app->get('/create/:type', function($type) use ($app) {
    $app->render('header.php');
    $app->render('flash.php');
    switch ($type) {
        default:
        case TYPE_SOLUTION:
            $app->render('creation_solution.php');
            break;
        case TYPE_INSTANCE:
            $app->render('creation_instance.php');
            break;
        case TYPE_MODEL:
            $app->render('creation_model.php');
            break;
    }
    $app->render('jsincludes.php', array(
        'scripts' => array('/js/form.js')
    ));
    $app->render('footer.php');
});

$app->post('/create/:type', function($type) use ($app) {
    FormActions::create($app, $type);
    $app->redirect('/create/' . $type);
});

$app->get('/edit/:type/:id', function($type, $id) use ($app) {
    //var_dump($app->request);die;
    $data = CRUD::raw($app->db, array(
        'table' => $type,
        'where' => 'id=' . $id
    ));
    $app->render('header.php');
    $app->render('flash.php');
    if (!$data) {
        Utils::yflash('error', 'Failed to load the data for edit.');
        $app->redirect('/');
    } else {
        switch ($type) {
            default:
            case TYPE_SOLUTION:
                $app->render('creation_solution.php', compact('data'));
                break;
            case TYPE_INSTANCE:
                $app->render('creation_instance.php', compact('data'));
                break;
            case TYPE_MODEL:
                $app->render('creation_model.php', compact('data'));
                break;
        }
    }
    $app->render('jsincludes.php', array(
        'scripts' => array('/js/form.js')
    ));
    $app->render('footer.php');
})->name('edit');

$app->put('/edit/:type/:id', function($type, $id) use ($app) {
    FormActions::update($app, $type, $id);
    $app->redirect('/list/' . $type);
});

$app->delete('/delete/:type/:id', function($type, $id) use ($app) {
    FormActions::delete($app, $type, $id);
    $app->redirect('/list/' . $type);
})->name('delete');

$app->get('/view', function() use ($app) {
    $app->render('header.php');
    $app->render('flash.php');
    $app->render('view.php');
    $app->render('jsincludes.php', array(
        'libs'    => array(
            '/js/lib/cytoscape.min.js'
        ),
        'scripts' => array(
            '/js/view.js'
        )
    ));
    $app->render('footer.php');
});

// API
// TODO: need refactoring
$app->get('/getcontent/:type/:filename/', function($type, $filename) use ($app) {
    $path = STORAGE_DIR;
    $ext = '.txt';
    switch ($type) {
        default:
        case TYPE_SOLUTION:
            $path .= 'solution/';
            $ext = '.sol';
            break;
        case TYPE_INSTANCE:
            $path .= 'instance/';
            $ext = '.dat';
            break;
        case TYPE_MODEL:
            $path .= 'model/';
            $ext = '.mod';
            break;
    }
    $path .= $filename . $ext;
    echo file_get_contents($path);
});
$app->get('/getdat/:filename', function($filename) use ($app) {
    echo Utils::dat2json(file_get_contents(STORAGE_DIR . $filename . '.dat'));
});

$app->get('/getsol/:filename', function($filename) use ($app) {
    echo Utils::sol2json(file_get_contents(STORAGE_DIR . $filename . '.sol'));
});

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
