<?php

namespace Tests;

use App\DefactoProduct;
use PHPUnit\Framework\TestCase;

class DeFactoProductTest extends TestCase
{
    private $xpath;
    private $deFactoProduct;

    public function testParseProductData()
    {
        $data = $this->deFactoProduct->parseProductData();
        $this->assertEquals('Test Title', $data['title']);
        $this->assertEquals('Test Description', $data['description']);
        $this->assertEquals('http://example.com/image.jpg', $data['image']);
        $this->assertEquals(['124,99'], $data['price']);
        $this->assertEquals('11/12 Yaş', $data['size']);
    }

    protected function setUp(): void
    {
        // Mocking DOMDocument and DOMXPath
        $dom = new \DOMDocument();
        @$dom->loadHTML('<html>
        <head>
        <meta charset="UTF-8">
            <meta property="og:title" content="Test Title">
            <meta property="og:description" content="Test Description">
            <meta property="og:image" content="http://example.com/image.jpg">
        </head>
        <body>
            <span class="sale d-inline-flex align-items-baseline">124,99</span>
            <div class="product-size-selector__buttons d-flex flex-wrap" title="Test">
                <button type="button" title="Lütfen beden seçiniz." class="button-reset cross-border-button" value="5/6 Yaş" aria-describedby="">  5/6 Yaş</button>
                <button type="button" title="Lütfen beden seçiniz." class="button-reset cross-border-button " value="7/8 Yaş" aria-describedby="">  7/8 Yaş</button>
                <button type="button" title="Lütfen beden seçiniz." class="button-reset cross-border-button" value="8/9 Yaş" aria-describedby="">  8/9 Yaş</button>
                <button type="button" title="Lütfen beden seçiniz." class="button-reset cross-border-button" value="9/10 Yaş">  9/10 Yaş</button>
                <button type="button" title="Lütfen beden seçiniz." class="button-reset cross-border-button active last-selected" value="11/12 Yaş" aria-describedby="">  11/12 Yaş</button>
                <button type="button" title="Lütfen beden seçiniz." class="button-reset cross-border-button" value="13/14 Yaş" aria-describedby="">  13/14 Yaş</button>
            </div>
        </body>
    </html>');
        $this->xpath = new \DOMXPath($dom);
        $this->deFactoProduct = new DeFactoProduct($this->xpath);
    }
}
