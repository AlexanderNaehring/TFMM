<?php

if(!$db = new SQLite3("database/tfmm.db"))
  exit;

if($_GET["mode"] == "ajax") {
  if($_POST["editFile"] == 1) {
    // update given row with given information
    if($statement = $db->prepare("UPDATE files SET build = :build, os = :os, stable = :stable, downloads = :downloads WHERE rowid = :rowid"))  {
      $statement->bindValue(":build",     $_POST["build"],      SQLITE3_INTEGER);
      $statement->bindValue(":os",        $_POST["os"],         SQLITE3_TEXT);
      $statement->bindValue(":stable",    $_POST["stable"],     SQLITE3_INTEGER);
      $statement->bindValue(":downloads", $_POST["downloads"],  SQLITE3_INTEGER);
      
      $statement->bindValue(":rowid",     $_POST["rowid"],      SQLITE3_INTEGER);
      
      if($statement->execute())
        echo "SUCCESS\n";
      else
        echo "ERROR\n";
      print_r($_POST);
    }
    $statement->close();
  }
  exit;
}


if($_POST["upload_exist"])  {
  // upload file to existing database row
  $filename = basename($_FILES["file"]["name"]);
  $build    = (int)$_POST["build"];
  $os       = $_POST["os"];
  
  if($statement = $db->prepare("UPDATE files SET filename = :filename WHERE build = :build AND os = :os"))  {
    $statement->bindValue(":filename", $filename, SQLITE3_TEXT);
    $statement->bindValue(":build", $build, SQLITE3_INTEGER);
    $statement->bindValue(":os", $os, SQLITE3_TEXT);
    
    if(move_uploaded_file($_FILES["file"]["tmp_name"], "files/{$os}/{$filename}"))
      $statement->execute();
  }
  $statement->close();
}

if($_POST["upload_new"])  {
  // create new file entry and add file data
  $build    = (int)$_POST["build"];
  $os       = $_POST["os"];
  $filename = basename($_FILES["file"]["name"]);
  $stable   = $_POST["stable"];
  
  if($statement = $db->prepare("INSERT INTO files (build, os, filename, stable, downloads) VALUES (:build, :os, :filename, :stable, :downloads)"))  {
    $statement->bindValue(":build",     $build,     SQLITE3_INTEGER);
    $statement->bindValue(":os",        $os,        SQLITE3_TEXT);
    $statement->bindValue(":filename",  $filename,  SQLITE3_TEXT);
    $statement->bindValue(":stable",    $stable,    SQLITE_INTEGER);
    $statement->bindValue(":downloads", 0,          SQLITE_INTEGER);
    
    if(move_uploaded_file($_FILES["file"]["tmp_name"], "files/{$os}/{$filename}"))
      $statement->execute();
  }
  $statement->close();
}

////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<h1>File Management</h1>";

// Manage Files

// serialize row and send using ajax
echo "
  <script>
    $(function() {
      $('form.fileEntry input, form.fileEntry select').change(function()  {
        var form = '<form><table><tr>' + $(this).closest('tr').html() + '</tr></table></form>';
        var serialized = $(form).serialize();
        
        var serialized = $(this).closest('tr').find('select, input').serialize();
        
        console.log('post data: '+serialized);
        $.post('files.ajax.html', serialized, function(data) { console.log(data); });
      });
    });
  </script>";

echo "
<article>
  <h2 class='slideButton'>Current File Records</h2>
  <div>";
if($result = $db->query("SELECT rowid, build, os, filename, stable, downloads FROM files ORDER BY build DESC")) {
  echo "
    <form class='fileEntry' action='#'>
      <table class='table'>
        <tr>
          <th style='width: 10%;'>Build</th>
          <th style='width: 15%;'>OS</th>
          <th style='width: 15%;'>Stable</th>
          <th style='width: 20%;'>Filename</th>
          <th style='width: 10%;'>Downloads</th>
          <th style='width: 10%;'>Size</th>
        </tr>";
      
  $os = array("win" => "Windows", "lin" => "Linux", "osx" => "Mac OS X");
  $stable = array(0 => "Unstable", 1 => "Stable");
  
  while($f = $result->fetchArray(SQLITE3_ASSOC)) {
    $size = filesize("files/{$f["os"]}/{$f["filename"]}");
    $size = round($size/1024)." kB";
    echo "
        <tr>
          <td>
            {$f["build"]}
            <input type='hidden' name='editFile' value='1' />
            <input type='hidden' name='rowid' value='{$f["rowid"]}' />
            <input type='hidden' name='build' value='{$f["build"]}' />
          </td>
          <td>
            <select name='os'>";
    foreach($os as $os_short => $os_long)
      echo "
              <option value='{$os_short}'".($os_short==$f["os"]?" selected='selected'":"").">{$os_long}</option>";
    echo "
            </select>
          </td>
          <td>
            <select name='stable'>";
    foreach($stable as $s_int => $s_str)
      echo "
              <option value='{$s_int}'".($s_int==$f["stable"]?" selected='selected'":"").">{$s_str}</option>";
    echo "
            </select>
          </td>
          <td><a href='{$s["url"]}download.{$f["os"]}-{$f["build"]}.html?nocount=1'>{$f["filename"]}</a></td>
          <td><input type='number' name='downloads' value='{$f["downloads"]}' /></td>
          <td>{$size}</td>
        </tr>";
  }
  echo "
      </table>
    </form>";
  $result->finalize();
}

echo "  
  </div>
</article>";
  
  
  
  
  
echo "
<article>
  <form action='' method='post' enctype='multipart/form-data'>
    <input type='hidden' name='upload_exist' value='1' />
    <table>
      <tr>
        <th>File</th>
        <td><input type='file' name='file' /></td>
      </tr>
      <tr>
        <td colspan='2'><input type='submit' value='Upload File' /></td>
      </tr>
    </table>
  </form>
</article>
";


?>