<?php
namespace Seif\Integration\Api;

interface ProductInterface
{
    /**
     * GET Products for Laravel
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getProducts();
}