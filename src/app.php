<?php

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app['debug'] = true;

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'crud_jquery',
        'user' => 'root',
        'password' => ''
    ),
));

$app->get('/create-table', function (Silex\Application $app) {
    $file = fopen(__DIR__ . '/../data/schema.sql', 'r');
    while ($line = fread($file, 4096)) {
        $app['db']->executeQuery($line);
    }
    fclose($file);
    return "Tabelas criadas";
});

/***************************************************/
// PÁGINAS
/***************************************************/

$app->get('/', function () {
    ob_start();
    include __DIR__ . '/../templates/home.php';
    return ob_get_clean();
});

/***************************************************/
// ADMINISTRATIVO
/***************************************************/

$app->run();
