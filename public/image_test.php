<?php

require_once('../includes/includes.php');

//$i = Image::find_or_create_by_url('http://new.artbash.co.nz/system/ats/10326/380x240h/ducks.jpg');
//$i = Image::find_or_create_by_url('http://christchurchartgallery.org.nz/media/i/header.png');
//<img src='<?=$i->s3_url()
//$i = new Image (0, 0, 'iPhoto.png');
$i = new Image (1, 0, 'http://christchurchartgallery.org.nz/media/cache/68/47/684738e933adfc3ef0e3371fbb9b6856.jpg');
//$i = new Image (0, 0, 'http://www.astromag.co.uk/images/vertical.jpg');
$i->cross_out();
$i->save_s3();
$outputtype = $i->imagick->getFormat();
header("Content-type: $outputtype");
echo $i->imagick;
?>

