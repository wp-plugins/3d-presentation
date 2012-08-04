<?php
/*
Plugin Name: 3D Presentation
Description: 3D Presentation Plugin allows to create, edit and publish an impress.js presentation in your WordPress site. Impress.js is a framework by Bartek Szopka based on the power of CSS3 transforms and transitions in modern browsers and inspired by the idea behind prezi.com.
Version: 1.0
Author: Luca Paggetti
License: GPLv2
Author URI: http://www.minimal-zone.com
*/


// ------------------------------------------------------------------------
// REQUIRE MINIMUM VERSION OF WORDPRESS:                                               
// ------------------------------------------------------------------------
// THIS IS USEFUL IF YOU REQUIRE A MINIMUM VERSION OF WORDPRESS TO RUN YOUR
// PLUGIN. IN THIS PLUGIN THE WP_EDITOR() FUNCTION REQUIRES WORDPRESS 3.0 
// OR ABOVE. ANYTHING LESS SHOWS A WARNING AND THE PLUGIN IS DEACTIVATED.                    
// ------------------------------------------------------------------------

function lpg_impressjs_requires_wordpress_version() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.0", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires WordPress 3.0 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
		}
	}
}
add_action( 'admin_init', 'lpg_impressjs_requires_wordpress_version' );
	


/* Set up the post types. */
add_action( 'init', 'lpg_impressjs_register_post_types' );

/* Registers post types. */
function lpg_impressjs_register_post_types() {

    /* Set up the arguments for the 'impress.js slide' post type. */
    $slide_args = array(
        'public' => true,
		'menu_icon' => plugins_url( 'impressjs-icon16.png' , __FILE__ ),
        'query_var' => 'impressjsstep',
        'rewrite' => array(
            'slug' => 'impressjs/steps',
            'with_front' => false,
        ),
        'supports' => array(
            'title',
			'slug',
            'editor'
        ),
        'labels' => array(
            'name' => '3D Presentation',
            'singular_name' => 'Step',
            'add_new' => 'Add New Step',
            'add_new_item' => 'Add New Step',
            'edit_item' => 'Edit Step',
            'new_item' => 'New Step',
            'view_item' => 'View Step',
            'search_items' => 'Search Step',
            'not_found' => 'No Steps Found',
            'not_found_in_trash' => 'No Steps Found In Trash'
        ),
    );

    /* Register the impress.js step post type. */
    register_post_type( 'impressjsstep', $slide_args );
	//add_rewrite_rule( 'presentation/$',plugins_url( 'impressjs-new.php' , __FILE__ ), 'top' );
	//add_rewrite_rule( '^impressjspresentation$', 'index.php?impressjspresentation=true', 'top' );
	//flush_rewrite_rules( false );
}

add_action( 'query_vars', 'impressjspresentation_query_vars' );
function impressjspresentation_query_vars( $query_vars )
{
    $query_vars[] = '3dpresentation';
    return $query_vars;
}

add_action( 'parse_request', 'impressjspresentation_parse_request' );
function impressjspresentation_parse_request( &$wp )
{
    if ( array_key_exists( '3dpresentation', $wp->query_vars ) ) {
        include( dirname( __FILE__ ) . '/impressjs-presentation.php' );
        exit();
    }
}

add_action( 'query_vars', 'impressjsoverview_query_vars' );
function impressjsoverview_query_vars( $query_vars )
{
    $query_vars[] = 'impressjsoverview';
    return $query_vars;
}

add_action( 'parse_request', 'impressjsoverview_parse_request' );
function impressjsoverview_parse_request( &$wp )
{
    if ( array_key_exists( 'impressjsoverview', $wp->query_vars ) ) {
        include( dirname( __FILE__ ) . '/impressjs-overview.php' );
        exit();
    }
}

add_action('admin_head', 'plugin_header');

function plugin_header() {
        global $post_type;
	?>
	<style>
	<?php if (($_GET['post_type'] == 'impressjsstep') || ($post_type == 'impressjsstep')) : ?>
	#icon-edit { background:transparent url('<?php echo plugins_url( 'impressjs-icon32.png' , __FILE__ );?>') no-repeat; }		
	<?php endif; ?>
        </style>
        <?php
}

