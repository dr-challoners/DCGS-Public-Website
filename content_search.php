<?php
	include('header_declarations.php');
	include('header_navigation.php');

  echo '<h1>Tagged pages</h1>';
  echo '<p>We are currently developing this index of content to help you find what you are looking for. Search for pages according to their relevant categories.</p>';

  foreach ($mainData['data']['tags'] as $tag => $pages) {
    if (count($pages) > 1) {
      echo '<h3>'.$tag.'</h3>';
      echo '<ul>';
      foreach ($pages as $page) {
        $page[2] = str_ireplace('[hidden]','',$page[2]);
        $page[2] = trim($page[2]);
        $page[2] = formatText($page[2],0);
        echo '<li><p>';
          echo '<a href="/c/'.clean($page[0]).'/'.clean($page[1]).'/'.clean($page[2]).'">';
            echo $page[2];
          echo '</a>';
        echo '</p></li>';
      }
      echo '</ul>';
    }
  }

  include('footer.php'); ?>