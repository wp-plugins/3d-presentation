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

<div <?php if(get_post_meta($post->ID, 'impressjs_id', true) !== '') echo "id='" . get_post_meta($post->ID, 'impressjs_id', true) . "' "  ?><?php if(get_post_meta($post->ID, 'impressjs_stepsel', true) !== '') echo "class='" . get_post_meta($post->ID, 'impressjs_stepsel', true) . "' "  ?><?php if(get_post_meta($post->ID, 'impressjs_datax', true) !== '') echo "data-x='" . get_post_meta($post->ID, 'impressjs_datax', true) . "' "  ?><?php if(get_post_meta($post->ID, 'impressjs_datay', true) !== '') echo "data-y='" . get_post_meta($post->ID, 'impressjs_datay', true) . "' "  ?><?php if(get_post_meta($post->ID, 'impressjs_dataz', true) !== '') echo "data-z='" . get_post_meta($post->ID, 'impressjs_dataz', true) . "' "  ?><?php if(get_post_meta($post->ID, 'impressjs_datascale', true) !== '') echo "data-scale='" . get_post_meta($post->ID, 'impressjs_datascale', true) . "' "  ?><?php if(get_post_meta($post->ID, 'impressjs_datarotatex', true) !== '') echo "data-rotate-x='" . get_post_meta($post->ID, 'impressjs_datarotatex', true) . "' "  ?><?php if(get_post_meta($post->ID, 'impressjs_datarotatey', true) !== '') echo "data-rotate-y='" . get_post_meta($post->ID, 'impressjs_datarotatey', true) . "' "  ?><?php if(get_post_meta($post->ID, 'impressjs_datarotate', true) !== '') echo "data-rotate='" . get_post_meta($post->ID, 'impressjs_datarotate', true) . "' "  ?> >
<?php if (get_post_status ($ID) == 'draft') { ?> <h4><span style="color:#ff0000;">DRAFT</span></h4> <?php }; ?>
<? echo $post->post_content; ?>
</div>

</div>

<!--
<div class="hint">
    <p>Use a spacebar or arrow keys to navigate</p>
</div>
<script>
if ("ontouchstart" in document.documentElement) { 
    document.querySelector(".hint").innerHTML = "<p>Tap on the left or right to navigate</p>";
}
</script>
-->

<script src="<?php echo plugins_url('js/impress.js',__FILE__); ?>"></script>
<script>impress().init();</script>
</body>
</html>