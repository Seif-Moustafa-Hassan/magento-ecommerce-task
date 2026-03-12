<?php
namespace Seif\Integration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\HTTP\Client\Curl;

class ProductSync extends AbstractHelper
{
    protected $curl;

    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    public function sendProducts(array $products)
    {
        $baseUrl = $this->scopeConfig->getValue('seif_integration/settings/laravel_url', ScopeInterface::SCOPE_STORE);
        $apiToken = $this->scopeConfig->getValue('seif_integration/settings/api_token', ScopeInterface::SCOPE_STORE);

        if (!$baseUrl) return false;

        $url = rtrim($baseUrl, '/') . '/api/products/sync';

        $this->curl->addHeader("Authorization", "Bearer $apiToken");
        $this->curl->addHeader("Content-Type", "application/json");
        $this->curl->post($url, json_encode(['products' => $products]));

        return $this->curl->getBody();
    }
}