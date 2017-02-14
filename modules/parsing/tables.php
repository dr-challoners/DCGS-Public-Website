<?php

if (strpos($row['url'],'google.com/spreadsheets') !== false) {
  $cutoff = strpos($row['url'],'spreadsheets/d/');
  $cutoff = $cutoff+15;
  $sheetID = substr($row['url'],$cutoff);
  $sheetID = explode('/',$sheetID)[0];
  $tableArray = sheetToArray($sheetID,'data/content',1);
  $processedTable = array();
  foreach ($tableArray['data'] as $table => $rows) {
    $processedTable[$table] = array();
    foreach ($rows as $line) {
      if (!isset($processedTable[$table]['th'])) {
        // Define the table headings
        // First check to see if all of the keys are blank (begin with _): if they are, the headings will be the content, otherwise the headings will be the keys.
        $blanks = ''; $tableKeys = array();
        foreach ($line as $key => $cell) {
          $blanks .= $key[0];
          $tableKeys[] = $key;
        }
        $blanks = str_replace('_','',$blanks);
        if ($blanks == '') {
          foreach ($line as $key => $cell) {
            if ($cell[0] != '_') {
              $processedTable[$table]['th'][$key] = $cell;
            } else {
              $processedTable[$table]['th'][$key] = '';
            }
          }
          $r = 1;
        } else {
          foreach ($line as $key => $cell) {
            if ($key[0] != '_') {
              $processedTable[$table]['th'][$key] = ucwords($key);
            } else {
              $processedTable[$table]['th'][$key] = '';
            }
          }
          foreach ($line as $key => $cell) {
            $processedTable[$table][1][$key] = $cell;
          }
          $r = 2;
        }
        
      } else {
        // Now get on with populating the rows
        foreach ($tableKeys as $key) {
          if (isset($line[$key])) {
            $processedTable[$table][$r][$key] = $line[$key];
          } else {
            $processedTable[$table][$r][$key] = '';
          }
        }
        $r++;
      }
    }
  }
  // Now display the table
  $content = '';
  if (strpos($row['format'],'tab') !== false) {
    $tabList = '';
  }
  unset($active);
  foreach ($processedTable as $table => $rows) {
    unset($top);
    if (strpos($row['format'],'tab') !== false) {
      $tabList .= '<li role="presentation"';
      if (!isset($active)) {
        $tabList .= ' class="active"';
      }
      $tabList .= '><a href="#'.clean($table).'" aria-controls="'.clean($table).'" role="tab" data-toggle="tab"><h3>'.$table.'</h3></a></li>';
      $content .= '<div role="tabpanel" class="tab-pane';
      if (!isset($active)) {
        $content .= ' active';
        $active = 1;
      }
      $content .= '" id="'.clean($table).'">';
    }
    if (strpos($row['format'],'title') !== false && strpos($row['format'],'tab') === false && $table[0] != '_') {
      $content .= '<h3>'.$table.'</h3>';
    }
    $content .= '<table class="table table-hover table-condensed">';
    foreach ($rows as $line) {
      if (!isset($top)) {
        $content .= '<thead><tr>';
        foreach ($line as $cell) {
          $content .= '<th><h4>'.$cell.'</h4></th>';
        }
        $content .= '</tr></thead>';
        $top = 1;
      } else {
        $content .= '<tr>';
        foreach ($line as $cell) {
          $content .= '<td>'.$cell.'</td>';
        }
        $content .= '</tr>';
      }
    }
    $content .= '</table>';
    if (strpos($row['format'],'tab') !== false) {
      $content .= '</div>';
    }
  }
  if (strpos($row['format'],'tab') !== false) {
    $tabList = '<ul class="nav nav-tabs tableTabs" role="tablist">'.$tabList.'</ul>';
    $content = $tabList.'<div class="tab-content">'.$content.'</div>';
  }
  $output['content'][] = $content;
}

?>