<?php

$products = [
    [
        "name" => "Test Product",
        "price" => 100,
        "sku" => "TEST-1"
    ]
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8000/api/products/sync");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['products'=>$products]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

echo $response;