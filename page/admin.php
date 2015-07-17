<?php

// Not neccessary to check access as subfolder is secure using .htaccess and output will check for access
/*
if(!$_SESSION["access_granted"]) {
  exit;
}
*/

// headline
echo "
<article>
  <h1>Admin Control Panel</h1>
  <p>From here, administrative tasks can be performed</p>
  <ul>
    <li><a href='files.html'>File Management</a></li>
    <li><a href='versions.html'>Version Management</a></li>
  </ul>
</article>
";



?>