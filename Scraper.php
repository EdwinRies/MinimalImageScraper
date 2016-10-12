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
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 15);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if (!($result = @curl_exec($curl)))
        {
            throw new Exception("Scraper: Error executing request '" . curl_error($curl) . "'");
        }

        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

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


}