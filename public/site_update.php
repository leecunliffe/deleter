<?
require_once('../includes/includes.php');

$site = Site::find_by_id($_POST['site_id']);
$site->css = $_POST['css'];
$site->save();
header( 'Location: http://'.$_SERVER['SERVER_NAME'].'/site.php?u='.urlencode($site->url) ) ;

?>
