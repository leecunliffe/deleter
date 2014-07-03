<?php

require_once '../s3/sdk.class.php';


class Image {
  public $url;
  public $id;
  public $s3_id;

  function __construct($id, $s3_id, $url) {
    $this->id = $id;
    $this->s3_id = $s3_id;
    $this->url = $url;
  }
  
  public static function find_by_url($url) {
    $sql = "SELECT * FROM images WHERE url = '" . mysql_real_escape_string($url) . "'";
    $res = get_res($sql);
    $data = mysql_fetch_assoc($res);
    if ($data) {
      return new Image($data['id'], $data['s3_id'], $data['url']);
    } else {
      return false;
    }
  }

  public static function find_or_create_by_url($url) {
    $i = Image::find_by_url($url);
    if ($i === false) {
      return Image::create_by_url($url);
    }
    return $i;
  }

  public static function create_by_url($url) {
    $sql = "INSERT INTO images (url) VALUES ('".mysql_real_escape_string($url)."')";
    get_res($sql);
    $i = Image::find_by_url($url);
    $i->cross_out();
    $i->save_s3();
    return $i;
  }

  function cross_out() {
    $fh = fopen($this->url, "r");
    $imagick = new Imagick();
    $imagick->readImageFile($fh);
    $geometry = $imagick->getImageGeometry();
    $x = $geometry['width'];
    $y = $geometry['height'];
    $max_side = max($x, $y);
    $min_side = min($x,$y);
    $cross_count = floor($max_side/$min_side);
    $draw = new ImagickDraw();
    $draw->setStrokeColor(new ImagickPixel('#010101')); // black fixes stroke width to 1?????
    $line_width = ceil($min_side/30);
    $draw->setStrokeWidth($line_width);
    $gap = 0;
    $stroke_var = $max_side/$cross_count;
    if ($cross_count > 1) {
      $gap = $line_width*3;
      $stroke_var -= $gap;
    }
    for ($i=0; $i<=$cross_count; $i++) {
      if ($max_side == $x) {
        $draw->line($i*($stroke_var+$gap), 0, (($i+1)*$stroke_var)+($i*$gap), $y); // the \ strokes
        $draw->line((($i+1)*$stroke_var)+($i*$gap), 0, $i*($stroke_var+$gap), $y); // the / strokes
      } else {
        $draw->line(0, $i*($stroke_var+$gap), $x, (($i+1)*$stroke_var)+($i*$gap)); // the \ strokes
        $draw->line($x, $i*($stroke_var+$gap), 0, (($i+1)*$stroke_var)+($i*$gap)); // the / strokes
      }
    }
    $imagick->drawImage($draw);
    $this->imagick = $imagick;
  }
  
  function save_s3() {
    $this->save();
    $this->imagick->writeImage('../public/images/'.$this->id);
    $s3 = new AmazonS3();
    $s3->create_object(S3_BUCKET, $this->id, array(
      'fileUpload' => '../public/images/'.$this->id, 
      'acl' => $s3::ACL_PUBLIC
    ));
    unlink('../public/images/'.$this->id);
  }

  function s3_url() {
    return 'http://s3.amazonaws.com/'.S3_BUCKET.'/'.$this->id;
  }

  function save() {
    
  }

}
