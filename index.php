<?php 

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

spl_autoload_register(function(string $class_name){
    require __DIR__ . "/src/" . str_replace("\\", "/", $class_name) . ".php";
});


$router = new Framework\Router;


$router->add("/{controller}/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);
$router->add("/{controller}/{id:\d+}/{action}");
$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("/{controller}/{action}");

$params = $router->match($path);

if($params === false){

    exit("No route matched");

}

$action = $params["action"];
$controller = "App\Controllers\\" . ucwords($params["controller"]);

$controller_object = new $controller;

$controller_object->$action();
