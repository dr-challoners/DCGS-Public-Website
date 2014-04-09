<?php
	include('header_declarations.php');
	include('header_navigation.php');
?>

<script>
  (function() {
    var cx = '000855791578980490437:ci73_tsmloo';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:search></gcse:search>

<?php include('footer.php'); ?>