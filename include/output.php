<?php
class output  {
  private $settings = array();
  private $content = array();
  
  public function __construct($settings) {
    # initialize settings to standard
    $s["url"]       = "";
    $s["lang"]      = "en";
    $s["site"]      = "";
    $s["title"]     = "";
    $s["author"]    = "";
    $s["logo"]      = "";
    $s["headline"]  = "";
    $s["subtitle"]  = "";

    $s["keywords"]  = "";
    $s["head"]      = array();
    
    $s["page"]      = "";
    
    $s["hidenav"]   = false;
    
    
    $this->settings = array_replace_recursive($s, $settings);
    
    $this->content["body"] = "";
    $this->content["jumbotron"] = "";
    $this->content["footer"] = "";
  }
  
  public function updateSettings($settings) {
    if(isarray($settings))
      $this->settings = array_replace_recursive($this->settings, $settings);
  }
  
  public function addBody($text)  {
    $this->content["body"] .= $text;
  }
  
  public function setFooter($footer)  {
    $this->content["footer"] = $footer;
  }
  
  public function addJumbotron($text)  {
    $this->content["jumbotron"] .= $text;
  }
  
  private function NavLink($page, $text)  {
    if(!$page)  $page = "home";
    if($this->settings["page"] == $page)
      $class = " class='active'";
    else  $class = "";
    $href = ($page==$this->settings["startpage"])?"./":"".$page.".html";
    return "<a href='{$href}'{$class}>{$text}</a>";
  }
  
  public function __toString() {
    # locally copy settings
    $s = $this->settings;
    # pre-processing:
    # title
    $title = $s["title"]?$s["site"]." - ".$s["title"]:$s["site"];
    # header files
    $head = ""; foreach($s["head"] as $file)
      $head = $head."\n      ".$file;
    # process nav links
    $links = "";
    if(is_array($s["nav"]))  {
      foreach($s["nav"] as $pages)  {
        if(is_array($pages))  {
          $i = 1;
          $links .= "<li>";
          foreach($pages as $page) {
            if($i == 2) $links .= "<ul><li>";
            if($i > 2)  $links .= "<li>";
            $links .= $this->NavLink($page, $s["pages"][$page]);
            if($i > 2)  $links .= "</li>";
            $i++;
          }
          if($i > 2)    $links .= "</ul>";
          $links .= "</li>";
        }
      }
    }
    
    
    $out = "<!DOCTYPE html>
<html lang='{$s["lang"]}'>
  <head>
    <meta charset='utf-8' />
    <meta name='robots' content='all' />
    <meta name='description' content='{$title}' />
    <meta name='Keywords' content='{$s["keywords"]}' />
    <meta name='Author' content='{$s["author"]}' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$title}</title>
    <meta name='robots' content='all, index, follow' />
    {$head}
    <link rel='icon' href='favicon.png' />
  </head>
  
  <body>
    <header>
      <div class='top'>
        ".($s["logo"]?"<div class='logo'><a href='{$s["url"]}'><img src='{$s["logo"]}' alt='' /></a></div>":"")."
        <div class='headline'><a href='{$s["url"]}'>{$s["headline"]}</a></div>
        ".($s["subtitle"]?"<div class='subtitle'>{$s["subtitle"]}</div>":"")."
      </div>".($this->hideNav?"":"
      <div class='nav-wrapper'>
        <nav><ul>{$links}</ul></nav>
      </div>")."
  </header>".($this->content["jumbotron"]?"
    <section class='jumbotron'>{$this->content["jumbotron"]}</section>":"")."
    <section hidden>
      <div class='warning' style='max-width: 800px;'>
        <strong>Experience the web even better, faster and more beautiful!</strong><br />
        <em>For your own safety, use a modern browser with the latest security mechanisms and
        <a href='http://en.wikipedia.org/wiki/HTML5'>HTML 5</a> support, for example
        <a href='http://www.google.com/chrome/'>Google Chrome</a>, <a href='https://www.mozilla.org/firefox/new/'>Mozilla Firefox</a> or similar.</em>
      </div>
    </section>
    <section class='main'>{$this->content["body"]}</section>
    <footer>{$this->content["footer"]}</footer>
    <script>
      $(function() {
        $(window).scroll(function() {
          if (typeof wp === 'undefined')  {
            wp = $('.nav-wrapper').position().top;
          }
          var sp = $(this).scrollTop();
          
          if(wp < $('.nav-wrapper').position().top) {
            wp = $('.nav-wrapper').position().top;
          }
          if(sp >= wp) {
            if(!$('.nav-wrapper').hasClass('fixed')) {
              $('.nav-wrapper').addClass('fixed');
              $('.nav-wrapper').before(\"<div class='spacer'></div>\");
            }
          } else if(sp < wp && $('.nav-wrapper').hasClass('fixed')) {
            $('.nav-wrapper').removeClass('fixed');
            $('.spacer').remove();
          }
        })
      });
      $(document).ready(function()  {
        var current_hash = location.hash;
        var current_hash = current_hash.replace(/#/,'');
        if(current_hash.length > 1) window.location.href = current_hash;
      });
    </script>
  </body>
</html>";
    
    $search = array(
      "/\>[^\S ]+/s",  // strip whitespaces after tags, except space
      "/[^\S ]+\</s",  // strip whitespaces before tags, except space
      "/(\s)+/s"       // shorten multiple whitespace sequences
    );
    $replace = array(
      ">",
      "<",
      "\\1"
    );
    $out = preg_replace($search, $replace, $out);
    
    return $out;
  }
};

?>