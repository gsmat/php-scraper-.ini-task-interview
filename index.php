<?php
// Include the necessary files
use App\DefactoProduct;
use App\ProductScraper;
use App\TrendyolProduct;

require './app/ProductScraper.php';
require './app/DefactoProduct.php';
require './app/TrendyolProduct.php';
try {



// DeFacto product scraping
    $url = "https://www.defacto.com.tr/kiz-cocuk-regular-fit-uzun-kollu-gomlek-2925255";
    $scraper = new ProductScraper($url);
    $defactoProduct = new DefactoProduct($scraper->getXPath());
    $defactoData = $defactoProduct->parseProductData();

    echo "DeFacto Product:\n";
    print_r($defactoData);

// Trendyol product scraping
    $url = "https://www.trendyol.com/ezem-store/katlanabilen-kadin-cantasi-p-835106809?boutiqueId=61&merchantId=928536&sav=true&v=42";
    $scraper = new ProductScraper($url);
    $trendyolProduct = new TrendyolProduct($scraper->getXPath(), $url);
    $trendyolData = $trendyolProduct->parseProductData();

    echo "\nTrendyol Product:\n";
    print_r($trendyolData);

}catch (Exception $e){
    echo $e->getMessage();
}