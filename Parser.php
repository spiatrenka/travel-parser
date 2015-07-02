<?php

/**
 * Class Parser
 */
class Parser
{
    /**
     * @param $url
     * @return array
     */
    public function parse($url)
    {
        libxml_use_internal_errors(true);

        $agencyLinks = array();
        for($i = 1; $i <= 10; $i++) {
            $innerUrl = $url . "?p=$i";
            $agencyLinks = $this->getAgencyLinks($innerUrl, $agencyLinks);
        }

        $main_url = explode('/', $url);
        $agencies = array();
        foreach($agencyLinks as $key => $val) {
            $agencies[] = $this->getAgencyInfo('http://' . $main_url[2] . $val);
        }

        return $agencies;
    }

    /**
     * @param $url
     * @param array $resultLinks
     * @return array
     */
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

    /**
     * @param $url
     * @return Agency
     */
    private function getAgencyInfo($url)
    {
        echo $url . "<br/>";
        $doc = new DOMDocument();
        $doc->loadHTML(file_get_contents($url));
        $xpath = new DOMXpath($doc);

        $agency = new Agency;

        $name = $xpath->query(".//h1");
        $agency->name = str_replace('Турфирма ', '', $name->item(0)->nodeValue);

        $certificateNumber = $xpath->query('.//div[@class="cert"]/div[@class="no"]');
        $agency->certificate = $certificateNumber->item(0)->nodeValue;
        $certificateSince = $xpath->query('.//div[@class="cert"]/div[@class="created_at"]');
        $agency->certificateSince = str_replace('Выдан: ', '', $certificateSince->item(0)->nodeValue);
        $certificateTo = $xpath->query('.//div[@class="cert"]/div[@class="expire_at"]');
        $agency->certificateTo = str_replace('Действителен до: ', '', $certificateTo->item(0)->nodeValue);

        $city = $xpath->query('.//div[@class="opinions-block"]/following::p');
        $address = $xpath->query(".//u[1]");
        $agency->address = $city->item(0)->nodeValue . ' ' . $address->item(0)->nodeValue;

        $website = $xpath->query('(.//div[@class="info-section"][1]/table/tr/td/p/a[@href="javascript://"])[2]');
        $agency->website = $website->item(0)->nodeValue;

        $worktime = $xpath->query('.//div[@class="info-section"][1]/table/tr/td/p[last()]');
        $agency->workTime = str_replace('Время работы: ', '', $worktime->item(0)->nodeValue);

        $phonesWork = array();
        $phones = $xpath->query('.//span[@title="Городской"]');
        foreach ($phones as $val) {
            $phonesWork[] = $val->nodeValue;
        }
        $agency->phonesWork = implode("\r\n", $phonesWork);

        $phonesVelcom = array();
        $phones = $xpath->query('.//span[@title="Velcom"]');
        foreach ($phones as $val) {
            $phonesArray[] = $val->nodeValue;
        }
        $agency->phonesVelcom = implode("\r\n", $phonesVelcom);

        $phonesMts = array();
        $phones = $xpath->query('.//span[@title="MTC"]');
        foreach ($phones as $val) {
            $phonesMts[] = $val->nodeValue;
        }
        $agency->phonesMts = implode("\r\n", $phonesMts);

        $phonesLife = array();
        $phones = $xpath->query('.//span[@title="life :)"]');
        foreach ($phones as $val) {
            $phonesLife[] = $val->nodeValue;
        }
        $agency->phonesLife = implode("\r\n", $phonesLife);

        $icqArray = array();
        $icq = $xpath->query('.//img[@title="ICQ"]/parent::p');
        foreach ($icq as $val) {
            $icqArray[] = $val->nodeValue;
        }
        $agency->icq = implode("\r\n", $icqArray);

        $skypeArray = array();
        $skype = $xpath->query('.//img[@title="Skype"]/parent::p');
        foreach ($skype as $val) {
            $skypeArray[] = $val->nodeValue;
        }
        $agency->skype = implode("\r\n", $skypeArray);

        $needle = 'УНП';
        $text = $xpath->query('.//div[@class="text-block text"]');
        $agency->unp = substr($text->item(0)->nodeValue, strpos($text->item(0)->nodeValue, $needle) + strlen($needle));

        $agency->link = $url;

        return $agency;
    }
}

/**
 * Class Agency
 */
class Agency {
    public $name;
    public $certificate;
    public $certificateSince;
    public $certificateTo;
    public $address;
    public $website;
    public $phonesWork;
    public $phonesVelcom;
    public $phonesMts;
    public $phonesLife;
    public $workTime;
    public $icq;
    public $skype;
    public $unp;
    public $link;
}