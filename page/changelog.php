<?php

$out->addBody("
<article>
  <h1>Changelog</h1>
  <ul>
    <li>version 0.8.120
      <ul>
        <li>fixed: empty name fields</li>
        <li>fixed: missing tags displayed as string</li>
        <li>added: progress bar at startup</li>
      </ul>
    </li>
    <li>version 0.8.116
      <ul>
        <li>OSX release</li>
      </ul>
    </li>
    <li>version 0.8.114
      <ul>
        <li>small bugfixes</li>
      </ul>
    </li>
    <li>version 0.8.111 build 1696
      <ul>
        <li>switched to new modding system (BETA)</li>
      </ul>
    </li>
    <li>version 0.6.66 - build 1310
      <ul>
        <li>added: MacOS support (BETA)</li>
        <li>added: multi locale support (en & de included)</li>
        <li>added: categories displayed with names in language of choice (locale file required)</li>
        <li>added: save and restore column width of list gadget after restarting TFMM</li>
        <li>added: option to resolve dependency while deactivating mods</li>
        <li>added: ID validation - only lowercase IDs are valid</li>
        <li>changed: debug output on (output stored in tfmm-output.txt)</li>
        <li>changed: unrar.dll packed in exe (win only)</li>
        <li>changed: new activation method</li>
        <li>changed: new deactivation method</li>
        <li>changed: update now offers option to open homepage</li>
        <li>changed: check new versions for each operating system independently</li>
        <li>changed: add multiple files via file requester (new mod - button)</li>
        <li>changed: ignore <kbd>__MACOS</kbd>, <kbd>.DS_Store</kbd>, <kbd>thumbs.db</kbd>, files starting with <kbd>._</kbd></li>
        <li>fixed: possibly critical bug in batch processing caused possible overlap of multiple activations and deactivations</li>
        <li>fixed: Active mods could be removed if deactivation failed</li>
        <li>fixed: memory leak</li>
        <li>fixed: first time activation did not extract preview image</li>
        <li>code optimizations</li>
      </ul>
    </li>
    <li>version 0.5.51 - build 1060
      <ul>
        <li>fixed: graphic errors on some systems with Intel GPU running Windows 7</li>
      </ul>
    </li>
    <li>version 0.5.50 - build 1050
      <ul>
        <li>fixed: reduced image flicker on Windows 7</li>
      </ul>
    </li>
    <li>version 0.5.49 - build 1040
      <ul>
        <li>added: Linux (64 bit) support</li>
        <li>added: sort columns on click (Windows only)</li>
        <li>added: multi-select</li>
        <li>added: batch processing</li>
        <li>added: Drag & Drop</li>
        <li>added: uninstalling active modifications</li>
        <li>added: right click menu</li>
        <li>added: multi author support</li>
        <li>added: information window</li>
        <li>added: display preview.png</li>
        <li>added: automatic ID generation</li>
        <li>added: open user page (train-fever.net) when clicking on author in information window</li>
        <li>changed: major redesign</li>
        <li>changed: direct activation after installation</li>
        <li>changed: links to Homepage, More Mods, Train-Fever.net</li>
        <li>changed: double click opens information window</li>
        <li>changed: better upgrade process</li>
        <li>fixed: file overwrite warning</li>
        <li>fixed: memory access errors</li>
        <li>fixed: image errors when sorting list</li>
        <li>fixed: multi author support</li>
        <li>code cleanup</li>
      </ul>
    </li>
    <li>version 0.2.18 - build 400
      <ul>
        <li>fixed: critical bug if mod name contained special characters</li>
      </ul>
    </li>
    <li>version 0.2.17 - build 374
      <ul>
        <li>added: check online for new version</li>
      </ul>
    </li>
    <li>version 0.2.16 - build 361
      <ul>
        <li>added: TFMM version check (mods can define minimum TFMM build recommended)</li>
        <li>added: warning if unrar.dll is not found - program termination</li>
        <li>fixed: possible invalid memory access during activation</li>
        <li>fixed: unrar bug from version 0.2.15</li>
        <li>code cleanup</li>
      </ul>
    </li>
    <li>version 0.2.14
      <ul>
        <li>added: rar support</li>
        <li>added: mod dependencies</li>
        <li>fixed: various small bugs</li>
      </ul>
    </li>
    <li>version 0.1.10
      <ul>
        <li>Better handling of duplicate mods</li>
        <li>New setting to restore window location of TFMM</li>
      </ul>
    </li>
    <li>version 0.1.8
      <ul>
        <li>First version of Train Fever Mod Manager</li>
        <li>install mods</li>
        <li>activate and deactivate mods</li>
        <li>keep track of modified files</li>
      </ul>
    </li>
  </ul>
</article>");

?>