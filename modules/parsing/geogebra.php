<?php

if (strpos($row['url'],'geogebra.org') !== false) {
  $id = explode('id/',$row['url'])[1];
  // http://tube.geogebra.org/material/simple/id/88993
  // rc  - right click, zooming, keyboard editing
  // ai  - input bar
  // sdz - pan and zoom
  // smb - show menu
  // stb - show toolbar (menu must be true)
  // ld  - label dragging
  // sri - show reset icon
  $src = 'https://www.geogebra.org/material/iframe/id/'.$id.'/width/640/height/480/rc/false/ai/false/sdz/true/smb/false/stb/false/stbh/true/ld/false/sri/false/at/auto';
  $output['content'][] = makeiFrame($src,'geogebra');
}

?>