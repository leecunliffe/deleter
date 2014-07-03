<?php

require_once('../includes/includes.php');

// ONLY works for GET!!!!!!!!!!

$url = $_GET['rm_base_url'];
$page = new Page($url);
$site_url = urldecode($_GET['rm_base_url']).urldecode($_GET['rm_action_url']);
$site_url = preg_replace('/(.*)\?.*/', '$1', $site_url);
unset($_GET['rm_base_url']);
unset($_GET['rm_action_url']);
$site_url .= "?".http_build_query($_GET);
header( 'Location: page.php?u='.urlencode($site_url));
