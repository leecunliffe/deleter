<?php

require_once('../includes/includes.php');

$url = $_GET['u'];
?>
<hr />
<?
$site = Site::find_or_create_by_url($url);

?>
<hr />
Editing <?=$site->url?>. View at <a href='http://<?=$_SERVER['SERVER_NAME']?>/page.php?u=<?=urlencode($site->url)?>' />http://<?=$_SERVER['SERVER_NAME']?>/page.php?u=<?=urlencode($site->url)?></a>
<br />
<form action="site_update.php" method='post'>
  <input type='hidden' name='site_id' value='<?=$site->id?>' />
  <textarea name='css' style='width:90%; height:200px;'><?=$site->css?></textarea>
  <br />
  <input type='submit' value='Save' />
</form>

