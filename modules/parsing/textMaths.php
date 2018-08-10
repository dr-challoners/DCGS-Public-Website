<?php

unset($button,$textID);
unset ($block);
if (in_array('left-block',$format) || in_array('right-block',$format)) {
  if (in_array('left-block',$format)) {
    $place = 'pull-left';
  }
  if (in_array('right-block',$format)) {
    $place = 'pull-right';
  }
  $block = '<div class="col-sm-5 '.$place.'">';
}
if (in_array('dropdown',$format)) {
  $textID = 'text'.mt_rand();
  $buttonText = explode(PHP_EOL,$row['content']);
  $button = '<a class="barLink btn btn-default btn-block hidden-print" role="button" data-toggle="collapse" href="#'.$textID.'" aria-expanded="false" aria-controls="collapseExample">';
  if (in_array('quote',$format)) {
    $button .= '<i class="fas fa-comment"></i>';
  } else {
    $button .= '<i class="fas fa-align-left"></i>';
  }
  $button .= array_shift($buttonText);
  $button .= '</a>';
  $content = array();
  foreach ($buttonText as $row) {
    if (!empty($row)) {
      $content[] = $row;
    }
  }
  $content = implode("\n\n",$content);
} else {
  $content = $row['content'];
}
$content = formatText($content);
if (!in_array('quote',$format)) {              
  $class = array();
  if (in_array('right',$format)) {
    $class[] = 'text-right';
  } elseif (in_array('centred',$format) || in_array('centre',$format) || in_array('center',$format) || in_array('centered',$format)) {
    $class[] = 'text-center';
  } elseif (in_array('justify',$format) || in_array('justified',$format)) {
    $class[] = 'text-justify';
  }
  if (in_array('lead',$format)) {
    $class[] = 'lead';
  }
  if (!empty($class)) {
    $class = implode(' ',$class);
    // preg_replace would be better here, if ever you can be bothered
    $oldTags = array(
      '<p>',
      '<h1>',
      '<h2>',
      '<h3>',
      '<h4>',
      '<h5>',
      '<h6>',
    );
    $newTags = array(
      '<p class="'.$class.'">',
      '<h1 class="'.$class.'">',
      '<h2 class="'.$class.'">',
      '<h3 class="'.$class.'">',
      '<h4 class="'.$class.'">',
      '<h5 class="'.$class.'">',
      '<h6 class="'.$class.'">',
    );
    $content = str_replace($oldTags,$newTags,$content);
  }
  if (in_array('highlight',$format) && !in_array('dropdown',$format)) {
    $content = '<div class="row highlightText"><div class="col-xs-12">'.$content.'</div></div>';
  }
} else {
  $content = '<blockquote>'.$content.'</blockquote>';
  $content = str_replace(array('[',']'),array('<footer>','</footer>'),$content);
  if (in_array('right',$format)) {
    $content = str_replace('<blockquote>','<blockquote class="blockquote-reverse">',$content);
  }
}
if (isset($button)) {
  $content = $button.'<div class="collapse" id="'.$textID.'">'.$content.'</div>';
}
if (isset($block)) {
  $content = $block.$content.'</div>';
}
if (isset($set) && !isset($block)) {
  $output['content'][$set]['set'][] = $content; 
} else {
  $output['content'][] = $content;
}

?>