<?php

$houseLists = sheetToArray('1aiIjsR1scp6d9GLTvd7NgsZZdgbGRywciJ-1URsPCgw','data/content/',24);

$houses = array('Foxell','Holman','Newman','Pearson','Rayner','Thorne');
function commaList($array) {
  foreach ($array as $key => $item) {
    if (!isset($string)) {
      $string = $item;
    } else {
      $string .= $item;
    }
    $a = $key+2; $b = count($array);
    if ($a == $b) { 
      $string .= ' and '; 
    } elseif ($a < $b) { 
      $string .= ', '; 
    }
  }
  return $string;
}

echo '<div class="houseLists">';
foreach ($houses as $house) {
  $$house = array();
  foreach ($houseLists['data']['House Captains'] as $entry) {
    if ($entry['house'] == $house) {
      ${$house}[$entry['rank']][] = $entry['forename'].' '.$entry['surname'];
    }
  }
  foreach ($houseLists['data']['Year 7 mentors'] as $entry) {
    if ($entry['house'] == $house) {
      ${$house}['Mentors'][] = $entry['forename'].' '.$entry['surname'];
    }
  }
  foreach ($houseLists['data']['Form representatives'] as $entry) {
    if ($entry['house'] == $house) {
      ${$house}['Reps']['Year '.$entry['year']][] = $entry['forename'].' '.$entry['surname'];
    }
  }
  foreach ($houseLists['data']['Staff'] as $entry) {
    if ($entry['house'] == $house) {
      ${$house}['Staff'][] = $entry['title'].'&nbsp;'.$entry['surname'];
    }
  }
  echo '<h2 id="'.strtolower($house).'">'.$house.'</h2>';
  echo '<h3><strong>Captains:</strong> ';
    echo ${$house}['Captain'][0].', '.${$house}['Deputy Captain'][0].' and '.${$house}['Deputy Captain'][1];
  echo '</h3>';
  echo '<p><strong>Sixth Form Mentors for Year 7</strong><br />';
    echo commaList(${$house}['Mentors']);
  echo '</p>';
  echo '<p><strong>Form representatives</strong><br />';
    for ($y = 7; $y <= 11; $y++) {
      if (isset(${$house}['Reps']['Year '.$y])) {
        echo 'Year '.$y.': '.commaList(${$house}['Reps']['Year '.$y]);
        echo '<br />';
      }
    }
  echo '</p>';
  echo '<p><strong>Staff:</strong> ';
    echo commaList(${$house}['Staff']);
  echo '</p>';
}
echo '</div>';

?>
