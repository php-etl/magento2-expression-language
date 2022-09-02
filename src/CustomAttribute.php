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
            (array_values(array_filter(\$output->getCustomAttributes(), fn (\$item) => \$item->getAttributeCode() === $attributeCode))[0]["value"] ?? null)
            PHP;
    }

    private function evaluate(array $context, object $input, string $attributeCode): ?array
    {
        $output = $input;

        return array_values(
            array_filter($output->getCustomAttributes(), fn (object $item) => $item->getAttributeCode() === $attributeCode)
        )[0]["value"] ?? null;
    }
}
