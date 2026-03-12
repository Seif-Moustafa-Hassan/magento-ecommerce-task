<?php

namespace Seif\Integration\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\HTTP\Client\Curl;

class OrderPlaceAfter implements ObserverInterface
{
    protected $curl;

    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        $data = [
            'order_id' => $order->getId(),
            'total' => $order->getGrandTotal(),
            'customer_email' => $order->getCustomerEmail()
        ];

        $this->curl->post(
            'http://localhost:8000/api/orders',
            json_encode($data)
        );
    }
}