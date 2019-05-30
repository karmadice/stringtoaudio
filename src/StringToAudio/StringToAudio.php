<?php

namespace StringToAudio;

use GuzzleHttp\Client;
use StringToAudio\Source;

class StringToAudio
{
    const BASE_URL = "http://translate.google.com/translate_tts?";
    
    private $_client;
    private $_source;
    private $_language;
    
    public function __construct($language = "en-us")
    {
        $this->_client = new Client();
        $this->_language = $language;
        $this->_source = new Source();
    }
    
    public function getAudio($string)
    {
        if( !$this->_language ) {
            throw new \Exception("You must provide language");
        }
        
        if( !$this->_source->getLocation() ) {
            throw new \Exception("Location to store audio is not defined");
        }
        
        if( !$string ) {
            throw new \Exception("Text string is not provided");
        }

        if (strlen($string) > 100) {
            throw new \Exception("Text string must not be greater than 100 characters");
        }
        
        $location = $this->_source->getLocation();

        if (!is_dir($location)) {
            $generated = mkdir($location, 0775, true);
            if (!$generated) {
                throw new \Exception("Failed to generate directory at given location");
            }
        } else if (!is_writable($location)) {
            throw new \Exception("We do not have write permission for given destination");
        }
        
        $uri = $this->buildUrl($string);

        $response = $this->_client->get($uri, array(
            'headers' => array(
                'Referer' => 'http://translate.google.com/',
                'User-Agent' => 'stagefright/1.2 (Linux;Android 5.0)',
                'Content-type' => 'audio/mpeg'
            ),
            'save_to' => $this->_source->getFullLocation()
        ));

        if ($response->getStatusCode() == 200) {
            return true;
        }
        throw new \Exception("Something went wrong while communicating with Google Translator");
    }

    /**
     *
     * @param  $language
     * @return $this
     */
    public function setLanguage($language) {
        $this->_language = $language;
        return $this;
    }

    /**
     *
     * @param $location
     * @return $this
     */
    public function setStorageLocation($location) {
        $this->_source->setLocation($location);
        return $this;
    }

    /**
     *
     * @param $name
     * @return $this
     */
    public function setFilename($name) {
        $this->_source->setName($name);
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getFullLocation() {
        return $this->_source->getFullLocation();
    }

    /**
     *
     * @param string $text
     * @return string | url
     */
    public function buildUrl($string) {
        $params = [
            'ie' => 'UTF-8',
            'q' => $string,
            'client' => 'tw-ob',
            'tl' => $this->_language
        ];

        return self::BASE_URL . http_build_query($params);
    }
}