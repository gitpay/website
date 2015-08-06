<?php

$login = $_REQUEST['user'];

?>

<!--
jQuery Feeds and site by Camilo Aguilar.

Except for the icons and the js libraries this site is UNLICENSED (http://unlicense.org/).

Icons by Icon8 (http://icons8.com/) and Icon Dock (http://icondock.com).
-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width; initial-scale=1.0" />

		<title>Gitpay Activity Stream for <?php echo $login ?></title>

		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>

		<link rel="stylesheet" href="http://camagu.github.io/jquery-feeds/ui/styles.css"/>

		<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

		<!-- <link rel="shortcut icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" href="/apple-touch-icon.png" /> -->

	</head>

	<body>
		<div class="demos page">
			<header class="header">
				<hgroup class="brand">
					<h1 class="title">Activity Stream for <a href="<?php echo $login ?>"><?php echo $login ?></a></h1>
					<!-- <h2 class="slogan">News and social activity streams made easy</h2> -->
				</hgroup>

			</header>

			<div class="content">

			<div class="code">
				<h2>In your markup:</h2>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/html&quot;</span> <span class="na">id=</span><span class="s">&quot;entryTmpl&quot;</span><span class="nt">&gt;</span>
	<span class="o">&lt;</span><span class="nx">article</span> <span class="kr">class</span><span class="o">=</span><span class="s2">&quot;&lt;!=source!&gt; entry&quot;</span><span class="o">&gt;</span>
		<span class="o">&lt;</span><span class="nx">p</span><span class="o">&gt;</span>
			<span class="o">&lt;</span><span class="nx">a</span> <span class="kr">class</span><span class="o">=</span><span class="s2">&quot;link&quot;</span> <span class="nx">href</span><span class="o">=</span><span class="s2">&quot;&lt;!=feedLink!&gt;&quot;</span> <span class="nx">title</span><span class="o">=</span><span class="s2">&quot;&lt;!=feedDescription!&gt;&quot;</span><span class="o">&gt;&lt;!=</span><span class="nx">feedTitle</span><span class="o">!&gt;&lt;</span><span class="err">/a&gt;</span>
			<span class="o">&lt;</span><span class="nx">span</span> <span class="kr">class</span><span class="o">=</span><span class="s2">&quot;publishedDate&quot;</span><span class="o">&gt;&lt;!=</span><span class="nx">publishedDate</span><span class="o">!&gt;&lt;</span><span class="err">/span&gt;</span>
		<span class="o">&lt;</span><span class="err">/p&gt;</span>
		<span class="o">&lt;</span><span class="nx">p</span> <span class="kr">class</span><span class="o">=</span><span class="s2">&quot;author&quot;</span><span class="o">&gt;&lt;!=</span><span class="nx">author</span><span class="o">!&gt;&lt;</span><span class="err">/p&gt;</span>
		<span class="o">&lt;</span><span class="nx">div</span> <span class="kr">class</span><span class="o">=</span><span class="s2">&quot;content&quot;</span><span class="o">&gt;&lt;!=</span><span class="nx">content</span><span class="o">!&gt;&lt;</span><span class="err">/div&gt;</span>
	<span class="o">&lt;</span><span class="err">/article&gt;</span>
<span class="nt">&lt;/script&gt;</span>
</code></pre></div>

				<h2>In your script:</h2>
<div class="highlight"><pre><code class="javascript"><span class="nx">$</span><span class="p">(</span><span class="s1">&#39;.feed&#39;</span><span class="p">).</span><span class="nx">feeds</span><span class="p">({</span>
	<span class="nx">feeds</span> <span class="o">:</span> <span class="p">{</span>
		<span class="nx">facebook</span> <span class="o">:</span> <span class="s1">&#39;http://www.facebook.com/feeds/page.php?format=atom10&amp;id=8305888286&#39;</span><span class="p">,</span>
		<span class="nx">twitter</span> <span class="o">:</span> <span class="s1">&#39;http://search.twitter.com/search.rss?q=from:RollingStones&amp;rpp=100&#39;</span><span class="p">,</span>
		<span class="nx">pinterest</span> <span class="o">:</span> <span class="s1">&#39;http://pinterest.com/rollingstones50/feed.rss&#39;</span><span class="p">,</span>
		<span class="nx">tumblr</span> <span class="o">:</span> <span class="s1">&#39;http://rollingstonesofficial.tumblr.com/rss&#39;</span>
	<span class="p">},</span>
	<span class="nx">preprocess</span> <span class="o">:</span> <span class="kd">function</span><span class="p">(</span><span class="nx">feed</span><span class="p">)</span> <span class="p">{</span>
		<span class="c1">// Using moment.js to diplay dates as time ago</span>
		<span class="k">this</span><span class="p">.</span><span class="nx">publishedDate</span> <span class="o">=</span> <span class="nx">moment</span><span class="p">(</span><span class="k">this</span><span class="p">.</span><span class="nx">publishedDate</span><span class="p">).</span><span class="nx">fromNow</span><span class="p">();</span>
	<span class="p">},</span>
	<span class="nx">entryTemplate</span> <span class="o">:</span> <span class="s1">&#39;entryTmpl&#39;</span><span class="p">,</span>
	<span class="nx">onComplete</span> <span class="o">:</span> <span class="kd">function</span><span class="p">(</span><span class="nx">entries</span><span class="p">)</span> <span class="p">{</span>
		<span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">).</span><span class="nx">find</span><span class="p">(</span><span class="s1">&#39;a&#39;</span><span class="p">).</span><span class="nx">attr</span><span class="p">(</span><span class="s1">&#39;target&#39;</span><span class="p">,</span> <span class="s1">&#39;_blank&#39;</span><span class="p">);</span>
	<span class="p">}</span>
<span class="p">});</span>
</code></pre></div>

				<p>Check the source code of this page for more information.</p>
			</div>

			<!----------------------------------------------------------------/
				 jQuery Feeds Demo
			/----------------------------------------------------------------->

			<!-- Define the container -->
			<div class="feed">&nbsp;</div>

			<!-- Define the entries' template inside a script tag.
				 Set it's *type* to *text/html* and assign it an *id*.
				 You can print the entry's properties by writting their name inside <!= !>. -->
			<script type="text/html" id="entryTmpl">
				<article class="<!=source!> entry">
					<p>
						<a class="link" href="<!=feedLink!>" title="<!=feedDescription!>"><!=feedTitle!></a>
						<! if (publishedDate) { !><span class="publishedDate"><!=publishedDate!></span><! } !></p>
					<p class="author"><!=author!></p>
					<div class="content"><!=content!></div>
				</article>
			</script>

			<!-- Got to the bottom of the source to check the scripts. -->

			<!----------------------------------------------------------------/
			/----------------------------------------------------------------->

			</div>

			<footer class="footer">
				<p>jQuery Feeds and site by Camilo Aguilar. Except for the icons and the js libraries this site is <a href="http://unlicense.org/" title="Check lincense specifics">UNLICENSED</a>. Icons by <a href="http://icons8.com/">Icon8</a> and <a href="http://icondock.com">Icon Dock</a>.</p>
			</footer>
		</div>

		<!-- Include jQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

		<!-- Include site script -->
		<script src="http://camagu.github.io/jquery-feeds/ui/site.js"></script>


		<!-- Load jQuery Feeds -->
		<script src="http://camagu.github.io/jquery-feeds/jquery.feeds.js" charset="utf-8"></script>

		<!-- Load moment.js, used to format dates, totally optional -->
		<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/1.6.2/moment.min.js" charset="utf-8"></script>

		<!-- Using Pure CSS Masonry which is not supported by IE. Fallback: original lib. -->
		<!--[if lte IE 9]><script src="http://cdnjs.cloudflare.com/ajax/libs/masonry/2.1.04/jquery.masonry.min.js"></script><![endif]-->
		<script>
			$(document).ready(function() {

				/* ==================================================================
				   Demo scripts
				   ================================================================== */

				// Attach feeds to container - $( container ).feeds({ options...});
				$('.feed').feeds({

					// Feeds: Set keys and paths of feeds
					feeds : {
            github : 'https://github.com/<?php echo $login ?>.atom',
					},

					// Preprocess: manipulated entries data before it's rendered
					preprocess : function(feed) {
						// Inside the callback 'this' corresponds to the entry being processed

						if (!this.publishedDate) {
							return;
						}

						// Example: Using moment.js to format dates as time ago, totally optional.
						this.publishedDate = moment(this.publishedDate).fromNow();
					},

					// entryTemplate: template used to render entries. Can be a string, an id or a callback.
					// Using the id of the template defined in the markup.
					entryTemplate : 'entryTmpl',

					// loadingTemplate: template used to show while entries are being retrieved. Can be a string, an id or a callback.
					loadingTemplate: '<p class="loading entry">Loading entries, hold on.</p>',

					// onComplete: called when all entries are rendered.
					onComplete : function(entries) {
						// Setting all links as external.
						// Inside the callback 'this' corresponds to the feeds' container.
						$(this).find('a').attr('target', '_blank');

						// Pure CSS Masonry fallback for IE
						if ($.browser.msie && parseInt($.browser.version.substr(0, 1)) < 10) {
							$('.feed').masonry({
								gutterWidth: 12
							});
						}

					}
				});
			});
		</script>

	</body>
</html>
