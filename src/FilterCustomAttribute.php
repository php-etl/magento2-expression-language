<?php

declare(strict_types=1);

namespace Kiboko\Component\ExpressionLanguage\Magento;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class FilterCustomAttribute extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            \Closure::fromCallable([$this, 'compile'])->bindTo($this),
            \Closure::fromCallable([$this, 'evaluate'])->bindTo($this)
        );
    }

    private function compile(string $attributeCode): string
    {
        return <<<PHP
            (fn (\$item) => \$item->getAttributeCode() === {$attributeCode})
            PHP;
    }

    private function evaluate(array $context, object $input, string $attributeCode): \Closure
    {
        return fn (object $item) => $item->getAttributeCode() === $attributeCode;
    }
}
