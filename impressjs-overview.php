<?php
$options = get_option('lpg_impressjs_options');
$s_textarea_one = $options['textarea_one'];
$s_textarea_two = $options['textarea_two'];
$s_textarea_three = $options['textarea_three'];
$s_textarea_four = $options['textarea_four'];
$s_textarea_five = $options['textarea_five'];
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=1024" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title><?php echo $s_textarea_three ?></title>
    
    <meta name="description" content="<?php echo $s_textarea_four ?>" />
    <meta name="author" content="<?php echo $s_textarea_five ?>" />

    <!--
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:regular,semibold,italic,italicsemibold|PT+Sans:400,700,400italic,700italic|PT+Serif:400,700,400italic,700italic" rel="stylesheet" />
	-->
	
	<link href="<?php echo $s_textarea_one ?>" />
	
    <link href="<?php echo plugins_url('css/impress-base.css',__FILE__); ?>" rel="stylesheet" />
	
	<style type="text/css">
	<?php echo $s_textarea_two ?>
	</style>
    
	<link rel="shortcut icon" href="<?php echo plugins_url('impressjs-icon16.png',__FILE__); ?>" />
	<link rel="apple-touch-icon" href="<?php echo plugins_url('apple-touch-icon.png',__FILE__); ?>" />
</head>
<body class="impress-not-supported">
<div class="fallback-message">
    <p>Your browser <b>doesn't support the features required</b> by impress.js, so you are presented with a simplified version of this presentation.</p>
    <p>For the best experience please use the latest <b>Chrome</b>, <b>Safari</b> or <b>Firefox</b> browser.</p>
</div>
<div id="impress">

<?php

if( current_user_can('edit_posts'))  { 
	$args=array(
		'post_type' => 'impressjsstep',
		'post_status' => array('publish', 'draft'),
		'posts_per_page' => -1,
		'meta_key' => 'impressjs_sequencenumber',
		'orderby' => 'meta_value_num',
		'order' => 'ASC'
    ); } else {
	$args=array(
		'post_type' => 'impressjsstep',
		'post_status' => array('publish'),
		'posts_per_page' => -1,
		'meta_key' => 'impressjs_sequencenumber',
		'orderby' => 'meta_value_num',
		'order' => 'ASC'
	);
};

query_posts( $args );

$minX = 100000;
$maxX = -100000;
$minY = 100000;
$maxY = -100000;
$maxScale = 1;
	
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php 

if(get_post_meta(get_the_ID(), 'impressjs_datax', true) !== '') {
	if (intval(get_post_meta(get_the_ID(), 'impressjs_datax', true)) < $minX) $minX = get_post_meta(get_the_ID(), 'impressjs_datax', true) ;
	if (intval(get_post_meta(get_the_ID(), 'impressjs_datax', true)) > $maxX ) $maxX = get_post_meta(get_the_ID(), 'impressjs_datax', true) ;
};

if(get_post_meta(get_the_ID(), 'impressjs_datay', true) !== '') {
	if (intval(get_post_meta(get_the_ID(), 'impressjs_datay', true)) < $minY) $minY = get_post_meta(get_the_ID(), 'impressjs_datay', true) ;
	if (intval(get_post_meta(get_the_ID(), 'impressjs_datay', true)) > $maxY ) $maxY = get_post_meta(get_the_ID(), 'impressjs_datay', true) ;
};

if(get_post_meta(get_the_ID(), 'impressjs_datascale', true) !== '') {
	if (intval(get_post_meta(get_the_ID(), 'impressjs_datascale', true)) > $maxScale ) $maxScale = get_post_meta(get_the_ID(), 'impressjs_datascale', true) ;
};

?>

<div <?php if(get_post_meta(get_the_ID(), 'impressjs_id', true) !== '') echo "id='" . get_post_meta(get_the_ID(), 'impressjs_id', true) . "' "  ?><?php if(get_post_meta(get_the_ID(), 'impressjs_stepsel', true) !== '') echo "class='" . get_post_meta(get_the_ID(), 'impressjs_stepsel', true) . "' "  ?><?php if(get_post_meta(get_the_ID(), 'impressjs_datax', true) !== '') echo "data-x='" . get_post_meta(get_the_ID(), 'impressjs_datax', true) . "' "  ?><?php if(get_post_meta(get_the_ID(), 'impressjs_datay', true) !== '') echo "data-y='" . get_post_meta(get_the_ID(), 'impressjs_datay', true) . "' "  ?><?php if(get_post_meta(get_the_ID(), 'impressjs_dataz', true) !== '') echo "data-z='" . get_post_meta(get_the_ID(), 'impressjs_dataz', true) . "' "  ?><?php if(get_post_meta(get_the_ID(), 'impressjs_datascale', true) !== '') echo "data-scale='" . get_post_meta(get_the_ID(), 'impressjs_datascale', true) . "' "  ?><?php if(get_post_meta(get_the_ID(), 'impressjs_datarotatex', true) !== '') echo "data-rotate-x='" . get_post_meta(get_the_ID(), 'impressjs_datarotatex', true) . "' "  ?><?php if(get_post_meta(get_the_ID(), 'impressjs_datarotatey', true) !== '') echo "data-rotate-y='" . get_post_meta(get_the_ID(), 'impressjs_datarotatey', true) . "' "  ?><?php if(get_post_meta(get_the_ID(), 'impressjs_datarotate', true) !== '') echo "data-rotate='" . get_post_meta(get_the_ID(), 'impressjs_datarotate', true) . "' "  ?> >
<?php if (get_post_status (get_the_ID()) == 'draft') { ?> <h4><span style="color:#ff0000;">DRAFT</span></h4> <?php }; ?>
<?php 
//the_content(); 
echo get_the_content();
?>
</div>

<?php endwhile; else: ?>
<div id="empty" class="step" data-x="0" data-y="0" data-scale="1">
        <p>We are working on an impressive 3D presentation here.</p>
		<p>Stay tuned.</p>
</div>
<?php endif; 

//Reset Query
wp_reset_query();

?>

<div id="overviewinedit" class="step" data-x="<?php echo ($minX+$maxX)/2 ?>" data-y="<?php echo ($minY+$maxY)/2 ?>" data-scale="<?php echo $maxScale ?>" >
</div>


</div>

<div class="hint">
    <p>Use a spacebar or arrow keys to navigate</p>
</div>
<script>
if ("ontouchstart" in document.documentElement) { 
    document.querySelector(".hint").innerHTML = "<p>Tap on the left or right to navigate</p>";
}
</script>
<script src="<?php echo plugins_url('js/impress.js',__FILE__); ?>"></script>
<script>impress().init();</script>
</body>
</html>



