1. Magento 2 E-Commerce Integration Module

This repository contains the **Magento 2.4.6 module** for E-Commerce integration with a Laravel backend. The module adds custom product attributes, REST APIs, webhooks for order and product sync, and an admin configuration panel for integration settings.

---

2. Requirements

- PHP 8.1.17  
- Magento 2.4.6  
- MySQL or MariaDB  
- Elasticsearch/OpenSearch  
- Composer  
- XAMPP (Windows)  
- Laravel backend URL for integration  

---

3. Installation Instructions (Windows / XAMPP)

3.1 Clone the repository

```bash
git clone https://github.com/Seif-Moustafa-Hassan/magento-ecommerce-task.git
cd magento-ecommerce-task

3.2 Copy module to Magento app/code

xcopy /E /I Vendor\IntegrationModule <magento-root>\app\code\Vendor\IntegrationModule

3.3 Enable module

cd <magento-root>
php bin/magento module:enable Vendor_IntegrationModule
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush

3.4 Deploy static content

php bin/magento setup:static-content:deploy -f

4. Module Features

4.1 Custom Product Attribute

Attribute: external_product_id
Created programmatically during module setup (InstallData.php)
Appears in Admin > Catalog > Products under Product Details
Access in code:
PHP: $product->getCustomAttribute('external_product_id')->getValue();

4.2 Custom REST API Endpoints

Get products: GET /rest/V1/integration/products
Returns: id, sku, name, price, qty, external_product_id

Update product stock: POST /rest/V1/integration/product-stock/update
Example payload:
{
  "sku": "ABC123",
  "qty": 20,
  "is_in_stock": true
}

Update order status: POST /rest/V1/integration/order-status/update
Example payload:
{
  "order_id": 101,
  "increment_id": "000000101",
  "status": "processing",
  "tracking_number": "TRACK123"
}

4.3 Order Webhook Design

Magento triggers Laravel webhook on events: order.created, order.updated, order.shipped, order.cancelled
Payload includes order details:
{
  "order_id": 101,
  "increment_id": "000000101",
  "customer": {
    "name": "John Doe",
    "email": "john@example.com"
  },
  "items": [
    {"sku": "ABC123", "qty": 1, "price": 100}
  ],
  "subtotal": 100,
  "grand_total": 105,
  "status": "processing",
  "created_at": "2026-03-12T10:00:00Z"
}

4.4 Admin Configuration

Accessible via: Admin > Stores > Configuration > Integration Settings

Settings:
Laravel Base URL – Backend URL for integration
API Token / Secret Key – Secure communication key
Enable / Disable Integration – Activate or deactivate sync
Product Sync Endpoint – Configure endpoint for product export/import


4.5 Product Export / Import

Export to Laravel: Sends product data including sku, name, price, qty, category_ids, external_product_id, updated_at

Import from Laravel: Updates existing products by SKU or creates new products. Imported fields include sku, name, price, special_price, qty, status, description, short_description, category assignment, and external_product_id.


5. Testing API Endpoints

Use Postman or any API client to test endpoints.
Example product sync request:

```HTTP
GET http://localhost/magento2/rest/V1/integration/products
Authorization: Bearer <admin-token>

```HTTP
POST http://localhost/magento2/rest/V1/integration/order-status/update
Authorization: Bearer <admin-token>
Content-Type: application/json
Body: { "order_id": 101, "status": "shipped" }

6. Notes / Tips

Ensure Elasticsearch/OpenSearch is running for product indexing.
Clear cache after any configuration change:

7. Author

Seif Moustafa
GitHub: https://github.com/Seif-Moustafa-Hassan
Email: seif.moustafa2001@gmail.com
