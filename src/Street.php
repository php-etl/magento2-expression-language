<?php

declare(strict_types=1);

namespace Kiboko\Component\ExpressionLanguage\Magento;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class Street extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            \Closure::fromCallable([$this, 'compile'])->bindTo($this),
            \Closure::fromCallable([$this, 'evaluate'])->bindTo($this)
        );
    }

    private function compile(string $address): string
    {
        return <<<PHP
            implode(', ', {$address}->getStreet())
            PHP;
    }

    private function evaluate(array $context, object $address): string
    {
        return implode(', ', $address->getStreet());
    }
}
