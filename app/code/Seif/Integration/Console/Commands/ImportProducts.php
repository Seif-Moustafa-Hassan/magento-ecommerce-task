<?php
namespace Seif\Integration\Console\Command;

use Magento\Framework\Console\Cli;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Seif\Integration\Helper\LaravelApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportProducts extends Command
{
    protected $state;
    protected $laravelApi;
    protected $productFactory;
    protected $productResource;

    public function __construct(
        State $state,
        LaravelApi $laravelApi,
        ProductFactory $productFactory,
        ProductResource $productResource
    ) {
        $this->state = $state;
        $this->laravelApi = $laravelApi;
        $this->productFactory = $productFactory;
        $this->productResource = $productResource;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('integration:import-products')
             ->setDescription('Import products from Laravel into Magento');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(Area::AREA_GLOBAL);

        $products = $this->laravelApi->getProducts();

        foreach ($products as $data) {
            $sku = $data['sku'];

            $product = $this->productFactory->create();
            $this->productResource->load($product, $sku, 'sku');

            // Update or create product
            $product->setSku($sku);
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setCustomAttribute('external_product_id', $data['external_product_id']);
            $product->setStatus($data['status']);
            $product->setTypeId('simple'); // simple product
            $product->setVisibility(4); // catalog + search
            $product->setStockData(['qty' => $data['qty'], 'is_in_stock' => ($data['qty'] > 0 ? 1 : 0)]);

            try {
                $this->productResource->save($product);
                $output->writeln("Saved product: " . $sku);
            } catch (\Exception $e) {
                $output->writeln("Failed to save: " . $sku . " - " . $e->getMessage());
            }
        }

        return Cli::RETURN_SUCCESS;
    }
}