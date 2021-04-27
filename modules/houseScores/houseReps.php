<?php

$houseLists = sheetToArray('1kokqHvUaDvXVUOjfRJlmC7rwGiW4Clt6Yf0LV7N31xk','data/content/',24);

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
  foreach ($houseLists['data']['Mentors'] as $entry) {
    if ($entry['house'] == $house) {
      ${$house}['Mentors']['Year '.$entry['year']][] = $entry['forename'].' '.$entry['surname'];
    }
  }
  foreach ($houseLists['data']['Form Reps'] as $entry) {
    if ($entry['house'] == $house) {
      ${$house}['Reps']['Year '.$entry['year']][] = $entry['forename'].' '.$entry['surname'];
    }
  }
  foreach ($houseLists['data']['Staff'] as $entry) {
    if ($entry['house'] == $house) {
      ${$house}['Staff'][] = $entry['title'].'&nbsp;'.$entry['forename'][0].'&nbsp;'.$entry['surname'];
    }
  }
  echo '<h2 id="'.strtolower($house).'">'.$house.'</h2>';
  echo '<h3><strong>Captains:</strong> ';
    echo ${$house}['Captain'][0].', '.${$house}['Deputy Captain'][0].' and '.${$house}['Deputy Captain'][1];
  echo '</h3>';
  echo '<p><strong>Sixth Form Mentors</strong><br />';
    for ($y = 7; $y <= 11; $y++) {
      if (isset(${$house}['Mentors']['Year '.$y])) {
        echo 'Year '.$y.': '.commaList(${$house}['Mentors']['Year '.$y]);
        echo '<br />';
      }
    }
  echo '</p>';
  echo '<p><strong>Form Representatives</strong><br />';
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
