<?php

namespace tests;

use App\TrendyolProduct;
use PHPUnit\Framework\TestCase;

class TrendyolProductTest extends TestCase
{
    private $xpath;
    private $trendyolProduct;

    protected function setUp(): void
    {
        // Mocking DOMDocument and DOMXPath
        $dom = new \DOMDocument();
        $dom->loadHTML('<html>
            <head>
                <meta property="og:title" content="Test Title">
                <meta property="og:description" content="Test Description">
                <meta property="og:image" content="http://example.com/image.jpg">
            </head>
            <body>
                <span class="prc-dsc">Test Price</span>
                <a href="/product?category=clothing&v=Large">Product Link</a>
            </body>
        </html>');
        $this->xpath = new \DOMXPath($dom);
        $this->trendyolProduct = new TrendyolProduct($this->xpath, '/product?category=clothing&v=Large');
    }

    public function testParseProductData()
    {
        $data = $this->trendyolProduct->parseProductData();

        $this->assertEquals('Test Title', $data['title']);
        $this->assertEquals('Test Description', $data['description']);
        $this->assertEquals('http://example.com/image.jpg', $data['image']);
        $this->assertEquals(['Test Price'], $data['price']);
        $this->assertEquals('Large', $data['size']);
    }
}
