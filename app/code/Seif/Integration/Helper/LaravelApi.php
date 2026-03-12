<?php
namespace Seif\Integration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\HTTP\Client\Curl;

class LaravelApi extends AbstractHelper
{
    protected $curl;

    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    public function getProducts()
    {
        $laravelUrl = 'http://localhost:8000/api/mock-magento/products'; // or your real Laravel API

        $this->curl->get($laravelUrl);

        $response = $this->curl->getBody();

        return json_decode($response, true);
    }
}