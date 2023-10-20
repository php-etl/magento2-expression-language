<?php

declare(strict_types=1);

namespace Kiboko\Component\ExpressionLanguage\Magento;

use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

final class MagentoExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new CustomAttribute('customAttribute'),
            new BillingAddress('billingAddress'),
            new ShippingAddress('shippingAddress'),
            new Street('street'),
            new OrderSimpleItems('simpleItems'),
            new FilterCustomAttribute('filterCustomAttribute'),
            new Normalize('normalize'),
        ];
    }
}
