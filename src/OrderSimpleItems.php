<?php

namespace Kiboko\Component\ExpressionLanguage\Magento;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class OrderSimpleItems extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            \Closure::fromCallable([$this, 'compile'])->bindTo($this),
            \Closure::fromCallable([$this, 'evaluate'])->bindTo($this)
        );
    }

    private function compile(string $items): string
    {
        return <<<PHP
            (array_filter($items, fn (object \$item) => \$item->getDefaultBilling() === true))
            PHP;
    }

    private function evaluate(array $context, array $items): array
    {
        return array_filter($items, fn (object $item) => $item->getProductType() === 'simple');
    }
}
