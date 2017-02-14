<?php

$content = explode(PHP_EOL, $row['content']);
foreach ($content as $credit) {
  $credit = trim($credit);
  if (!empty($credit)) {
    if (stripos($credit,', Year ') == true) {
      // This tidies up the way a student's year is displayed, if editors don't follow the style guide
      $credit = str_ireplace(', Year',' (Year',$credit);
      $credit = $credit.')';
    }
    // Stop just the year number from dropping to a new line on long lists of credits
    $credit = str_ireplace('Year ','Year&nbsp;',$credit);
    if (!empty($row['url'])) {
      $credit = '<a href="'.$row['url'].'">'.$credit.'</a>';
    }
    $output['info'][substr($dataType,4)][] = $credit;
  }
}

?>