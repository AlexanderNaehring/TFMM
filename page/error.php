<?php

switch($_GET["mode"]) {
  case 401:
    $header = "HTTP/1.0 401 Unauthorized";
    $h = "401 - Unauthorized";
    $p = "Please authorize to access this document.";
    break;
  case 403:
    $header = "HTTP/1.0 403 Forbidden";
    $h = "403 - Forbidden";
    $p = "This document is not available, sorry.";
    break;
  case 404:
  default:
    $header = "HTTP/1.0 404 Not Found";
    $h = "404 - Not Found! :(";
    $p = "The requested page could not be found. Please go back or try again later.";
    break;
}

header($header);
$out->addBody("
<article>
  <h1>{$h}</h1>
  <p>
    {$p}
  </p>
</article>");

?>