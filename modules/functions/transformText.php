<?php




function formatText($text,$paragraphs = 1) {
  //$text = htmlentities($text);
  $text = Parsedown::instance()->parse($text);
  if ($paragraphs == 0) {
    $text = str_replace(array('<p>','</p>'),'',$text);
  }
  return $text;
}

function clean($string) {
  $replaceChars = array('-' => '~0', '\'' => '~1', ',' => '~2', ':' => '~3', '?' => '~4', '!' => '~5', '&' => '~6', '@' => '~7', '%' => '~8');
  $string = strtolower($string); // Make all characters lowercase, to effectively allow case-insensitive searching
  $string = trim($string);
  $string = strtr($string, $replaceChars); // Encode some characters that we would like to keep
  $string = str_replace(' ', '-', $string); // Replace all spaces neatly with hyphens (better than using %20 all over the place)
  $string = preg_replace('/[^a-z0-9\-~]/', '', $string); // Remove any remaining special characters
  $string = preg_replace('/-+/', '-', $string); // Replace multiple hyphens with a single one, should they occur 
  return $string;
}

function revert($string) {
  // Gets a cleaned string back to (approximately) its original state
  $replaceChars = array('-' => '~0', '\'' => '~1', ',' => '~2', ':' => '~3', '?' => '~4', '!' => '~5', '&' => '~6', '@' => '~7', '%' => '~8');
  $returnChars  = array_flip($replaceChars);
  $string = str_replace('-', ' ', $string);
  $string = str_replace('~0', '~0 ', $string); // This and the final line allow both parts of a hyphenated word to be capitalised
  $string = strtr($string, $returnChars);
  $string = ucwords($string);
  $string = str_replace('- ', '-', $string);
  return $string;
}

function makeID($string, $short = '') {
    $string = md5($string);
    if (!empty($short)) {
      $string = substr($string,0,8);
    }
    return $string;
  }

?>