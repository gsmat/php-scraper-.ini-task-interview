<?php

namespace App;
class ProductScraper
{
    private $url;
    private $html;
    private $xpath;

    public function __construct($url)
    {
        $this->url = $url;
        $this->fetchHTML();
        $this->loadDOM();
    }

    private function fetchHTML()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $this->html = curl_exec($ch);
        curl_close($ch);
    }

    private function loadDOM()
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($this->html);
        libxml_clear_errors();
        $this->xpath = new \DOMXPath($doc);
    }

    public function getXPath()
    {
        return $this->xpath;
    }
}
