<?php

namespace App;

class DefactoProduct
{
    private $xpath;

    public function __construct($xpath)
    {
        $this->xpath = $xpath;
    }

    public function parseProductData()
    {
        $title = $this->getMetaContent('og:title');
        $description = $this->getMetaContent('og:description');
        $image = $this->getMetaContent('og:image');
        $price = $this->getClassContent('sale d-inline-flex align-items-baseline');

        if (empty($price)) {
            $price = $this->getClassContent('product-card__price--new d-inline-flex align-items-baseline');
        }

        $size = $this->getActiveButtonContent('product-size-selector__buttons', 'active');

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

    private function getClassContent($className): array
    {
        $nodeList = $this->xpath->query("//*[contains(@class, '$className')]");
        $values = [];
        foreach ($nodeList as $node) {
            $values[] = trim($node->textContent);
        }
        return $values ?: ['Not available'];
    }

    private function getActiveButtonContent($wrapperClass, $activeClass): string
    {
        $button = $this->xpath->query("//div[contains(@class, '$wrapperClass')]//button[contains(@class, '$activeClass')]");
        return $button->length > 0 ? trim($button->item(0)->getAttribute('value')) : 'Not available';
    }
}