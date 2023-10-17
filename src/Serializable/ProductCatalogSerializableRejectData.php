<?php

namespace Kiboko\Component\ExpressionLanguage\Magento\Serializable;

use Kiboko\Magento\V2_3\Model\CatalogDataProductInterface;

class ProductCatalogSerializableRejectData implements SerializableRejectDataInterface
{
    private mixed $data;
    public function __construct(mixed $data)
    {
        $this->data = $data;
    }

    public function jsonSerialize(): mixed
    {
        if (!$this->data instanceof CatalogDataProductInterface)
        {
            return $this->data;
        }

        return ['ProductSku' => $this->data->getSku(), 'ProductName' => $this->data->getName()];
    }
}
