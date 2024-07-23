<?php 

namespace Framework;

use ReflectionMethod;

class Dispatcher 
{
    public function __construct(private Router $router)
    {
    }

    public function handle($path)
    {

        $params = $this->router->match($path);

        if($params === false){

            exit("No route matched");

        }

        $action = $this->getActionName($params);
        $controller = $this->getControllerName($params);

        $controller_object = new $controller;

        $args = $this->getActionArguments($controller, $action, $params);

        $controller_object->$action(...$args);

    }

    private function getActionName(array $params): string 
    {
        $action = $params["action"];
        $action = ucwords(strtolower($action), "-"); 
        $action = str_replace("-","",$action);
        $action = lcfirst($action);
        return $action;
    }

    private function getControllerName(array $params): string 
    {
        $controller = $params["controller"];
        $controller = ucwords(strtolower($controller), "-"); 
        $controller = str_replace("-","",$controller);
        $namespace = "App\Controllers";

        if(array_key_exists("namespace", $params)){

            $namespace .= "\\" . $params["namespace"];

        }

        return $namespace . "\\" . $controller;
    }

    private function getActionArguments(string $controller, 
                                        string $action, 
                                        array $params): array
    {
        $args = [];

        $method = new ReflectionMethod($controller, $action);

        foreach($method->getParameters() as $parameter){

            $name = $parameter->getName();
            $args[$name] = $params[$name];
        }

        return $args;
    }
}