// Add the Meta Box
function add_impressjs_meta_box() {
    add_meta_box(
		'impressjs_meta_box', // $id
		'3D Presentation Step Attributes', // $title 
		'show_impressjs_meta_box', // $callback
		'impressjsstep', // $page
		'normal', // $context
		'high'); // $priority
}
add_action('add_meta_boxes', 'add_impressjs_meta_box');

// Field Array
$prefix = 'impressjs_';
$impressjs_meta_fields = array(
	array(
		'label'=> 'sequence',
		'desc'	=> 'sequence number',
		'id'	=> $prefix.'sequencenumber',
		'type'	=> 'text',
		'validation' => 'required'
	),
	array(
		'label'=> 'id',
		'desc'	=> 'id',
		'id'	=> $prefix.'id',
		'type'	=> 'text'
	),
	array(
		'label'=> 'data-x',
		'desc'	=> 'x offset',
		'id'	=> $prefix.'datax',
		'type'	=> 'text'
	),
	array(
		'label'=> 'data-y',
		'desc'	=> 'y offset',
		'id'	=> $prefix.'datay',
		'type'	=> 'text'
	),
	array(
		'label'=> 'data-z',
		'desc'	=> 'z offset',
		'id'	=> $prefix.'dataz',
		'type'	=> 'text'
	),
	array(
		'label'=> 'data-scale',
		'desc'	=> 'scale',
		'id'	=> $prefix.'datascale',
		'type'	=> 'text'
	),
	array(
		'label'=> 'data-rotate',
		'desc'	=> 'rotate',
		'id'	=> $prefix.'datarotate',
		'type'	=> 'text'
	),
	array(
		'label'=> 'data-rotate-x',
		'desc'	=> 'rotate x',
		'id'	=> $prefix.'datarotatex',
		'type'	=> 'text'
	),
	array(
		'label'=> 'data-rotate-y',
		'desc'	=> 'rotate y',
		'id'	=> $prefix.'datarotatey',
		'type'	=> 'text'
	),
	array(
		'label'=> 'step selector',
		'desc'	=> 'step selector (css)',
		'id'	=> $prefix.'stepsel',
		'type'	=> 'text',
		'std' => 'step'
	)
);

// The Callback
function show_impressjs_meta_box() {
global $impressjs_meta_fields, $post;
// Use nonce for verification
echo '<input type="hidden" name="impressjs_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	
	// Begin the field table and loop

	echo '<table class="form-table">';
	
	echo '<tr>';
	echo '<th><label>overview</label></th>';
	echo '<td>';
	echo '<iframe style="border:medium double rgb(223,223,223)" src="http://localhost/mz/index.php?impressjsoverview=true#/overviewinedit" width="300" height="200">';
	echo '<p>Your browser does not support iframes.</p>';
	echo '</iframe>';
	echo '</td>';
	echo '</tr>';
	
	foreach ($impressjs_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field['id'], true);
		// begin a table row with
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
				<td>';
				switch($field['type']) {
					// case items will go here
					
					// text
					/*
					case 'text':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					*/
					case 'text':
						echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" ', $field['validation'],' />
						<br /><span class="description">'.$field['desc'].'</span>';
					break;
					
					// textarea
					case 'textarea':
						echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					
					// checkbox
					case 'checkbox':
						echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
							<label for="'.$field['id'].'">'.$field['desc'].'</label>';
					break;
					
					// select
				case 'select':
					echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
					foreach ($field['options'] as $option) {
						echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
					}
					echo '</select><br /><span class="description">'.$field['desc'].'</span>';
				break;
				
				// radio
				case 'radio':
					foreach ( $field['options'] as $option ) {
						echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
								<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
					}
				break; 

				// checkbox_group
				case 'checkbox_group':
					foreach ($field['options'] as $option) {
						echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' /> 
								<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
					}
					echo '<span class="description">'.$field['desc'].'</span>';
				break;

				 // post_list
				case 'post_list':
				$items = get_posts( array (
					//'post_type'	=> $field['post_type'],
					'post_type'	=> 'impressjsstep',
					'posts_per_page' => -1
				));
					echo '<select name="'.$field['id'].'" id="'.$field['id'].'">
							<option value="">Select One</option>'; // Select One
						foreach($items as $item) {
							echo '<option value="'.$item->ID.'"',$meta == $item->ID ? ' selected="selected"' : '','>'.$item->post_type.': '.$item->post_title.'</option>';
						} // end foreach
					echo '</select><br /><span class="description">'.$field['desc'].'</span>';
				break; 				
					
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}
  
// Save the Data
function save_impressjs_meta($post_id) {
    global $impressjs_meta_fields;
	
	// verify nonce
	if (!wp_verify_nonce($_POST['impressjs_meta_box_nonce'], basename(__FILE__))) 
		return $post_id;
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}
	
	// loop through fields and save the data
	foreach ($impressjs_meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	} // end foreach
}
add_action('save_post', 'save_impressjs_meta');


