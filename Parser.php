<?php

class Parser
{
    public function parse($url)
    {
        libxml_use_internal_errors(true);

        // Установка конфигурации tidy
        $config = array(
            'indent'         => true,
            'output-xhtml'   => true,
            'wrap'           => 200);

        $agencyLinks = array();

        $encoding = "utf8";

        $this->getAgencyInfo($config, 'http://www.holiday.by/agencies/altatur', $encoding);
        die();

        for($i = 1; $i <= 10; $i++) {
            $innerUrl = $url . "?p=$i";
            $agencyLinks = $this->getAgencyLinks($config, $innerUrl, $encoding, $agencyLinks);
        }

        var_dump($agencyLinks);

        $agencies = array();
        foreach($agencyLinks as $key => $val) {
            $agencies[] = $this->getAgencyInfo($config, $val, $encoding);
        }


    }

    private function getAgencyLinks($config, $url, $encoding, $resultLinks = array())
    {
        // Tidy
        $tidy = new tidy;
        $tidy->parseFile($url, $config, $encoding);
        $doc = new DOMDocument();
        $doc->loadHTML($tidy->value);
        $xpath = new DOMXpath($doc);

        $agencyLink = $xpath->query("(.//a[@class='title'])[position()>4]");
        var_dump($agencyLink->length);
        foreach($agencyLink as $key => $element) {
            $resultLinks[] = $element->attributes->getNamedItem('href')->value;
        }

        return $resultLinks;
    }

    private function getAgencyInfo($config, $url, $encoding)
    {
        // Tidy
        $tidy = new tidy;
        $tidy->parseFile($url, $config, $encoding);
        $doc = new DOMDocument();
        $doc->loadHTML($tidy->value);
        $xpath = new DOMXpath($doc);

        $name = $xpath->query(".//h1");
        var_dump(iconv('Windows-1252', 'utf-8', $name->item(0)->nodeValue));
    }
}