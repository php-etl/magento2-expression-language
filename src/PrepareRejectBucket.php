<?php

namespace Kiboko\Component\ExpressionLanguage\Magento;

use Kiboko\Component\ExpressionLanguage\Magento\Serializable\ProductCatalogSerializableRejectData;
use Kiboko\Component\ExpressionLanguage\Magento\Serializable\SerializableRejectDataInterface;
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

    private function compile(string $dataToFormat): string
    {
        return <<<PHP
            new \Kiboko\Component\ExpressionLanguage\Magento\Serializable\ProductCatalogSerializableRejectData($dataToFormat)
            PHP;
    }

    private function evaluate(array $context, array $dataToFormat): SerializableRejectDataInterface
    {
        return new ProductCatalogSerializableRejectData($dataToFormat);
    }
}
