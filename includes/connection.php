<?php

$mysql_conn = mysql_connect('localhost', DB_USER, DB_PASS);
mysql_select_db(DB_NAME) or die ("no db");

function get_res($sql) {
  $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
  return $result;
}

?>
