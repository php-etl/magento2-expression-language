<?php

namespace Kiboko\Component\ExpressionLanguage\Magento;

use Kiboko\Component\ExpressionLanguage\Magento\Serializable\ProductCatalogSerializableRejectData;
use Kiboko\Component\ExpressionLanguage\Magento\Serializable\SerializableRejectDataInterface;
use ReflectionObject;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class PrepareRejectBucket extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            \Closure::fromCallable([$this, 'compile'])->bindTo($this),
            \Closure::fromCallable([$this, 'evaluate'])->bindTo($this)
        );
    }

    private function compile(string $input, int $depth = 2): string
    {
        return <<<PHP
            (function() use ($input){
                \$serializer = function(\$input, int \$depth = 2) use (&\$serializer) {
                    if (\$depth === 0 || !is_array(\$input) && !is_object(\$input)) {
                        return json_encode(\$input);
                    }
                    \$output = [];
                    if (is_object(\$input)) {
                        \$object = new ReflectionObject(\$input);
                        foreach (\$object->getProperties() as \$property) {
                            \$value = \$property->getValue(\$input);
                            \$output[\$property->getName()] = \$serializer(\$value, \$depth - 1);
                        }
                    }
                    if (is_array(\$input)) {
                        foreach (\$input as \$key => \$value) {
                            \$output[\$key] = \$serializer(\$value, \$depth - 1);
                        }
                    }
                    return \$output;
                };
                return \$serializer(\$input, $depth);
            })()
            PHP;
    }

    private function evaluate(array $context, mixed $input, int $depth = 2): array
    {
        $serializer = function ($input, int $depth = 2) use(&$serializer) {
            if ($depth === 0 || !is_array($input) && !is_object($input)) {
                return json_encode($input);
            }
            $output = [];
            if (is_object($input)) {
                $object = new ReflectionObject($input);
                foreach ($object->getProperties() as $property) {
                    $value = $property->getValue($input);
                    $output[$property->getName()] = $serializer($value, $depth - 1);
                }
            }
            if (is_array($input)) {
                foreach ($input as $key => $value) {
                    $output[$key] = $serializer($value, $depth - 1);
                }
            }
            return $output;
        };

        return $serializer($input, $depth);
    }
}
