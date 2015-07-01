<?php

require_once('Agency.php');

class Parser
{
    public function parse($url)
    {
        libxml_use_internal_errors(true);

        /*$agencyLinks = array();
        for($i = 1; $i <= 10; $i++) {
            $innerUrl = $url . "?p=$i";
            $agencyLinks = $this->getAgencyLinks($innerUrl, $agencyLinks);
        }*/

        $main_url = explode('/', $url);
        $this->getAgencyInfo('http://www.holiday.by/agencies/tisjacha');
        die();
        $agencies = array();
        foreach($agencyLinks as $key => $val) {
            $agencies[] = $this->getAgencyInfo('http://' . $main_url[2] . $val);
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
        echo str_replace('Турфирма ', '', $name->item(0)->nodeValue) . "<br/>";

        $certificateNumber = $xpath->query('.//div[@class="cert"]/div[@class="no"]');
        echo $certificateNumber->item(0)->nodeValue . "<br/>";
        $certificateSince = $xpath->query('.//div[@class="cert"]/div[@class="created_at"]');
        echo str_replace('Выдан: ', '', $certificateSince->item(0)->nodeValue . "<br/>");
        $certificateTo = $xpath->query('.//div[@class="cert"]/div[@class="expire_at"]');
        echo str_replace('Действителен до: ', '', $certificateTo->item(0)->nodeValue . "<br/>");

        $city = $xpath->query('.//div[@class="opinions-block"]/following::p');
        echo $city->item(0)->nodeValue . "<br/>";
        $address = $xpath->query(".//u[1]");
        echo $address->item(0)->nodeValue . "<br/>";

        $website = $xpath->query('(.//div[@class="info-section"][1]/table/tr/td/p/a[@href="javascript://"])[2]');
        echo $website->item(0)->nodeValue . "<br/>";

        $worktime = $xpath->query('.//div[@class="info-section"][1]/table/tr/td/p[last()]');
        echo str_replace('Время работы: ', '', $worktime->item(0)->nodeValue . "<br/>");

        $phones = $xpath->query('.//span[@title="Velcom"]');

        die();
    }
}