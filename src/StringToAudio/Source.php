<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019-05-30
 * Time: 12:31 PM
 */

namespace StringToAudio;


class Source
{
    private $_location;
    private $_name;
    private $_extension;
    
    public function __construct()
    {
        $this->_extension = "mp3";
        $this->_name = "textaudio";
    }

    /**
     * Get full path for audio source
     * 
     * @return string
     */
    public function getFullLocation()
    {
        return $this->_location . DIRECTORY_SEPARATOR . $this->_name . "." . $this->_extension;
    }

    /**
     * Get directory location for audio source
     * 
     * @return mixed
     */
    public function getLocation()
    {
        return $this->_location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->_location = $location;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed string
     */
    public function getExtension()
    {
        return $this->_extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->_extension = $extension;
    }
}