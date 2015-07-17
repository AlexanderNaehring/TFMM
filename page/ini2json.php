<?php

function parse_ini ($input) {
  if(!$input)
    return array();
  
  if(is_array($input))
    $ini = $input; // input is already an array
  else if(file_exists($input))
    $ini = file($input); // read file to array
  else { // assume ini as string, convert to array
    $ini = explode("\n", $input);
  }
  
  if(count($ini) == 0) { return array(); }
  
  $sections = array();
  $values = array();
  $globals = array();
  $result = array();
  $i = 0;
  
  foreach($ini as $line){
    $line = trim($line);
    // Comments
    if ( $line == '' || $line{0} == ';' ) { continue; }
    // Sections
    if ( $line{0} == '[' ) {
        $sections[] = substr($line, 1, -1);
        $i++;
        continue;
    }
    // Key-value pair
    list($key, $value) = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value);
    if ( $i == 0 ) {
      // Array values
      if(substr($line, -1, 2) == '[]') {
        $globals[$key][] = $value;
      } else {
        $globals[$key] = $value;
      }
    } else {
      // Array values
      if ( substr( $line, -1, 2 ) == '[]' ) {
        $values[$i - 1][$key][] = $value;
      } else {
        $values[$i - 1][$key] = $value;
      }
    }
  }
  for( $j=0; $j<$i; $j++ ) {
    $result[$sections[$j]] = $values[$j];
  }
  return $globals + $result;
}

if($_POST["ini2json"])  {
  $ini = $_POST["ini"];
  $array = parse_ini($ini);
  $json = json_encode($array, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
  $json = str_replace("  ", "&nbsp; ", $json);
  $json = str_replace("\n", "<br/>", $json);
}

echo "
<article>
  <h1>INI to JSON Converter</h1>
  <p>This tool can be used to convert INI files into a JSON format.</p>
  
  <form action='' method='post'>
    <textarea name='ini'>{$ini}</textarea>
    <input type='submit' name='ini2json' value='Convert' />
    ".($json?"<code class='box' style='overflow: auto;'>{$json}</code>":"")."
  </form>
  
</article>";

?>