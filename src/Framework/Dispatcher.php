<?php 

namespace Framework;

use ReflectionClass;
use ReflectionMethod;

class Dispatcher 
{
    public function __construct(private Router $router)
    {
    }

    public function handle(string $path)
    {

        $params = $this->router->match($path);

        if($params === false){

            exit("No route matched");

        }

        $action = $this->getActionName($params);
        $controller = $this->getControllerName($params);

        $controller_object = $this->getObject($controller);

        $args = $this->getActionArguments($controller, $action, $params);
        $controller_object->$action(...$args);

    }

    private function getObject(string $class_name): object
    {
        // Autowiring
        $dependencies = [];
        $reflector = new ReflectionClass($class_name);
        $constructor = $reflector->getConstructor();
        if($constructor === null){

            return new $class_name;

        }

        foreach($constructor->getParameters() as $parameter){

            $type = (string)$parameter->getType();
            $dependencies[] = $this->getObject($type);
            
        }
        
        return new $class_name(...$dependencies);
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