// ------------------------------------------------------------------------
// PLUGIN PREFIX:                                                          
// ------------------------------------------------------------------------
// A PREFIX IS USED TO AVOID CONFLICTS WITH EXISTING PLUGIN FUNCTION NAMES.
// WHEN CREATING A NEW PLUGIN, CHANGE THE PREFIX AND USE YOUR TEXT EDITORS 
// SEARCH/REPLACE FUNCTION TO RENAME THEM ALL QUICKLY.
// ------------------------------------------------------------------------

// 'lpg_impressjs_' is a prefix

// ------------------------------------------------------------------------
// REGISTER HOOKS & CALLBACK FUNCTIONS:
// ------------------------------------------------------------------------
// HOOKS TO SETUP DEFAULT PLUGIN OPTIONS, HANDLE CLEAN-UP OF OPTIONS WHEN
// PLUGIN IS DEACTIVATED AND DELETED, INITIALISE PLUGIN, ADD OPTIONS PAGE.
// ------------------------------------------------------------------------

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'lpg_impressjs_add_defaults');
//register_deactivation_hook( __FILE__, 'lpg_impressjs_deactivate' );
register_uninstall_hook(__FILE__, 'lpg_impressjs_delete_plugin_options');
add_action('admin_init', 'lpg_impressjs_init' );
add_action('admin_menu', 'lpg_impressjs_add_options_page');
add_filter( 'plugin_action_links', 'lpg_impressjs_plugin_action_links', 10, 2 );

// --------------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_uninstall_hook(__FILE__, 'lpg_impressjs_delete_plugin_options')
// --------------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE USER DEACTIVATES AND DELETES THE PLUGIN. IT SIMPLY DELETES
// THE PLUGIN OPTIONS DB ENTRY (WHICH IS AN ARRAY STORING ALL THE PLUGIN OPTIONS).
// --------------------------------------------------------------------------------------

