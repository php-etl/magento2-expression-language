<?php

namespace Kiboko\Component\ExpressionLanguage\Magento;

use Kiboko\Magento\V2_3\Model\CustomerDataCustomerInterface;
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

    private function compile(): string
    {
        return <<<PHP
            (array_values(array_filter(\$output->getAddresses(), fn (object \$item) => \$item->getDefaultBiling() === true))[0] ?? null)
            PHP;
    }

    private function evaluate(array $context, object $input): ?array
    {
        $output = $input;

        return array_values(
            array_filter($output->getAddresses(), fn (object $item) => $item->getDefaultBiling() === true)
        )[0] ?? null;
    }
}
