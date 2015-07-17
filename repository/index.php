<?php

// TFMM Main Repository

header('Content-Type: application/json; charset=utf-8'); #text/txt #text/html

// list of "local" repositories
$repo = array( 
  // basic info about this repo
  "repository" => array(
    "name"        => "TFMM Base Repository",
    "description" => "This repository links to mods from train-fever.net and provides official language files.",
    "maintainer"  => "Xanos",
    "url"         => "http://repo.tfmm.xanos.eu/",
    "info_url"    => "http://tfmm.xanos.eu/",
  ),
  
  // repo for language files
  "locale"  => array( 
    // link to repo:
    "url"     => "http://repo.tfmm.xanos.eu/locale/",
    // last change of this repo:
    "changed" => time(),
  ),
  
  // repo for modification files
  "mods"    => array(
    "url"     => "http://repo.tfmm.xanos.eu/mods/",
    "changed" => time(),
  ),
  
);

echo json_encode($repo, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
