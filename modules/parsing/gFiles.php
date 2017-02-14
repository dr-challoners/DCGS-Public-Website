<?php

if (strpos($row['url'],"docs.google") !== false) {
  unset($docType);
  if (strpos($row['url'],"document") !== false) {
    $docType = 'document';
  } elseif (strpos($row['url'],"presentation") !== false) {
    $docType = 'presentation';
  } elseif (strpos($row['url'],"spreadsheets") !== false) {
    $docType = 'spreadsheets';
  }
  if (isset($docType)) {
    $id = strpos($row['url'],'d/');
    $id = substr($row['url'],$id+2);
    $id = explode('/',$id)[0];
    $url = 'https://docs.google.com/'.$docType.'/d/'.$id.'/preview';
    $output['content'][] = makeiFrame($url,$docType,$row['content']);
  }
}

?>