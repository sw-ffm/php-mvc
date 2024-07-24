<?php 

declare(strict_types=1);

namespace Framework;

use Closure;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionNamedType;

class Container 
{   

    private array $registry = [];

    public function set(string $name, Closure $value): void 
    {
        $this->registry[$name] = $value;
    }

    public function get(string $class_name): object
    {
        // Registry
        if(array_key_exists($class_name, $this->registry)){

            return $this->registry[$class_name]();

        }

        // Autowiring
        $dependencies = [];
        $reflector = new ReflectionClass($class_name);
        $constructor = $reflector->getConstructor();
        if($constructor === null){

            return new $class_name;

        }

        foreach($constructor->getParameters() as $parameter){

            $type = $parameter->getType();

            if($type === null){

                throw new InvalidArgumentException("Constructor parameter '{$parameter->getName()}'
                    in the $class_name class 
                    has no type definition");

            }

            if(!($type instanceof ReflectionNamedType)){

                throw new InvalidArgumentException("Constructor parameter '{$parameter->getName()}'
                    in the $class_name class 
                    is an invalid type: '$type'");

            }

            if($type->isBuiltin()){

                throw new InvalidArgumentException("Unable to resolve constructor parameter 
                    '{$parameter->getName()}' of type 
                    '$type' in the $class_name class");

            }

            $dependencies[] = $this->get((string)$type);
            
        }
        
        return new $class_name(...$dependencies);
    }
}