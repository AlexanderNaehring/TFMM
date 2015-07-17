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
    $s["css"]       = array();
    $s["js"]        = array();
    
    $s["page"]      = "";
    
    $s["startpage"] = "home";
    
    $s["hidenav"]   = false;
    
    $s["admin"]["hash"] = '$2y$10$FAG0xZ1P2QheTTqedf2EsuT.w7nZvxM0Km/oO8nv07/DzcaJ1h6i2'; // password = password
    $s["admin"]["startpage"] = "admin"; # start page for adminstrative area
    $s["admin"]["pages"] = array(
      "admin" => "Admin Control Panel",
    );
    
    // overwrite standard settings with user defined settings
    $this->settings = array_replace_recursive($s, $settings);
    
    
    // init content
    $this->content["body"] = "";
    $this->content["jumbotron"] = "";
    $this->content["footer"] = "&copy; 2014-".date("Y")." <a href='{$this->setings["email"]}'>{$this->settings["author"]}</a> &mdash; {$this->settings["address"]}";
  }
  
  public function updateSettings($settings) {
    if(isarray($settings))
      $this->settings = array_replace_recursive($this->settings, $settings);
  }
  
  public function addBody($text)  {
    $this->content["body"] .= $text;
  }
  
  public function addCSS($link) {
    if($link)
      $this->settings["css"][] = $link;
  }
  
  public function addJS($link) {
    if($link)
      $this->settings["js"][] = $link;
  }
  
  public function setFooter($footer)  {
    $this->content["footer"] = $footer;
  }
  
  public function addJumbotron($text)  {
    $this->content["jumbotron"] .= $text;
  }
  
  private function NavLink($page, $text)  {
    $url = $this->settings["url"];
    if($this->settings["page"] == $page)
      $class = " class='active'";
    else
      $class = "";
    if($page{0} == "@")
      $href = "#";
    else
      $href = ($page == $this->settings["startpage"])?$url:$url.$page.".html";
    return "<a href='{$href}'{$class}>{$text}</a>";
  }
  
  # public function __toString() {
  public function html() {
    # locally copy settings
    $s = &$this->settings;
    
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    
    session_name("session");
    // session_cache_expire(180); //minutes (not lifetime!)
    // session_set_cookie_params(30*60, "/"); // seconds, lifetime of cookie // BUG: only sets expiration time once!
    session_cache_limiter("nocache");
    session_start();
    // workaround for expiration time: reset cookie at every page refresh
    setcookie(session_name(), session_id(), time()+30*60);
    
    // include page and catch output
    
    // get page from request
    if($page = $_GET["page"]) {
      if(!is_file("page/{$page}.php"))
        $page = "error";
    } else
      $page = $s["startpage"];
    
    if(!$s["subtitle"] and (isset($s["pages"][$page]) or isset($s["admin"]["pages"][$page])) )
      $s["subtitle"] = $s["subtitles"][$page];
    
    $s["page"] = $page;
    
    // Administration
    if($_GET["logout"] or $_POST["logout"]) {
      $_SESSION = array();
      # session_destroy()
      # delete cookies
    }
    if(isset($s["admin"]["pages"][$page])) { // this page may only be accessed when logged in
      
      if(!$_SESSION["access_granted"]) {
        // access to admin pages not granted
        // check for password transmission or display login form
        if($passwd = $_POST["passwd"])  {
          $_SESSION["access_granted"] = password_verify($passwd, $s["admin"]["hash"]);
        }
        if(!$_SESSION["access_granted"])  {
          $page = ""; // unset page as no access is given!
          header("HTTP/1.0 401 Unauthorized");
          if($passwd)
              $this->content["body"] .= "<div class='warning'>Wrong Password!</div>";
          $this->content["body"] .= "
          <form method='post' action=''>
            <table>
              <tr>
                <th>Password</th>
                <td><input type='password' name='passwd' value='' /></td>
              </tr>
              <tr>
                <td colspan='2'><input type='submit' value='Login' /></td>
              </tr>
            </table>
          </form>";
        }
      } else  {
        // access granted , leave $page set
      }
    }
    
    if($_SESSION["access_granted"])
        $this->content["footer"] .= " &mdash; <a href='{$this->settings["url"]}{$this->settings["admin"]["startpage"]}.html'>Administration</a> | <a href='?logout=1'>Logout</a>";
    
    if($page) { // if page is defined (may not be defined to to access restrictions)
      if(is_file("page/{$page}.php")) {
        // get page echo
        try {
          ///////////////////////
          // INCLUDE PAGE HERE //
          ///////////////////////
          ob_start();
          include("page/{$page}.php");
          $this->content["body"] .= ob_get_contents();
          ob_end_clean();
        } catch(Exception $e) {
          $this->content["body"] .= "<div class='danger'>{$e->getMessage()}</div>";
        }
      } else  {
        header("HTTP/1.0 404 Not Found");
        $this->content["body"] = "<div class='danger'>Critical Error: Could not find page!</div>";
      }
    }
    
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    
    # pre-processing:
    # title
    $title = $s["title"]?$s["site"]." - ".$s["title"]:$s["site"];
    # header files
    $css = ""; foreach($s["css"] as $file)
      $css = $css."\n    <link rel='stylesheet' type='text/css' href='{$file}' />";
    $js = ""; foreach($s["js"] as $file)
      $js = $js."\n      <script src='{$file}'></script>";
    
    # process nav links
    $links = "";
    if(is_array($s["nav"]))  {
      $count = count($s["nav"]);
      $width = 100/$count;
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
    
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    
    $out = "<!DOCTYPE html>
<html lang='{$s["lang"]}'>
  <head>
    <meta charset='utf-8' />
    <meta name='robots' content='all' />
    <meta name='description' content='{$title}' />
    <meta name='Keywords' content='{$s["keywords"]}' />
    <meta name='Author' content='{$s["author"]}' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='robots' content='all, index, follow' />
    <meta name='google-site-verification' content='pCY1QQhzoMnnmAsePaPyCIXdoB8pQmwoy1dTMcs1qgY' />
    <title>{$title}</title>
    <link rel='stylesheet' type='text/css' href='{$this->settings["url"]}style.css' />
    {$css}
    <script src='//code.jquery.com/jquery-1.11.2.min.js'></script>
    {$js}
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
      $(function() { /* $(document).ready(function() { }); */
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
        });
        
        $('.slideButton').click(function() {
          $(this).next().slideToggle(500);
        });
        
        var current_hash = location.hash;
        var current_hash = current_hash.replace(/#/,'');
        if(current_hash.length > 1) window.location.href = current_hash;
      });
      
      
    </script>
  </body>
</html>";
    
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    
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