// Delete options table entries ONLY when plugin deactivated AND deleted
function lpg_impressjs_delete_plugin_options() {
	delete_option('lpg_impressjs_options');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_activation_hook(__FILE__, 'lpg_impressjs_add_defaults')
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE PLUGIN IS ACTIVATED. IF THERE ARE NO THEME OPTIONS
// CURRENTLY SET, OR THE USER HAS SELECTED THE CHECKBOX TO RESET OPTIONS TO THEIR
// DEFAULTS THEN THE OPTIONS ARE SET/RESET.
//
// OTHERWISE, THE PLUGIN OPTIONS REMAIN UNCHANGED.
// ------------------------------------------------------------------------------

// Define default option settings
function lpg_impressjs_add_defaults() {
	$tmp = get_option('lpg_impressjs_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('lpg_impressjs_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(	"textarea_one" =>"http://fonts.googleapis.com/css?family=Open+Sans:regular,semibold,italic,italicsemibold|PT+Sans:400,700,400italic,700italic|PT+Serif:400,700,400italic,700italic",
						"textarea_two" => file_get_contents (plugins_url( 'css/impress-demo.css' , __FILE__ )),
						"textarea_three" =>"impress.js | presentation tool based on the power of CSS3 transforms and transitions in modern browsers | by Bartek Szopka @bartaz",
						"textarea_four" =>"impress.js is a presentation tool based on the power of CSS3 transforms and transitions in modern browsers and inspired by the idea behind prezi.com.",
						"textarea_five" =>"Bartek Szopka",
						"chk_default_options_db" => "",
						"chk_default_slides_db" => ""
		);
		update_option('lpg_impressjs_options', $arr);
	};
	if(($tmp['chk_default_slides_db']=='1')||(!is_array($tmp))) {
		insert_impressjs_post_test();
	};
}

function lpg_impressjs_deactivate() {
	//flush_rewrite_rules();
}


function insert_impressjs_post_test() {
	// delete all existing posts
	
	$args = array(
		'numberposts' => 50,
		'post_type' =>'impressjsstep'
	);
	$posts = get_posts( $args );
	if (is_array($posts)) {
	   foreach ($posts as $post) {
	// what you want to do;
		   wp_delete_post( $post->ID, false);
		   //echo "Deleted Post: ".$post->title."\r\n";
	   }
	}
	
	$my_post_a1 = array(
		'post_title' => "Aren't you just bored with...",
		'post_content' => "<q>Aren't you just <b>bored</b> with all those slides-based presentations?</q>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost1 = wp_insert_post($my_post_a1);
	add_post_meta($mypost1, 'impressjs_sequencenumber', 10);
	add_post_meta($mypost1, 'impressjs_datax', -1000);
	add_post_meta($mypost1, 'impressjs_datay', -1500);
	add_post_meta($mypost1, 'impressjs_id', "bored");
	add_post_meta($mypost1, 'impressjs_stepsel', "step slide");
	
	$my_post_a2 = array(
		'post_title' => "...Don't you think that presentations...",
		'post_content' => "<q>Don't you think that presentations given <strong>in modern browsers</strong> shouldn't <strong>copy the limits</strong> of 'classic' slide decks?</q>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost2 = wp_insert_post($my_post_a2);
	add_post_meta($mypost2, 'impressjs_sequencenumber', 20);
	add_post_meta($mypost2, 'impressjs_datax', 0);
	add_post_meta($mypost2, 'impressjs_datay', -1500);
	add_post_meta($mypost2, 'impressjs_stepsel', "step slide");
	
	$my_post_a3 = array(
		'post_title' => "...Would you like to impress your audience...",
		'post_content' => "<q>Would you like to <strong>impress your audience</strong> with <strong>stunning visualization</strong> of your talk?</q>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost3 = wp_insert_post($my_post_a3);
	add_post_meta($mypost3, 'impressjs_sequencenumber', 30);
	add_post_meta($mypost3, 'impressjs_datax', 1000);
	add_post_meta($mypost3, 'impressjs_datay', -1500);
	add_post_meta($mypost3, 'impressjs_stepsel', "step slide");
	
	$my_post_a4 = array(
		'post_title' => "...then you should try impress.js...",
		'post_content' => "<span class='try'>then you should try</span><h1>impress.js<sup>*</sup></h1><span class='footnote'><sup>*</sup> no rhyme intended</span>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost4 = wp_insert_post($my_post_a4);
	add_post_meta($mypost4, 'impressjs_sequencenumber', 40);
	add_post_meta($mypost4, 'impressjs_datax', '0');
	add_post_meta($mypost4, 'impressjs_datay', '0');
	add_post_meta($mypost4, 'impressjs_id', "title");
	add_post_meta($mypost4, 'impressjs_datascale', 4);
	add_post_meta($mypost4, 'impressjs_stepsel', "step");
	
	$my_post_a5 = array(
		'post_title' => "...It's a presentation tool inspired by the idea behind prezi.com...",
		'post_content' => "<p>It's a <strong>presentation tool</strong> <br/>inspired by the idea behind <a href='http://prezi.com'>prezi.com</a> <br/>and based on the <strong>power of CSS3 transforms and transitions</strong> in modern browsers.</p>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost5 = wp_insert_post($my_post_a5);
	add_post_meta($mypost5, 'impressjs_sequencenumber', 50);
	add_post_meta($mypost5, 'impressjs_datax', 850);
	add_post_meta($mypost5, 'impressjs_datay', 3000);
	add_post_meta($mypost5, 'impressjs_id', "its");
	add_post_meta($mypost5, 'impressjs_datarotate', 90);
	add_post_meta($mypost5, 'impressjs_datascale', 5);
	add_post_meta($mypost5, 'impressjs_stepsel', "step");
	
	$my_post_a6 = array(
		'post_title' => "...visualize your big thoughts...",
		'post_content' => "<p>visualize your <b>big</b> <span class='thoughts'>thoughts</span></p>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost6 = wp_insert_post($my_post_a6);
	add_post_meta($mypost6, 'impressjs_sequencenumber', 60);
	add_post_meta($mypost6, 'impressjs_datax', 3500);
	add_post_meta($mypost6, 'impressjs_datay', 2100);
	add_post_meta($mypost6, 'impressjs_id', "big");
	add_post_meta($mypost6, 'impressjs_datarotate', 180);
	add_post_meta($mypost6, 'impressjs_datascale', 6);
	add_post_meta($mypost6, 'impressjs_stepsel', "step");
	
	$my_post_a7 = array(
		'post_title' => "...and tiny ideas...",
		'post_content' => "<p>and <b>tiny</b> ideas</p>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost7 = wp_insert_post($my_post_a7);
	add_post_meta($mypost7, 'impressjs_sequencenumber', 70);
	add_post_meta($mypost7, 'impressjs_datax', 2825);
	add_post_meta($mypost7, 'impressjs_datay', 2325);
	add_post_meta($mypost7, 'impressjs_dataz', -3000);
	add_post_meta($mypost7, 'impressjs_id', "tiny");
	add_post_meta($mypost7, 'impressjs_datarotate', 300);
	add_post_meta($mypost7, 'impressjs_datascale', 1);
	add_post_meta($mypost7, 'impressjs_stepsel', "step");
	
	$my_post_a8 = array(
		'post_title' => "...by positioning, rotating and scaling...",
		'post_content' => "<p>by <b class='positioning'>positioning</b>, <b class='rotating'>rotating</b> and <b class='scaling'>scaling</b> them on an infinite canvas</p>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost8 = wp_insert_post($my_post_a8);
	add_post_meta($mypost8, 'impressjs_sequencenumber', 80);
	add_post_meta($mypost8, 'impressjs_datax', 3500);
	add_post_meta($mypost8, 'impressjs_datay', -850);
	add_post_meta($mypost8, 'impressjs_id', "ing");
	add_post_meta($mypost8, 'impressjs_datarotate', 270);
	add_post_meta($mypost8, 'impressjs_datascale', 6);
	add_post_meta($mypost8, 'impressjs_stepsel', "step");
	
	$my_post_a9 = array(
		'post_title' => "...the only limit is your imagination...",
		'post_content' => "<p>the only <b>limit</b> is your <b class='imagination'>imagination</b></p>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost9 = wp_insert_post($my_post_a9);
	add_post_meta($mypost9, 'impressjs_sequencenumber', 90);
	add_post_meta($mypost9, 'impressjs_datax', 6700);
	add_post_meta($mypost9, 'impressjs_datay', -300);
	add_post_meta($mypost9, 'impressjs_id', "imagination");
	add_post_meta($mypost9, 'impressjs_datascale', 6);
	add_post_meta($mypost9, 'impressjs_stepsel', "step");
	
	$my_post_a10 = array(
		'post_title' => "...want to know more?...",
		'post_content' => "<p>want to know more?</p><q><a href='http://github.com/bartaz/impress.js'>use the source</a>, Luke!</q>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost10 = wp_insert_post($my_post_a10);
	add_post_meta($mypost10, 'impressjs_sequencenumber', 100);
	add_post_meta($mypost10, 'impressjs_datax', 6300);
	add_post_meta($mypost10, 'impressjs_datay', 2000);
	add_post_meta($mypost10, 'impressjs_datarotate', 20);
	add_post_meta($mypost10, 'impressjs_id', "source");
	add_post_meta($mypost10, 'impressjs_datascale', 4);
	add_post_meta($mypost10, 'impressjs_stepsel', "step");
	
	$my_post_a11 = array(
		'post_title' => "...one more thing...",
		'post_content' => "<p>one more thing...</p>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost11 = wp_insert_post($my_post_a11);
	add_post_meta($mypost11, 'impressjs_sequencenumber', 110);
	add_post_meta($mypost11, 'impressjs_datax', 6000);
	add_post_meta($mypost11, 'impressjs_datay', 4000);
	add_post_meta($mypost11, 'impressjs_id', "one-more-thing");
	add_post_meta($mypost11, 'impressjs_datascale', 2);
	add_post_meta($mypost11, 'impressjs_stepsel', "step");
	
	$my_post_a12 = array(
		'post_title' => "...have you noticed...",
		'post_content' => "<p><span class='have'>have</span> <span class='you'>you</span> <span class='noticed'>noticed</span> <span class='its'>it's</span> <span class='in'>in</span> <b>3D<sup>*</sup></b>?</p><span class='footnote'>* beat that, prezi ;)</span>",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost12 = wp_insert_post($my_post_a12);
	add_post_meta($mypost12, 'impressjs_sequencenumber', 120);
	add_post_meta($mypost12, 'impressjs_datax', 6200);
	add_post_meta($mypost12, 'impressjs_datay', 4300);
	add_post_meta($mypost12, 'impressjs_dataz', -100);
	add_post_meta($mypost12, 'impressjs_datarotatex', -40);
	add_post_meta($mypost12, 'impressjs_datarotatey', 10);
	add_post_meta($mypost12, 'impressjs_id', "its-in-3d");
	add_post_meta($mypost12, 'impressjs_datascale', 2);
	add_post_meta($mypost12, 'impressjs_stepsel', "step");
	
	$my_post_a13 = array(
		'post_title' => "...",
		'post_content' => "",
		'post_status' => 'draft',
		'post_type' => 'impressjsstep'
	);
	$mypost13 = wp_insert_post($my_post_a13);
	add_post_meta($mypost13, 'impressjs_sequencenumber', 130);
	add_post_meta($mypost13, 'impressjs_datax', 3000);
	add_post_meta($mypost13, 'impressjs_datay', 1500);
	add_post_meta($mypost13, 'impressjs_datascale', 10);
	add_post_meta($mypost13, 'impressjs_id', "overview");
	add_post_meta($mypost13, 'impressjs_stepsel', "step");
	
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_init', 'lpg_impressjs_init' )
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_init' HOOK FIRES, AND REGISTERS YOUR PLUGIN
// SETTING WITH THE WORDPRESS SETTINGS API. YOU WON'T BE ABLE TO USE THE SETTINGS
// API UNTIL YOU DO.
// ------------------------------------------------------------------------------

// Init plugin options to white list our options
function lpg_impressjs_init(){
	register_setting( 'lpg_impressjs_plugin_options', 'lpg_impressjs_options', 'lpg_impressjs_validate_options' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_menu', 'lpg_impressjs_add_options_page');
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_menu' HOOK FIRES, AND ADDS A NEW OPTIONS
// PAGE FOR YOUR PLUGIN TO THE SETTINGS MENU.
// ------------------------------------------------------------------------------

// Add menu page
function lpg_impressjs_add_options_page() {
	add_options_page('3D Presentation Options Page', '3D Presentation', 'manage_options', __FILE__, 'lpg_impressjs_render_form');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION SPECIFIED IN: add_options_page()
// ------------------------------------------------------------------------------
// THIS FUNCTION IS SPECIFIED IN add_options_page() AS THE CALLBACK FUNCTION THAT
// ACTUALLY RENDER THE PLUGIN OPTIONS FORM AS A SUB-MENU UNDER THE EXISTING
// SETTINGS ADMIN MENU.
// ------------------------------------------------------------------------------

// Render the Plugin options form
function lpg_impressjs_render_form() {
	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>3D Presentation Options Page</h2>
		<p>Please use the configuration parameters below to set up 3D Presentation options.</p>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('lpg_impressjs_plugin_options'); ?>
			<?php $options = get_option('lpg_impressjs_options'); ?>

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">
			
				<!-- Text Area Control -->
				<tr>
					<th scope="row">Linked stylesheet url</th>
					<td>
						<textarea name="lpg_impressjs_options[textarea_one]" rows="3" cols="120" type='textarea'><?php echo $options['textarea_one']; ?></textarea><br /><span style="color:#666666;margin-left:2px;">The url of a linked stylesheet (leave empty if none)</span>
					</td>
				</tr>

				<!-- Text Area Control -->
				<tr>
					<th scope="row">CSS additional styles</th>
					<td>
						<textarea name="lpg_impressjs_options[textarea_two]" rows="15" cols="120" type='textarea'><?php echo $options['textarea_two']; ?></textarea><br /><span style="color:#666666;margin-left:2px;">CSS additional styles can be put here</span>
					</td>
				</tr>
				
				<!-- Text Area Control -->
				<tr>
					<th scope="row">Presentation Title</th>
					<td>
						<textarea name="lpg_impressjs_options[textarea_three]" rows="2" cols="120" type='textarea'><?php echo $options['textarea_three']; ?></textarea><br /><span style="color:#666666;margin-left:2px;">Presentation title can be put here</span>
					</td>
				</tr>
				
				<!-- Text Area Control -->
				<tr>
					<th scope="row">Presentation meta description</th>
					<td>
						<textarea name="lpg_impressjs_options[textarea_four]" rows="3" cols="120" type='textarea'><?php echo $options['textarea_four']; ?></textarea><br /><span style="color:#666666;margin-left:2px;">Presentation meta description can be put here</span>
					</td>
				</tr>
				
				<!-- Text Area Control -->
				<tr>
					<th scope="row">Presentation author</th>
					<td>
						<textarea name="lpg_impressjs_options[textarea_five]" rows="1" cols="80" type='textarea'><?php echo $options['textarea_five']; ?></textarea><br /><span style="color:#666666;margin-left:2px;">Presentation author can be put here</span>
					</td>
				</tr>

				
				<!-- Textbox Control -->
				<tr>
					<th scope="row">Presentation link</th>
					<td>
						<span><?php echo site_url() . "/index.php?3dpresentation=true"; ?></span>
					</td>
				</tr>

				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Restore Settings</th>
					<td>
						<label><input name="lpg_impressjs_options[chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_db'])) { checked('1', $options['chk_default_options_db']); } ?> /> Restore defaults settings upon plugin deactivation/reactivation</label>
						<br /><span style="color:#666666;margin-left:2px;">Only check this if you want to reset plugin settings upon Plugin reactivation</span>
					</td>
				</tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Restore Default Slides</th>
					<td>
						<label><input name="lpg_impressjs_options[chk_default_slides_db]" type="checkbox" value="1" <?php if (isset($options['chk_default_slides_db'])) { checked('1', $options['chk_default_slides_db']); } ?> /> Restore default slides upon plugin deactivation/reactivation</label>
						<br /><span style="color:#666666;margin-left:2px;">Only check this if you want to reset to the default demo slides upon Plugin reactivation</span>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function lpg_impressjs_validate_options($input) {
	 // strip html from textboxes
	$input['textarea_one'] =  wp_filter_nohtml_kses($input['textarea_one']); // Sanitize textarea input (strip html tags, and escape characters)
	$input['textarea_two'] =  wp_filter_nohtml_kses($input['textarea_two']); // Sanitize textarea input (strip html tags, and escape characters)
	$input['textarea_three'] =  wp_filter_nohtml_kses($input['textarea_three']); // Sanitize textarea input (strip html tags, and escape characters)
	$input['textarea_four'] =  wp_filter_nohtml_kses($input['textarea_four']); // Sanitize textarea input (strip html tags, and escape characters)
	$input['textarea_five'] =  wp_filter_nohtml_kses($input['textarea_five']); // Sanitize textarea input (strip html tags, and escape characters)
	return $input;
}

// Display a Settings link on the main Plugins page
function lpg_impressjs_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$lpg_impressjs_links = '<a href="'.get_admin_url().'options-general.php?page=3d-presentation/impressjs-step.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $lpg_impressjs_links );
	}

	return $links;
}

add_filter( 'manage_edit-impressjsstep_columns', 'my_edit_impressjsstep_columns' ) ;

function my_edit_impressjsstep_columns ( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'impressjs_sequencenumber' => __( 'Sequence Number' ),
		'title' => __( 'Step Title' ),
		'impressjs_id' => __( 'id' ),
		'impressjs_datax' => __( 'x' ),
		'impressjs_datay' => __( 'y' ),
		'impressjs_dataz' => __( 'z' ),
		'impressjs_datascale' => __( 'scale' )
	);

	return $columns;
}


add_action( 'manage_impressjsstep_posts_custom_column', 'my_manage_impressjsstep_columns', 10, 2 );

function my_manage_impressjsstep_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'sequencenumber' column. */
		case 'impressjs_sequencenumber' :

			/* Get the post meta. */
			$sequencenumber = get_post_meta( $post->ID, 'impressjs_sequencenumber', true );

			/* If no sequencenumber is found, output a default message. */
			if ( empty( $sequencenumber ) )
				echo __('Unknown');

			/* If there is a duration, append 'minutes' to the text string. */
			else
				//printf( __( '' ), $sequencenumber );
				echo __($sequencenumber);

		break;
		
		case 'impressjs_id' :
			$idd = get_post_meta( $post->ID, 'impressjs_id', true );
			if ( empty( $idd ) )
				echo __('');
			else
				echo __($idd);
		break;
		
		case 'impressjs_datax' :
			$cx = get_post_meta( $post->ID, 'impressjs_datax', true );
			if ( empty( $cx ) )
				echo __('');
			else
				echo __($cx);
		break;
		
		case 'impressjs_datay' :
			$cy = get_post_meta( $post->ID, 'impressjs_datay', true );
			if ( empty( $cy ) )
				echo __('');
			else
				echo __($cy);
		break;
		
		case 'impressjs_dataz' :
			$cz = get_post_meta( $post->ID, 'impressjs_dataz', true );
			if ( empty( $cz ) )
				echo __('');
			else
				echo __($cz);
		break;
		
		case 'impressjs_datascale' :
			$cscale = get_post_meta( $post->ID, 'impressjs_datascale', true );
			if ( empty( $cscale ) )
				echo __('');
			else
				echo __($cscale);
		break;
		

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}



add_filter( 'manage_edit-impressjsstep_sortable_columns', 'my_impressjsstep_sortable_columns' );

function my_impressjsstep_sortable_columns( $columns ) {

	$columns['impressjs_sequencenumber'] = 'impressjs_sequencenumber';

	return $columns;
}

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'my_edit_impressjsstep_load' );

function my_edit_impressjsstep_load() {
	add_filter( 'request', 'my_sort_impressjssteps' );
}

/* Sorts the movies. */
function my_sort_impressjssteps( $vars ) {

	/* Check if we're viewing the 'impressjsstep' post type. */
	if ( isset( $vars['post_type'] ) && 'impressjsstep' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'sequencenumber'. */
		//if ( isset( $vars['orderby'] ) && 'impressjs_sequencenumber' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'impressjs_sequencenumber',
					'orderby' => 'meta_value_num',
					'order' => 'ASC'
				)
			);
		//}
	}

	return $vars;
}

/*

add_action("template_redirect", 'my_theme_redirect');

function my_theme_redirect() {
    global $wp;
    $plugindir = dirname( __FILE__ );

    //A Specific Custom Post Type
    if ($wp->query_vars["post_type"] == 'impressjsslide') {
        $templatefilename = 'single-impressjsstep.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/' . $templatefilename;
        }
        do_theme_redirect($return_template);
	}
}

function do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}

*/

function get_step_post_type_template($single_template) {
 global $post;

 if ($post->post_type == 'impressjsstep') {
      $single_template = dirname( __FILE__ ) . '/single-impressjsstep.php';
 }
 return $single_template;
}

add_filter( "single_template", "get_step_post_type_template" ) ;




add_action('admin_enqueue_scripts', 'add_my_js');
   
function add_my_js(){ 
   
  wp_enqueue_script('my_validate', plugins_url( '/js/jquery.validate.js' , __FILE__ ), array('jquery'));
  wp_enqueue_script('my_script_js', plugins_url( '/js/myvalidate.js'));
  
}


?>