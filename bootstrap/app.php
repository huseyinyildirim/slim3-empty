<?php

session_start();

//region Using
use Respect\Validation\Validator as v;
//endregion

//region Include Autoload
require __DIR__ . '/../vendor/autoload.php';
//endregion

//region Slim Settings
$app = new \Slim\App([
    'settings'=>[
        'displayErrorDetails'=>true,
        'db'=>[
            'driver'=>'mysql',
            'host' =>'sistem.alpdemirkaya.com',
            'port' => '35891',
            'database'=>'venigu',
            'username'=>'fatix',
            'password'=>'s2st3m6dm2n*',
            'charset'=>'utf8',
            'collation'=>'utf8_general_ci',
            'prefix'=>''
        ]
    ]
]);
//endregion

$container = $app->getContainer();

//region Database Connection
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule){
    return $capsule;
};
//endregion

//region Define Member
$container['member']=function ($container) {
    return new \App\Functions\Member;
};
//endregion

//region Define Management
$container['management']=function ($container) {
    return new \App\Functions\Management;
};
//endregion

//region Register Slim/Flash
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};
//endregion

//region View Define
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('member',[
        'check'=>$container->member->check(),
        'user'=>$container->member->user()
    ]);

    $view->getEnvironment()->addGlobal('management',[
        'check'=>$container->management->check(),
        'admin'=>$container->management->admin()
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};
//endregion

//region Namespace Define
$container['validator'] = function ($container){
    return new App\Validation\Validator;
};

//region Controller
$container['HomeController'] = function ($container){
    return new \App\Controllers\HomeController($container);
};

$container['MemberController'] = function ($container){
    return new \App\Controllers\MemberController($container);
};

$container['RegisterController'] = function ($container){
    return new \App\Controllers\RegisterController($container);
};

$container['LoginController'] = function ($container){
    return new \App\Controllers\LoginController($container);
};

//region Management Controller
$container['Management\HomeController'] = function ($container){
    return new \App\Controllers\Management\HomeController($container);
};

$container['Management\LoginController'] = function ($container){
    return new \App\Controllers\Management\LoginController($container);
};

$container['Management\LogoutController'] = function ($container){
    return new \App\Controllers\Management\LogoutController($container);
};

$container['Management\ChangePasswordController'] = function ($container){
    return new \App\Controllers\Management\ChangePasswordController($container);
};

$container['Management\AdminController'] = function ($container){
    return new \App\Controllers\Management\AdminController($container);
};

$container['Management\MemberController'] = function ($container){
    return new \App\Controllers\Management\MemberController($container);
};
//endregion

//endregion

//region Register Crsf
$container['csrf']=function ($container) {
    return new \Slim\Csrf\Guard;
};
//endregion

//region Register Monolog
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};
//endregion

//endregion

//region Middleware
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
//endregion

//region Csrf Added in App
$app->add($container->csrf);
//endregion

v::with('App\\Validation\\Rules');

//region Include Routes
require __DIR__ . '/../app/routes.php';
//endregion