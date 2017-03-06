<?php

// Google Forms (possibly other forms later, but for now...)
if (strpos($row['url'],"docs.google") !== false && strpos($row['url'],"forms") !== false) {
  $id = strpos($row['url'],'d/');
  $id = substr($row['url'],$id+2);
  $id = explode('/',$id)[0];
  $output['content'][] = makeiFrame('https://docs.google.com/forms/d/'.$id.'/viewform?embedded=true','form',$row['content']);
}

?>