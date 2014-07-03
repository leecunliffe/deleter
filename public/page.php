<?php

require_once('../includes/includes.php');

$url = $_GET['u'];
$page = new Page($url);
if (substr($url,-3) == 'jpg' || substr($url,-3) == 'png') {
  if ($url == 'http://christchurchartgallery.org.nz/media/cache/19/1f/191f579f13861b82ad0957d2cd9f16ac.jpg') {
     //header( 'Location: http://s3.amazonaws.com/website_remixer_custom/rita.jpg');
     header( 'Location: http://deletedmuseums.org/rita.jpg');
  } else {
    $image = Image::find_or_create_by_url($url);
    header( 'Location: '.$image->s3_url() );
  }
} elseif ($url == 'http://christchurchartgallery.org.nz/media/uploads/2011_09/Rita_Angus_-_Cass_High_Qual.mp3') {
  header( 'Location: https://s3.amazonaws.com/website_remixer_custom/Rita_Angus_-_Cass_High_Qual+edited.mp3');
} else {
  echo $page->display();
}


