<?php
require_once "bootstrap.php";

use StringToAudio\StringToAudio;
use StringToAudio\AudioProcesser;

if( isset($_POST) && !empty( $_POST ) ) {
    $inputString = $_POST['inputstring'];
    
    // init
    $object = new StringToAudio();
    
    $i = 1;
    $files = array();
    $outputFileName = $_POST['outputname'];
    $outputFileLocation = $_POST['outputlocation'];
    $object->setStorageLocation($outputFileLocation);
    if( strlen($inputString) > 100 ) {
        // Split long string into small chunks
        $stringChunks = str_split($inputString, 100);
        foreach ( $stringChunks as $chunks ) {
            $object->setFilename($outputFileName . $i);
            $object->getAudio($chunks);
            $files[] = $object->getFullLocation();
            $i++;
        }
        $processor = new AudioProcesser();
        $audio = $processor->combileMp3($files, $outputFileName, $outputFileLocation);
    } else {
        $object->setFilename($outputFileName);
        $object->getAudio($inputString);
        $audio = $object->getFullLocation();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>String to Audio</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="bg-light">

    <div class="container">
        <div class="py-5 text-center">
            <h2>String to Audio converter</h2>
            <p class="lead">Convert text of any length to audio using google translator</p>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="POST" class="needs-validation">
                    <div class="form-group">
                        <label for="text-to-convert">Text to convert to audio</label>
                        <textarea class="form-control" name="inputstring" id="text-to-convert" tabindex="1" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="output-name">Output file name</label>
                        <input type="text" id="output-name" name="outputname" class="form-control" required tabindex="2" placeholder="Enter name for the output audio file"/>
                    </div>
                    <div class="form-group">
                        <label for="output-location">Output file location</label>
                        <input type="text" name="outputlocation" id="output-location" class="form-control" required tabindex="3" placeholder="Enter location for the output audio file"/>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <h5 class="card-header">
                        Output
                    </h5>
                    <div class="card-body">
                        <?php if( isset( $audio ) && $audio != ""): ?>
                            <audio src="<?php echo $audio; ?>" controls type="audio/mpeg"></audio>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>
