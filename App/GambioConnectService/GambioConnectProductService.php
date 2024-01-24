<?php

namespace GXModules\Makaira\GambioConnect\App\GambioConnectService;

use GXModules\Makaira\GambioConnect\App\Documents\MakairaProduct;
use GXModules\Makaira\GambioConnect\App\GambioConnectService;
use GXModules\Makaira\GambioConnect\Service\GambioConnectEntityInterface;

class GambioConnectProductService extends GambioConnectService implements GambioConnectEntityInterface
{
    public function export(int $productId = null): void
    {
        
        $lang = 2;
        if ($productId === null) {
            $this->exportAll();
        } else {
            
            $product = $this->connection->fetchAllAssociative(
                '
                SELECT p.*, pd.products_name, pd.products_name, pd.products_description, pd.products_short_description, pd.products_url, pd.products_viewed
                  FROM ' . 'products' . ' p
             LEFT JOIN ' . 'products_description' . ' pd ON pd.products_id = p.products_id AND pd.language_id = "' . $lang . '"
             WHERE p.products_id ="' . $productId . '"'
            );
            
            
            $specificProductVariants = $this->variantReadService->getProductVariantsByProductId($productId);
            $document = new MakairaProduct($product[0], $specificProductVariants);
            $this->client->push_revision($document->addMakairaDocumentWrapper());
        }
    }
    
    
    
    public function exportAll(): void
    {
        
        $lang = 2;
        
        $products = $this->connection->fetchAllAssociative(
            '
            SELECT p.*, pd.products_name, pd.products_description, pd.products_short_description, pd.products_url, pd.products_viewed
			  FROM ' . 'products' . ' p
		 LEFT JOIN ' . 'products_description' . ' pd ON pd.products_id = p.products_id AND pd.language_id = "' . $lang . '"
         '
        );
        
        $this->logger->info(json_encode($products));
        
        foreach ($products as $product) {
            $specificProductVariants = $this->variantReadService->getProductVariantsByProductId((int) $product['products_id']);
            $document = new MakairaProduct($product, $specificProductVariants);
            $this->client->push_revision($document->addMakairaDocumentWrapper());
            
            $this->logger->info(json_encode($document->addMakairaDocumentWrapper()));
        }
        
        // $specificProductOption = $this->additionalOptionReadService->getAdditionalOptionsByProductId($productid);
        //  $this->logger->info(json_encode($prod));
        //$this->logger->info(json_encode($product->getName(new LanguageCode(new StringType('de')))));
        //$this->logger->info(json_encode($document->add_makaira_document_wrapper()));
    }
    
    public function exportByVariantId(int $variantId): void
    {
        $specificProductVariants = $this->variantReadService->getProductVariantById($variantId);
        $this->export($specificProductVariants->productId());
    }
    
    public function replace(): void
    {
        $this->client->rebuild(['products']);
    }
    
    public function switch(): void
    {
        $this->client->switch(['products']);
    }
}