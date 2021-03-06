<?php

/**
 * Created by PhpStorm.
 * User: riese
 * Date: 10/12/2016
 * Time: 7:11 PM
 * Simple class to aid in scraping content off of websites.
 *
 * $scraper = new Scraper();
 * $scraper->setUrl('http://www.google.com');
 * $scraper->scrape();
 *
 */
class Scraper
{
    private $url;
    private $curl;
    public function __construct($url = null)
    {
        $this->url = $url;
    }

    public function scrape()
    {
        if(!isset($this->url))
        {
            throw new Exception("Scraper: No url was set");
        }
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 15);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);

        if (!($result = @curl_exec($this->curl)))
        {
            throw new Exception("Scraper: Error executing request '" . curl_error($this->curl) . "'");
        }

        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        if($responseCode >= 400)
        {
            throw new Exception("Scraper: The host responded with '" . $responseCode . "' error code.");
        }

        return $result;
    }

    public function setURL($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);
    }


}