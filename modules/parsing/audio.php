<?php

if (strpos($row['url'],'soundcloud') !== false) {
  $sc = file_get_contents('http://api.soundcloud.com/resolve.json?url='.$row['url'].'&client_id=59f4a725f3d9f62a3057e87a9a19b3c6');
  $sc = json_decode($sc);
  $id = $sc->id;
  if ($sc->kind == 'playlist') {
    $outputType = 'playlists';
    $boxType    = 'audioPlaylist';
  } else {
    $outputType = 'tracks';
    $boxType    = 'audio';
  }
  $src  = 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/'.$outputType.'/'.$id.'&amp;color=2358A3&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=false';
  $output['content'][] = makeiFrame($src,$boxType,$row['content'],$row['format']);
}
elseif (strpos($row['url'],'audioboom') !== false) {
  if (strpos($row['url'],'playlists') === false) {
    $src = explode('boos/',$row['url'])[1];
    $src = explode('?',$src)[0];
    $src = str_replace('.embed','',$src);
    $src = '//embeds.audioboom.com/boos/'.$src.'/embed/v3?link_color=%23173F7A&amp;image_option=none';
    $output['content'][] = makeiFrame($src,'audio',$row['content']);
  } else {
    $src = explode('playlists/',$row['url'])[1];
    $src = str_replace('.embed','',$src);
    $src = '//embeds.audioboom.com/publishing/playlist/v4?bg_fill_col=%23f5f5f5&amp;boo_content_type=playlist&amp;data_for_content_type='.$src.'&amp;image_option=none&amp;link_color=%23173F7A&amp;src=https%3A%2F%2Fapi.audioboom.com%2Fplaylists%2F'.$src;
    $output['content'][] = makeiFrame($src,'audioPlaylist',$row['content']);
  }
}

?>