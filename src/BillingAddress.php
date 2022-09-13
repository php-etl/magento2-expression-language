<?php

namespace Kiboko\Component\ExpressionLanguage\Magento;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class BillingAddress extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            \Closure::fromCallable([$this, 'compile'])->bindTo($this),
            \Closure::fromCallable([$this, 'evaluate'])->bindTo($this)
        );
    }

    private function compile(string $addresses): string
    {
        return <<<PHP
            (array_values(array_filter($addresses, fn (object \$item) => \$item->getDefaultBilling() === true))[0] ?? null)
            PHP;
    }

    private function evaluate(array $context, array $addresses): ?array
    {
        return array_values(
            array_filter($addresses, fn (object $item) => $item->getDefaultBilling() === true)
        )[0] ?? null;
    }
}
