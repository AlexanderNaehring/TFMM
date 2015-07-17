<?php

echo "
<article>
  <h1>Changelog</h1>";
if($db = new SQLite3("database/tfmm.db", SQLITE3_OPEN_READONLY)){
  # $result = $db->query("SELECT build, version, strftime('%s',date) AS date, changelog FROM changelog ORDER BY date DESC")
  $statement = $db->prepare("SELECT *, strftime('%s',date) AS date FROM changelogExt ORDER BY date DESC");
  #$statement->bindValue(':id', $id);
  if($result = $statement->execute()) {
    while($v = $result->fetchArray()) {
      $os = "";
      if($v["win"]) $os .= "<img src='images/win.png' alt='win' title='Windows' style='height: 1em;' /> ";
      if($v["lin"]) $os .= "<img src='images/lin.png' alt='lin' title='Linux' style='height: 1em;' /> ";
      if($v["osx"]) $os .= "<img src='images/osx.png' alt='osx' title='Mac OS X' style='height: 1em;' /> ";
      echo "<strong>version {$v["version"]}</strong> (build {$v["build"]} | ".date("M j, Y", $v["date"]).") {$os}";
      if($v["changelog"]) {
        echo "<ul>";
        $cl = $v["changelog"];
        $cl = explode("\n", $cl);
        foreach($cl as $li) {
          $search = array("added:", "changed:", "fixed:");
          $replace = array(
            "<span style='color: green'>added:</span>",
            "<span style='color: orange'>changed:</span>",
            "<span style='color: red'>fixed:</span>");
          $li = str_replace($search, $replace, $li);
          echo "<li>{$li}</li>";
        }
        echo "</ul>";
      }
    }
  }
  $statement->close();
  $db->close();
}
echo "
</article>";

?>