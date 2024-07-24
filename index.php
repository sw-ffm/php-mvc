<?php 

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

spl_autoload_register(function(string $class_name){
    require __DIR__ . "/src/" . str_replace("\\", "/", $class_name) . ".php";
});


$router = new Framework\Router;


$router->add("/admin/{controller}/{action}", ["namespace" => "Admin"]);
$router->add("/{controller}/{id:\d+}/{action}");
$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("/{controller}/{action}");

// Registry
$container = new Framework\Container;
$container->set(App\Database::class, function(){

    return new App\Database("localhost","phpmvc","phpmvc","pass123");

});

$dispatcher = new Framework\Dispatcher($router, $container);

$dispatcher->handle($path);
