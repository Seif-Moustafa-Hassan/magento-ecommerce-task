<?php
namespace Seif\Integration\Model;

use Seif\Integration\Api\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Product implements ProductInterface
{
    protected $productCollectionFactory;

    public function __construct(CollectionFactory $productCollectionFactory)
    {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function getProducts()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect([
            'entity_id',
            'sku',
            'name',
            'price',
            'special_price',
            'status',
            'quantity_and_stock_status',
            'external_product_id'
        ]);

        $products = [];
        foreach ($collection as $product) {
            $products[] = [
                'id' => $product->getId(),
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'special_price' => $product->getSpecialPrice(),
                'status' => $product->getStatus(),
                'qty' => $product->getExtensionAttributes()->getStockItem()->getQty(),
                'external_product_id' => $product->getData('external_product_id')
            ];
        }
        return $products;
    }
}