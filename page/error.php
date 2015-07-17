<?php
header("HTTP/1.0 404 Not Found");

$out->addBody("
<h1>404 - Not Found! :(</h1>
<p>
  The requested page could not be found. Please go back or try again later.</br>
</p>");
?>