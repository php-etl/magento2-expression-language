<?php

namespace Kiboko\Component\ExpressionLanguage\Magento;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class CustomAttribute extends ExpressionFunction
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
            (array_values(array_filter(\$input->getCustomAttributes(), fn (\$item) => \$item->getAttributeCode() === $attributeCode))[0]->getValue() ?? null)
            PHP;
    }

    private function evaluate(array $context, object $input, string $attributeCode): ?array
    {
        return array_values(
            array_filter($input->getCustomAttributes(), fn (object $item) => $item->getAttributeCode() === $attributeCode)
        )[0]->getValue() ?? null;
    }
}
