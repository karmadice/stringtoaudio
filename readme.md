# String To Audio

A wrapper for Google Translator that generates audio from string. It supports any number of characters.

```php
use GoogleStringToAudio\StringToAudio;

$speech = new StringToAudio();
$speech->setStorageLocation('audios');
$speech->setFilename('test');
$speech->getAudio('This is my first test');
echo 'File generated:' . $speech->getFullLocation() . '<br>';
```