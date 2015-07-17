<?php


include("include/output.php");

# create settings array for output
$settings["url"]      = "http://tfmm.xanos.eu/";
$settings["lang"]     = "en";
$settings["site"]     = "Train Fever Mod Manager";
$settings["title"]    = "";
$settings["author"]   = "Alexander Nähring";
$settings["keywords"] = "Train Fever, TFMM, Train Fever Mod Manager, Game, Game Manager, Community, Mods, Modifications, ";

$settings["logo"]     = "";
$settings["headline"] = "Train Fever Mod Manager";
$settings["subtitle"] = "";

$settings["head"] = array();

$settings["startpage"] = "info";

$settings["pages"] = array(
  "info"      => "Information",
  "download"  => "Download",
  "changelog" => "Changelog",
  "locale"    => "Localizations",
  "ini2json"  => "ini-to-json",
  "error"     => "Error",
  "#tools"    => "Tools",
);
$settings["subtitles"] = array(
  "info"      => "",
  "download"  => "Download Area",
  "changelog" => "Changelog",
  "locale"    => "Localizations",
  "ini2json"  => "INI to JSON Converter",
  "error"     => "",
);


$settings["nav"] = array(
  array("info"),
  array("download"),
  array("changelog"),
  array("#tools", "locale", "ini2json"),
);

// js scripts
$settings["head"][] = "<script src='//code.jquery.com/jquery-1.11.2.min.js'></script>";
#$settings["head"][] = "<script src='script.js'></script>";
#$settings["head"][] = "<script src='script/jquery.mobile.custom.min.js";
// css scripts
$settings["head"][] = "<link rel='stylesheet' type='text/css' href='style.css' />";

/*  JSON for general settings
$json = json_encode($settings, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
file_put_contents("include/settings.json", $json);
$json = file_get_contents("include/settings.json");
$settings = json_decode($json, true);
*/

// get pages
if($page = $_GET["page"]) {
  if(!is_file("page/{$page}.php"))
    $page = "error";
} else
  $page = $settings["startpage"];

if(!$settings["subtitle"] and isset($settings["pages"][$page]))
  $settings["subtitle"] = $settings["subtitles"][$page];
$settings["page"] = $page;

$out = new output($settings);
$out->setFooter("&copy; 2014-".date("Y")." <a href='mailto:xanos@alexandernaehring.eu'>Alexander Nähring</a> &mdash; Am Zwinger 5 - 82205 Gilching - Deutschland");
#$out->addBody("<h1>Info</h1>");
#$out->addBody(file_get_contents("lorem_ipsum.txt"));


include("page/{$page}.php");


echo $out;

?>