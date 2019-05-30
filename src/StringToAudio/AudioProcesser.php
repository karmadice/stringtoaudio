<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019-05-30
 * Time: 12:50 PM
 */

namespace StringToAudio;

use getID3;

class AudioProcesser
{
    private $_object;
    private $_extension;
    
    public function __construct()
    {
        $this->_object = new getID3();
        $this->_extension = "mp3";
    }

    public function combileMp3($files, $output, $location)
    {
        foreach ($files as $input) {
            if( is_array($input) ) {
                $input = $input[0];
            }
        }
        
        if( !is_readable($input) ) {
            throw new \Exception($input. " is not redable");
            return FALSE;
        }

        if (( file_exists($output) && !is_writable($output) ) || (!file_exists($output) && !is_writable(dirname($output)) )) {
            echo $output . ' is not writable';
            return FALSE;
        }
        
        ob_start();
        if( $fpOutput = fopen($location . DIRECTORY_SEPARATOR . $output, "wb") ){
            ob_end_clean();
            foreach ($files as $input) {
                $startOffset = 0;
                $lengthSeconds = 0;
                if( is_array( $input ) ) {
                    @list($input, $startOffset, $lengthSeconds) = $input;
                }
                $currentFileInfo = $this->_object->analyze($input);
                if( $currentFileInfo['fileformat'] === 'mp3') {
                    ob_start();
                    if( $fpSource = fopen($input, 'rb') ) {
                        ob_end_clean();
                        $currentOutputPosition = ftell($fpOutput);
                        // copy audio data from first file
                        $startOffsetBytes = $currentFileInfo['avdataoffset'];
                        if( $startOffset > 0 ) { // start X seconds from start of audio
                            $startOffsetBytes = $currentFileInfo['avdataoffset'] + round( $currentFileInfo['bitrate'] / 8 * $startOffset );
                        } else if( $startOffset < 0 ) { // start X seconds from end of audio
                            $startOffsetBytes = $currentFileInfo['avdataend'] + round( $currentFileInfo['bitrate'] / 8 * $startOffset );
                        }
                        
                        $startOffsetBytes = max( $currentFileInfo['avdataoffset'], min( $currentFileInfo['avdataend'], $startOffsetBytes ) );
                        
                        $endOffsetBytes = $currentFileInfo['avdataend'];
                        
                        if( $lengthSeconds > 0 ){ // Seconds from start
                            $endOffsetBytes = $startOffsetBytes + round( $currentFileInfo['bitrate'] / 8 * $lengthSeconds );
                        } else if( $lengthSeconds < 0 ) { // seconds from end of audio
                            $endOffsetBytes = $currentFileInfo['avdataend'] + round( $currentFileInfo['bitrate'] / 8 * $lengthSeconds );
                        }
                        
                        $endOffsetBytes = max( $currentFileInfo['avdataoffset'], min( $currentFileInfo['avdataend'], $endOffsetBytes ) );
                        
                        if( $endOffsetBytes <= $startOffsetBytes ) {
                             echo "Failed to copy ". $input . " from ". $startOffset . "-seconds start for " . $lengthSeconds . "-seconds length (not enough data)";
                             fclose($fpSource);
                             fclose($fpOutput);
                             return FALSE;
                        }
                        
                        fseek( $fpSource, $startOffsetBytes, SEEK_SET );
                        while ( !feof( $fpSource ) && (ftell( $fpSource ) < $endOffsetBytes) ) {
                            fwrite( $fpOutput, fread( $fpSource, min( 32768, $endOffsetBytes - ftell($fpSource) ) ) );
                        }
                        fclose($fpSource);
                        unlink( $input );
                    } else {
                        $errorMessage = ob_get_contents();
                        ob_end_clean();
                        fclose($fpOutput);
                        throw new \Exception("Failed to open " . $input . " for reading");
                        return FALSE;
                    }
                } else {
                    fclose( $fpOutput );
                    throw new \Exception($input . " is not MP3 format");
                    return FALSE;
                }
                
            }
        } else {
            $errorMessage = ob_get_contents();
            ob_end_clean();
            throw new \Exception("Failed to open " . $output . " for writing");
            return FALSE;
        }
        fclose($fpOutput);
        return $location . DIRECTORY_SEPARATOR . $output;
    }

    public function setExtenstion( $extension ) {
        $this->_extension = $extension;
        return $this;
    }
}