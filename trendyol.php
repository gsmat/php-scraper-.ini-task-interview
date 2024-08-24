<?php

/*use App\ProductScraper;

require __DIR__ . '/vendor/autoload.php';

$url = "https://www.defacto.com.tr/";
$scrape = new ProductScraper($url);

print_r($scrape->parseProductData());*/
$ch = curl_init();

$url = "https://www.trendyol.com/ezem-store/katlanabilen-kadin-cantasi-p-835106809?boutiqueId=61&merchantId=928536&sav=true&v=42";

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$html = curl_exec($ch);

curl_close($ch);

$parsedUrl = parse_url($url);
parse_str($parsedUrl['query'], $queryParams);

$productSize = isset($queryParams['v']) ? $queryParams['v'] : null;

$doc = new DOMDocument();

libxml_use_internal_errors(true);

$doc->loadHTML($html);

libxml_clear_errors();

$xpath = new DOMXPath($doc);

$ogTitle = $xpath->query("//meta[@property='og:title']");
$ogDescription = $xpath->query("//meta[@property='og:description']");
$ogImage = $xpath->query("//meta[@property='og:image']");

$prcDscElements = $xpath->query("//*[contains(@class, 'prc-dsc')]");

function getMetaContent($nodeList)
{
    if ($nodeList->length > 0) {
        return $nodeList->item(0)->getAttribute('content');
    }
    return null;
}

$title = getMetaContent($ogTitle);
$description = getMetaContent($ogDescription);
$image = getMetaContent($ogImage);

function getClassContent($nodeList)
{
    $values = [];
    foreach ($nodeList as $node) {
        $values[] = trim($node->textContent);
    }
    return $values;
}

$prcDscValues = getClassContent($prcDscElements);

echo "Title: " . $title . "\n";
echo "Description: " . $description . "\n";
echo "Image URL: " . $image . "\n";

if ($productSize) {
    echo "Product Size: " . $productSize . "\n";
} else {
    echo "Product Size: Not available\n";
}

if (!empty($prcDscValues)) {
    echo "Price Description";
    foreach ($prcDscValues as $value) {
        echo "- " . $value . "\n";
    }
} else {
    echo "Price Description: Not available\n";
}


