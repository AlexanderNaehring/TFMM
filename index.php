<?php

date_default_timezone_set('Europe/Berlin'); 
setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
mb_internal_encoding("UTF-8");

include("include/output.php");

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
# create settings array for output

// basic
$settings["url"]      = "http://tfmm.xanos.eu/";
$settings["lang"]     = "en";
$settings["site"]     = "Train Fever Mod Manager";
$settings["title"]    = "";
$settings["author"]   = "Alexander Nähring";
$settings["email"]    = "xanos@alexandernaehring.eu";
$settings["address"]  = "Am Zwinger 5 - 82205 Gilching - Deutschland";
$settings["keywords"] = "Train Fever, TFMM, Train Fever Mod Manager, Game, Game Manager, Community, Mods, Modifications, ";

// design
$settings["logo"]     = "";
$settings["headline"] = "Train Fever Mod Manager";
$settings["subtitle"] = "";
$settings["footer"]   = "&copy; 2014-".date("Y")." <a href='mailto:xanos@alexandernaehring.eu'>Alexander Nähring</a> &mdash; Am Zwinger 5 - 82205 Gilching - Deutschland";

// page information
$settings["startpage"] = "info";
$settings["pages"] = array(
  "info"      => "Information",
  "download"  => "Download",
  "archive"   => "Download Archive",
  "changelog" => "Changelog",
  "locale"    => "Localizations",
  "ini2json"  => "ini-to-json",
  "error"     => "Error",
  "@tools"    => "Tools",
);
$settings["subtitles"] = array(
  "info"      => "",
  "download"  => "Download Area",
  "archive"   => "Download Archive",
  "changelog" => "Changelog",
  "locale"    => "Localizations",
  "ini2json"  => "INI to JSON Converter",
  "error"     => "",
  "admin"     => "Adminstration",
  "files"     => "Adminstration: File Management",
  "versions"  => "Adminstration: Version Management",
);

// the following pages can only be accessed when logged in
$settings["admin"]["hash"] = '$2y$10$qJXFdxq1ORZxuqCru9o3o./LucKM6zLO/wS39w0H/lyoSTkktFMAe'; //password = password
$settings["admin"]["startpage"] = "admin"; # start page for adminstrative area
$settings["admin"]["pages"] = array(
  "admin" => "Admin Control Panel",
  "files" => "File Management",
  "versions" => "Version Management",
);

// navigation menu
$settings["nav"] = array(
  array("info"),
  array("download"),
  #array("archive"),
  array("changelog"),
  array("@tools", "locale", "ini2json"), // links starting with @ have no physical page
);


#$settings["css"][] = "path_to_css_file";
#$settings["js"][]  = "path_to_js_file";

/*  JSON for general settings
$json = json_encode($settings, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
file_put_contents("include/settings.json", $json);
$json = file_get_contents("include/settings.json");
$settings = json_decode($json, true);
*/


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$out = new output($settings);
echo $out->html();

?>