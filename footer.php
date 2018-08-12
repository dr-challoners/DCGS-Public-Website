  <?php
  if (isset($error)) {
    include ('globalError.php');
  }
  ?>
  </div> <!-- .container -->
  <nav class="navbar footerBar navbar-fixed-bottom">
    <div class="container">
      <p class="pull-left visible-xs-block">DCGS, &copy; 2005-<?php echo date("y"); ?></p>
      <p class="pull-left hidden-xs">Dr Challoner's Grammar School, &copy; 2005-<?php echo date("y"); ?></p>
      <p class="pull-right visible-xs-block"><a href="/">Home</a> - <a href="#">Page top</a> - <a href="<?php echo $hardLink_privacy; ?>">Privacy</a></p>
      <p class="pull-right hidden-xs">
        <a href="/">Home</a> - <a href="<?php echo $hardLink_termdates; ?>">Term Dates</a> - <a href="<?php echo $hardLink_admissions; ?>">Admissions</a> - <a href="<?php echo $hardLink_vacancies; ?>">Vacancies</a> - <a href="<?php echo $hardLink_supportingus; ?>">Supporting the School</a> - <a href="<?php echo $hardLink_contactus; ?>">Contact Us</a> - <a href="<?php echo $hardLink_privacy; ?>">Privacy</a>
      </p>
    </div>
  </nav>
  <script>
    /*! Main */
    jQuery(document).ready(function($) {

        var navbar = $('.menuFix'),
            distance = navbar.offset().top,
            $window = $(window);

        $window.scroll(function() {
            if ($window.scrollTop() >= distance) {
                navbar.removeClass('navbar-fixed-top').addClass('navbar-fixed-top');
                $('body').css('padding-top',distance+57+'px');
                $('.dcgsBanner').css('display','none');
            } else {
                navbar.removeClass('navbar-fixed-top');
                $('body').css('padding-top', '0px');
                navbar.css('top','0px');
                $('.dcgsBanner').css('display','block');
            }
        });
    });
  </script>
  <?php if (isset($curTimestamp)) { ?>
    <script type='text/javascript' src='/modules/js/diary.js'></script>
    <script type="text/javascript" language="javascript">;
      generateDiary(moment('<?php echo date('Y-m',$curTimestamp).'-01'; ?>'), moment('<?php echo date('Y-m-d',$curTimestamp); ?>'));
    </script>
  <?php } ?>
  <script type="text/javascript">
    $(document).click(function(e) {
      if (!$(e.target).is('.panelMain')) {
        $('.collapseMain').collapse('hide');	    
      }
    });
  </script>
  <script type="text/javascript" src="/modules/js/fadeSlideShow.js"></script>
  <script type="text/javascript">
    jQuery(document).ready(function(){
      jQuery('#slideshow').fadeSlideShow();
    });
  </script>
  <script type="text/javascript" async
    src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML">
  </script>
  <script type="text/javascript" src="/modules/fancyBox/jquery.fancybox.js?v=2.1.5"></script>
  <script type="text/javascript" src="/modules/fancyBox/jquery.mousewheel-3.0.6.pack.js"></script>
  <script>
    window.twttr = (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
      if (d.getElementById(id)) return t;
      js = d.createElement(s);
      js.id = id;
      js.src = "https://platform.twitter.com/widgets.js";
      fjs.parentNode.insertBefore(js, fjs);

      t._e = [];
      t.ready = function(f) {
        t._e.push(f);
      };

      return t;
    }(document, "script", "twitter-wjs"));
  </script>
  <script>
    $(document).ready(function() {
      $(".fancyBox").fancybox({
        type : 'image',
        helpers		: {
          title	: { type : 'over' }
        }
      });
    });
  </script>
  <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</body>
</html>
