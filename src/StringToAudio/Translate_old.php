<?php


namespace StringToAudio;

use GuzzleHttp\Client;

class TranslateOld
{
    const BASE_URL = "https://translate.google.com/translate_a/single?";

    private $_client;
    private $_source;
    private $_language;
    private $_translation_language;

    public function __construct($language = "en-us", $translationLanguage = "en-us")
    {
        $this->_client = new Client();
        $this->_language = $language;
        $this->_translation_language = $translationLanguage;
        $this->_source = new Source();
    }

    public function getTranslatedString($string)
    {
        $uri = $this->buildUrl();
        $response = $this->_client->post($uri,[
            'form_params' => [
                'q' => $string
            ]
        ]);
        $body = $response->getBody();
        return $response;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->_language = $language;
    }

    /**
     * @param string $translation_language
     */
    public function setTranslationLanguage($translation_language)
    {
        $this->_translation_language = $translation_language;
    }

    public function buildUrl()
    {
        $params = [
            'ie' => 'UTF-8',
            'client' => 'tw-ob',
            'tl' => $this->_translation_language,
            'hl' => $this->_language
        ];

        return self::BASE_URL . http_build_query($params);
    }

}