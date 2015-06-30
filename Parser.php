<?php

class Parser
{
    public function parse($url)
    {
        libxml_use_internal_errors(true);

        $agencyLinks = array();
        for($i = 1; $i <= 10; $i++) {
            $innerUrl = $url . "?p=$i";
            $agencyLinks = $this->getAgencyLinks($innerUrl, $agencyLinks);
        }

        var_dump($agencyLinks);

        $agencies = array();
        foreach($agencyLinks as $key => $val) {
            $agencies[] = $this->getAgencyInfo($val);
        }
    }

    private function getAgencyLinks($url, $resultLinks = array())
    {
        $doc = new DOMDocument();
        $doc->loadHTML(file_get_contents($url));
        $xpath = new DOMXpath($doc);

        $agencyLink = $xpath->query("(.//a[@class='title'])[position()>4]");
        foreach($agencyLink as $key => $element) {
            $resultLinks[] = $element->attributes->getNamedItem('href')->value;
        }

        return $resultLinks;
    }

    private function getAgencyInfo($url)
    {
        $doc = new DOMDocument();
        $doc->loadHTML(file_get_contents($url));
        $xpath = new DOMXpath($doc);

        $name = $xpath->query(".//h1");
        echo $name->item(0)->nodeValue;
        die();
    }
}