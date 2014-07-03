<?php

$page_base_url = "";
$page_url_path = "";

class Page {
  public $url;
  public $base_url;
  public $html;
  public $site;
  public $path;
  
  function __construct($url) {
    $this->url = $url;
    $ar = parse_url($url);
    $this->base_url = $ar['scheme'] . "://" . $ar['host'];
    $this->path = str_replace($this->base_url, "", $url);
    $this->site = Site::find_by_url($this->base_url);
  }

  function scrape() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->base_url. $this->path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $this->html = curl_exec($ch);
    curl_close($ch);
  }

  function display() {
    $this->scrape();
    $this->apply_filters();
    return $this->html;
  }

  function apply_filters() {
    $this->rewrite_css();
    $this->rewrite_links();
    $this->rewrite_js();
    $this->rewrite_images();
    $this->rewrite_forms();
    $this->rewrite_mp3();
    $this->add_css();
  }

  function gather_css() {

  }

  function strip_css() {
  
  }

  function rewrite_js() {
    $this->html = preg_replace('/(<script.*?type="text\/javascript".*?src=(\'|"))(\/.*?)(\'|")/', "$1".$this->base_url."$3$4", $this->html);
  }

  function rewrite_mp3() {
    $this->html = preg_replace('/src="\/(.*?\.mp3")/', "src=\"".$this->base_url."/$1", $this->html);
    $this->html = str_replace('http://christchurchartgallery.org.nz/media/uploads/2011_09/Rita_Angus_-_Cass_High_Qual.mp3', 'https://s3.amazonaws.com/website_remixer_custom/Rita_Angus_-_Cass_High_Qual+edited.mp3', $this->html);
  }

  function rewrite_css() {
    $this->html = preg_replace('/(<link.*?rel="stylesheet".*?href=(\'|"))(.*?)(\'|")/', "$1".$this->base_url."$3$4", $this->html);
  }

  function rewrite_links() {
    //$this->html = '<a href="/search/" class="advanced">&rarr; Advanced Search</a>';
    $GLOBALS['page_base_url'] = $this->base_url;
    $this->html = preg_replace_callback('/(<a.*?href=(\'|"))(.*?)(\'|")/', "rlcf", $this->html);
  }

  function rewrite_images() {
 //   $this->html = '<img src="/media/cache/65/98/6598cb5dd8a078ffc28b076f126533a3.jpg" alt="The Christchurch Art Gallery Collection" width="880" height="300">';
    $GLOBALS['page_base_url'] = $this->base_url;
    $this->html = preg_replace_callback('/(<img.*?src=(\'|"))(.*?)(\'|")/', "rlcf",  $this->html);
    $this->html = preg_replace_callback('/poster="\/(.*?\.jpg)"/', "rlcf_poster", $this->html);
    $this->html = preg_replace_callback('/data-hover_src="\/(.*?\.jpg)"/', "rlcf_data_hover_src", $this->html);
  }

  function rewrite_forms() {
    $GLOBALS['page_base_url'] = $this->base_url;
    $GLOBALS['page_url_path'] = $this->path;
     $this->html = preg_replace_callback('/(<form.*?action=(\'|"))(.*?)(\'|")(.*?>)/', "rclf_form", $this->html);
  }

  function add_css() {
    $extra = "
    <style type=\"text/css\">
      body > *{visibility:inherit}
      html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,font,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td, a, a.current, a:visited, a:link, header nav.side a, #crumbtrail p, #crumbtrail > a, #crumbtrail > span, input, nav.bold a, .toprow a, .toprow label, .toprow input.go {
        text-decoration: line-through;
      }
      body {
        background: url('https://s3.amazonaws.com/website_remixer_custom/crosses.png');
      }
      #headerwrap header .title a {
        background-image: url('http://remixer.leecunliffe.com/page.php?u=http%3A%2F%2Fchristchurchartgallery.org.nz%2Fmedia%2Fi%2Fheader.png');
      }
    </style>\n
    ";
    $this->html = str_replace("</head>", $extra."</head>", $this->html);
  }

}

function rlcf($matches) {
  if (substr($matches[3],0, 4) == "http") {
    return $matches[1]."http://".$_SERVER['SERVER_NAME']."/page.php?u=".urlencode($matches[3]).$matches[4];
  } else {
    return $matches[1]."http://".$_SERVER['SERVER_NAME']."/page.php?u=".urlencode($GLOBALS['page_base_url'].$matches[3]).$matches[4];
  }
}

function rlcf_poster($matches) {
  return "poster=\"http://".$_SERVER['SERVER_NAME']."/page.php?u=".urlencode($GLOBALS['page_base_url']."/".$matches[1])."\"";
}

function rlcf_data_hover_src($matches) {
  return "data-hover_src=\"http://".$_SERVER['SERVER_NAME']."/page.php?u=".urlencode($GLOBALS['page_base_url']."/".$matches[1])."\"";
}


function rclf_form($matches) {
  $ret = $matches[1]."/form.php".$matches[4].$matches[5]."
   <input type='hidden' name='rm_base_url' value='".urlencode($GLOBALS['page_base_url'])."' />
   ";
  if ($matches[3] == ".") {
    $matches[3] = $GLOBALS['page_url_path'];
  }
  $ret .= "<input type='hidden' name='rm_action_url' value='".urlencode($matches[3])."' />
   ";
   return $ret;
}
      
      
