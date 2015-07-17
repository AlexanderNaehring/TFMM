<?php

if($mode = $_GET["mode"]) {
  list($os, $build) = explode("-", $mode);
  if($os and $build)  {
    if($db = new SQLite3("database/tfmm.db")){
      $statement = $db->prepare("SELECT * FROM files WHERE build = :build AND os = :os LIMIT 1");
      $statement->bindValue(':build', (int)$build, SQLITE3_INTEGER);
      $statement->bindValue(':os', $os, SQLITE3_TEXT);
      
      if($result = $statement->execute()) {
        $file = $result->fetchArray(SQLITE3_ASSOC);
        
        // increase download counter
        if(!$_GET["nocount"]) {
          // static counter in files table
          if($st2 = $db->prepare("UPDATE files SET downloads = downloads + 1 WHERE build = :build AND os = :os ")) {
            $st2->bindValue(":build", (int)$build, SQLITE3_INTEGER);
            $st2->bindValue(":os", $os, SQLITE3_TEXT);
            $st2->execute();
            $st2->close();
          }
          
          // dynamic counter (single row per download)
          if($st2 = $db->prepare("INSERT INTO downloads (build, os) VALUES (:build, :os)")) { #strftime('%s', 'now')
            $st2->bindValue(":build", (int)$build, SQLITE3_INTEGER);
            $st2->bindValue(":os", $os, SQLITE3_TEXT);
            $st2->execute();
            $st2->close();
          } else
            exit;
        }
        
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$file["filename"]}");
        #header("Content-Length: ".filesize());
        ob_clean(); // clean output of potential stuff
        
        //echo $file["data"]; // read data (BLOB) from sqlite3 database
        // read data from file
        
        $filename = "files/{$file["os"]}/{$file["filename"]}";
        readfile($filename);
        
        ob_end_flush(); // flush output buffer -> send all data to output!
      }
      
      $statement->close();
      $db->close();
      
      exit; // exit (no further handling by index.php / output.php)
    } else {
      echo "<div class='danger'>Could not access database!</div>";
    }
  }
}


echo "
<article>
  <h1>Download TFMM</h1>
  <p>
    Here you can download the latest version of TFMM for Windows, OS X and Linux.<br />
    If you are looking for an older version, you can check out the <a href='http://tfmm.xanos.eu/archive.html'>archive</a>.
  </p>
</article>";


// retrieve latest versions
if($db = new SQLite3("database/tfmm.db", SQLITE3_OPEN_READONLY)){
  $statement = $db->prepare("SELECT * FROM versions WHERE os = :os AND stable = :stable ORDER BY build DESC LIMIT 1");
  
  #$statement->bindValue(':os', "win", SQLITE3_TEXT);
  #$statement->bindValue(':stable', 1, SQLITE3_INTEGER);
  
  $p_os = "";
  $p_stable = 1;
  $statement->bindParam(":os", $p_os, SQLITE3_TEXT);
  $statement->bindParam(":stable", $p_stable, SQLITE3_INTEGER);
  
  $downloads = array();
  $downloads_str = array();
  
  $os_list = array("win" => "Windows", "lin" => "Linux", "osx" => "Mac OS X");
  $stable_list = array("stable" => 1, "unstable" => 0);
  
  // loop through stable and unstable
  foreach($stable_list as $stable_str => $stable_int) {
    $p_stable = $stable_int; // set parameter for statement (query)
    // loop through different OS (win, lin, osx)
    foreach($os_list as $os => $os_name)  {
      $p_os = $os; // set parameter for statement (query)
      
      if($result = $statement->execute())
        if($v = $result->fetchArray(SQLITE3_ASSOC))  {
          if($stable_int == 0 and $v["build"] <= $downloads["stable"][$os]["build"])  {
            // only show testing version if newer than current stable version
          } else {
            $downloads[$stable_str][$os] = $v;
            $downloads_str[$stable_str][$os] = "
              <a class='{$stable_str}' href='{$s["url"]}download.{$os}-{$v["build"]}.html' title='Download TFMM v{$v["version"]}'>
                <img src='images/{$os}.png' alt='{$os}'/>
                <strong>TFMM for {$os_name}</strong>
                <small>v{$v["version"]}</small>
              </a>";
          }
        }
      
      $statement->reset();
    }
  }
  $statement->close();
  $db->close();
}

// stable versions
echo "
<article>
  <h2>Latest Stable Version</h2>
  <div class='downloads'>
    {$downloads_str["stable"]["win"]}
    {$downloads_str["stable"]["lin"]}
    {$downloads_str["stable"]["osx"]}
  </div>
</article>";

// unstable / testing versions:
if(is_array($downloads_str["unstable"])) // only print this section if there are current testing versions available
  echo "
<article>
  <h2>Latest Testing Version</h2>
  <div class='info'>
    <strong>Beware! Testing versions may be unstable, full of bugs and potentially kill your dog. &#x1f609;</strong>
  </div>
  <div class='downloads'>
    {$downloads_str["unstable"]["win"]}
    {$downloads_str["unstable"]["lin"]}
    {$downloads_str["unstable"]["osx"]}
  </div>
</article>";


?>