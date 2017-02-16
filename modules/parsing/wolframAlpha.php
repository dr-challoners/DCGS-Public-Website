<?php

if (strpos($row['url'],'wolframalpha.com/widget') !== false) {
  $id = explode('id=',$row['url'])[1];
  $output['content'][] = '<script type="text/javascript" id="WolframAlphaScript'.$id.'" src="//www.wolframalpha.com/widget/widget.jsp?id='.$id.'&theme=orange&output=popup"></script>';
}

?>