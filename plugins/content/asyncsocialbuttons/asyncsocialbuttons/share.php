<?php
    $permalink = $_REQUEST['permalink'];
	$buttonstate = $_REQUEST['buttonstate'];
	$title = $_REQUEST['title'];
?>

<!-- Tweeter -->
<?php if ($buttonstate[0]){ ?>
	<div class="abtwitter">
		<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo $permalink; ?>" data-text="<?php echo $title; ?>" data-count="horizontal" data-lang="en">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	</div
<?php } ?>

<!-- Facebook -->
<?php if ($buttonstate[4]){ ?>
	<div class="abfblike">
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<div class="fb-like" data-href="<?php echo $permalink; ?>" data-send="true" data-layout="button_count" data-width="90" data-show-faces="false"></div>
		<script type="text/javascript" src="http://apis.google.com/js/plusone.js"> {lang: 'en'}</script>
	</div>
<?php } ?>

<!-- Google + -->
<?php if ($buttonstate[1]){ ?>
	<div class="abgplus">
		<script type="text/javascript">
		  (function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>

		<div class="g-plusone" data-size="medium"></div>
	</div>
<?php } ?>

<!-- LinkedIn -->
<?php if ($buttonstate[2]){ ?>
	<div class="ablinkedin">
		<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
		<script type="IN/Share" data-counter="right" data-title="<?php echo $title; ?>"></script>
	</div>
<?php } ?>


<!-- Buffer -->
<?php if ($buttonstate[3]){ ?>
	<div class="abbuffer">
	<a href="http://bufferapp.com/add" class="buffer-add-button" data-count="horizontal" data-via="deconfcom" >Buffer</a><script type="text/javascript" src="http://static.bufferapp.com/js/button.js"></script>
	</div>
<?php } ?>