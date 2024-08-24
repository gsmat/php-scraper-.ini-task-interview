<?php

namespace App;
class TrendyolProduct
{
    private $xpath;
    private $url;

    public function __construct($xpath, $url)
    {
        $this->xpath = $xpath;
        $this->url = $url;
    }

    public function parseProductData()
    {
        $title = $this->getMetaContent('og:title');
        $description = $this->getMetaContent('og:description');
        $image = $this->getMetaContent('og:image');
        $price = $this->getClassContent('prc-dsc');
        $size = $this->getProductSize();

        return [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'price' => $price,
            'size' => $size,
        ];
    }

    private function getMetaContent($property)
    {
        $meta = $this->xpath->query("//meta[@property='$property']");
        return $meta->length > 0 ? $meta->item(0)->getAttribute('content') : 'Not available';
    }

    private function getClassContent($className)
    {
        $nodeList = $this->xpath->query("//*[contains(@class, '$className')]");
        $values = [];
        foreach ($nodeList as $node) {
            $values[] = trim($node->textContent);
        }
        return $values ?: ['Not available'];
    }

    private function getProductSize()
    {
        $parsedUrl = parse_url($this->url);
        parse_str($parsedUrl['query'], $queryParams);
        return isset($queryParams['v']) ? $queryParams['v'] : 'Not available';
    }
}