<?php

echo "
<article>
  <h1>Archive</h1>
  <script>
    $(function() {
      $('.archive_filter input').click(function() {
        var selector = '.'+$(this).val();
        
        if($(this).is(':checked'))
          $(selector).show();
        else
          $(selector).hide();
        
      });
    });
  </script>
  <form action='#' class='archive_filter'>
    <!--<label><input type='checkbox' value='stable' checked='checked' /> Show Stable Releases</label>-->
    <label><input type='checkbox' value='unstable' checked='checked' /> Show Testing Releases</label>
  </form>";

if($db = new SQLite3("database/tfmm.db", SQLITE3_OPEN_READONLY)) {
  
  $statement = $db->prepare("SELECT *, strftime('%s',date) AS date FROM versions WHERE os = :os ORDER BY build DESC");
  $p_os = "";
  $statement->bindParam(":os", $p_os, SQLITE3_TEXT);
  
  $os_list = array("win" => "Windows", "lin" => "Linux", "osx" => "Mac OS X");
  foreach ($os_list as $os => $os_str)  {
    $p_os = $os; // bind param
    
    echo "<h2 class='slideButton'>TFMM for {$os_str}</h2>";
    
    if($result = $statement->execute()) {
      
      echo "<div class='downloads archive'>";
      
      while($v = $result->fetchArray(SQLITE3_ASSOC))  {
        $stable = $v["stable"]?"stable":"unstable";
        echo "
          <a class='{$stable}' href='{$s["url"]}download.{$v["os"]}-{$v["build"]}.html' title='Download TFMM v{$v["version"]}'>
            <img src='images/{$v["os"]}.png' alt='{$v["os"]}'/>
            <strong>v{$v["version"]} - build {$v["build"]}</strong>
            <small>".date("M j, Y", $v["date"])."</small>
          </a>";
      }
      echo "</div>";
      
    }
    $statement->reset(); // reset for next iteration
  }
  $statement->close();
  $db->close();
} else {
  echo "<div class='danger'>Could not access database!'</div>";
}

echo "
</article>";

?>