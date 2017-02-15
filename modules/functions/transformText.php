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
   $string = strtolower($string); // Makes all characters lowercase, to effectively allow case-insensitive searching
   $string = trim($string);
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^a-z0-9\-]/', '', $string); // Removes special chars.
   $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.   
   return $string;
}

function revert($string) {
  // Gets a cleaned string back to a crude version of its original state
  $string = str_replace('-', ' ', $string);
  $string = ucwords($string);
  return $string;
}

function makeID($string, $short = '') {
    $string = md5($string);
    if (!empty($short)) {
      $string = substr($string,0,8);
    }
    return $string;
  }

function word_cutoff($text, $length) { // Creates the preview text for articles
    if(strlen($text) > $length) {
        $text = substr($text, 0, strpos($text, ' ', $length));
		}
    return $text;
	}

?>