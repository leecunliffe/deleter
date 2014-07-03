<?php
require_once "../models/page.php";

class Site {
  public $url;
  public $css;
  public $id;

 function  __construct($url, $css, $id) {
    $this->url = $url;
    $this->css = $css;
    $this->id = $id;
  }

  public function save() {
    $sql = "UPDATE sites set url = '" . mysql_real_escape_string($this->url) ."', css = '". mysql_real_escape_string($this->css)."' WHERE sites.id = " . $this->id;
    get_res($sql);
  }

  public static function find_or_create_by_url($url) {
    // check if URL is valid!
    $site = Site::find_by_url($url);
    if ($site === false) {
      return Site::create($url);
    }
    return $site;
  }

  public static function find_by_url($url) {
    $sql = "SELECT * FROM sites WHERE url = '" . mysql_real_escape_string($url) . "'";
    $res = get_res($sql);
    $data = mysql_fetch_assoc($res);
    if ($data) {
      $site = new Site($data['url'], $data['css'], $data['id']);
      return $site;
    } else {
      return false;
    }
  }
  
  public static function find_by_id($id) {
    $sql = "SELECT * FROM sites WHERE id =" . (int)$id;
    $res = get_res($sql);
    $data = mysql_fetch_assoc($res);
    if ($data) {
      $site = new Site($data['url'], $data['css'], $data['id']);
      return $site;
    } else {
      return false;
    }
  }

  public static function create($url) {
    $sql = "INSERT INTO sites (url) VALUES ('".mysql_real_escape_string($url)."')";
    get_res($sql);
    return Site::find_by_url($url);
  }


}
