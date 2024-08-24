<?php
$ch = curl_init();

$url = "https://www.defacto.com.tr/kiz-cocuk-regular-fit-uzun-kollu-gomlek-2925255";

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$html = curl_exec($ch);

curl_close($ch);

$parsedUrl = parse_url($url);

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

function getClassContent($xpath, $className)
{
    $nodeList = $xpath->query("//*[contains(@class, '$className')]");
    $values = [];
    foreach ($nodeList as $node) {
        $values[] = trim($node->textContent);
    }
    return $values;
}

$salePrice = getClassContent($xpath, 'sale d-inline-flex align-items-baseline');

if (empty($salePrice)) {
    $salePrice = getClassContent($xpath, 'product-card__price--new d-inline-flex align-items-baseline');
}

$activeButton = $xpath->query("//div[contains(@class, 'product-size-selector__buttons')]//button[contains(@class, 'active')]");

// Extract the value of the active button if it exists
$activeButtonValue = $activeButton->length > 0 ? trim($activeButton->item(0)->getAttribute('value')) : 'Not available';

echo "Title: " . $title . "\n";
echo "Description: " . $description . "\n";
echo "Image URL: " . $image . "\n";
echo "Size: " . $activeButtonValue . "\n";

if (!empty($salePrice)) {
    echo "Price Description:\n";
    foreach ($salePrice as $value) {
        echo "- " . $value . "\n";
    }
} else {
    echo "Price Description: Not available\n";
}
