<?php
/*
Plugin Name: Show Hide Author
Description: Choose whether to show or hide the author name.
Version: 2.2
Author: Michael Spyratos
Author URI: http://mspyratos.com/
License: GPL2
*/

/*  Copyright 2013 Michael Spyratos (email : m.spyratos@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * 
 * FUNCTIONS
 * 
 */
 

/**
 * Creates default settings values uppon plugin activation
 */
function show_author_after_activation() {
	global $wpdb;
	$db_prefix = $wpdb->prefix;
	$query = $wpdb->prepare("SELECT * FROM " . $db_prefix . "options WHERE `option_name`=%s", 'show_author_options');
	$db_result = $wpdb->get_row($query);
	if(is_null($db_result) || $db_result == "" || !isset($db_result)) {
		$query = $wpdb->prepare("INSERT INTO " . $db_prefix . "options SET
				option_name = %s,
				option_value = %s,
				autoload = %s
		",
				'show_author_options',
				'a:6:{s:11:"option_post";s:4:"post";s:11:"option_page";s:4:"page";s:15:"option_url_show";s:0:"";s:15:"option_url_hide";s:0:"";s:15:"option_by_class";s:0:"";s:15:"option_by_regex";s:0:"";}',
				'yes'
		);
		$wpdb->query($query);
	}
}

/**
 * Deletes options from the database uppon plugin uninstall
 */
function show_author_after_uninstall() {
	global $wpdb;
	$db_prefix = $wpdb->prefix;
	$query = $wpdb->prepare("DELETE FROM " . $db_prefix . "options WHERE `option_name`='show_author_options'");
	$db_result = $wpdb->query($query);
}

/**
 * Creates the settings page
 */
function adminPanel() {	
	?>
	
		<?php if( isset($_GET['settings-updated']) ) { ?>
			<div id="message" class="updated">
				<p><strong><?php _e('Settings saved.'); ?></strong></p>
			</div>			
		<?php } ?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Show Hide Author - Settings</h2>
			<p>On this page you configure where and if to show the aurhor name/link.</p>
			<form method="post" action="options.php" id="show_author_form">
				<?php settings_fields('admin_panel_group') ?>
				<?php $options = get_option('show_author_options'); ?>
				<fieldset>
					<h2>Show author name in:</h2>
					<p> </p>
					<h3>Post Types</h3>
					<?php
						$all_post_type_names = get_post_types();
						if(isset($all_post_type_names)) : ?>
							<?php foreach($all_post_type_names as $post_type_name) : ?>
								<div>						
									<input type="checkbox" id="show_author_<?php echo $post_type_name; ?>" name="show_author_options[option_<?php echo $post_type_name; ?>]"
										value="<?php echo $post_type_name;?>" <?php if(isset($options['option_' . $post_type_name])) checked($post_type_name, $options['option_' . $post_type_name]); ?>>
									<label for="show_author_options[option_<?php echo $post_type_name; ?>]"><?php echo ucfirst($post_type_name) . 's'; ?></label>
								</div>
							<?php endforeach; ?>
					<?php endif; ?>
					<p> </p>
					<h3>Individual Pages</h3>
					<p>Choose to show the author name in individual pages, from inside the editor while you compose a new post.</p>
					<div>
						<input type="checkbox" id="show_author_individual" name="show_author_options[option_individual]"
							value="individual" <?php if(isset($options['option_individual'])) checked('individual', $options['option_individual']); ?>>
						<label for="show_author_options[option_individual]">Individual Pages **</label>
					</div>
					<p> </p>
					<h3>Custom URLs to Show Author Name</h3>
					<div>
						<p><label for="show_author_options[option_url_show]">Enter Custom Urls in seperate lines</label></p>
						<textarea id="show_author_url_show" name="show_author_options[option_url_show]" rows="10" cols="50"><?php
						if(isset($options['option_url_show']) && $options['option_url_show'] != '') echo $options['option_url_show']; ?></textarea>
					</div>
					<h3>Custom URLs to Hide Author Name</h3>
					<div>
						<p><label for="show_author_options[option_url_hide]">Enter Custom Urls in seperate lines</label></p>
						<textarea id="show_author_url_hide" name="show_author_options[option_url_hide]" rows="10" cols="50"><?php
						if(isset($options['option_url_hide']) && $options['option_url_hide'] != '') echo $options['option_url_hide']; ?></textarea>
						<p>
							<i>
								For example:<br />
								http://mydomain.com/<br />
								http://mydomain.com/category
							</i>
						</p>
					</div>
					<h3>Advanced: Hide the "by" word</h3>
					<p>If when removed the author name you're left with a "by" word, remove it by adding the class of the its parent element and/or a regex.</p>
					<div>
						<p><label for="show_author_options[option_by_class]">The parent classes<br /><i><?php echo 'e.g. entry-meta;author-meta'; ?></i></label></p>
						<input type="text" id="show_author_by_class" name="show_author_options[option_by_class]" value="<?php
						if(isset($options['option_by_class']) && $options['option_by_class'] != '') echo htmlspecialchars ($options['option_by_class']); ?>">
					</div>
					<div>
						<p><label for="show_author_options[option_by_regex]">The regular expressions<br /><i><?php echo htmlspecialchars ('e.g. by <a(.*?)/a>;<span class="sep"> by </span>'); ?></i></label></p>
						<input type="text" id="show_author_by_regex" name="show_author_options[option_by_regex]" value="<?php
						if(isset($options['option_by_regex']) && $options['option_by_regex'] != '') echo htmlspecialchars ($options['option_by_regex']); ?>">
					</div>
					<p>
						<i>Use multiple values by seperating them with a semicolon (;).<br />
						The Default regex value is: <?php echo htmlspecialchars ('by <a(.*?)/a>'); ?>, which means select the word "by", followed by a link "a" tag.</i>
					</p>
				</fieldset>
				<p><input name="Submit" type="submit" class="button button-primary button-large" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
				<p> </p>
				<p><i><u>Notes</u>:<br />
				If nothing submitted, author name will be hidden everywhere.<br />
				If a post type (from above) doesn't support the display of author name, then even if you check it, it will not show up.<br />
				</i></p>
			</form>
		</div> <!-- End of "wrap" -->
	<?php
}


/**
 * Displays the settings page
 */
function adminPanelPage() {
	add_submenu_page('plugins.php', 'Show or hide the author name', 'Show/Hide Author', 'edit_plugins', 'show_hide_author', 'adminPanel');
}

/**
 * Registers the settings to the ini
 */
function show_hide_author_init() {
	register_setting( 'admin_panel_group', 'show_author_options');
}

/**
 * Adds a meta-box in every post type
 * This way you can choose to show author's name in individual pages
 */
function show_author_metabox($post) {
	$show_author_inthis_post = get_post_meta( $post->ID, '_show_author_inthis_post', true );
	?>
		<p>Select whether to display or not the author's name, independently of the settings in <i>Show Hide Author</i> plugin.</p>
		<div>
			<input type="radio" id="show_author_inthis_post" name="show_author_inthis_post" value="author_default"
					<?php if($show_author_inthis_post != 'inthis_post' && $show_author_inthis_post != 'not_inthis_post') {echo 'checked="checked"';} ?>>
			<label>Use Settings in <i>Show Hide Author</i> plugin</label>
		</div>
		<div>
			<input type="radio" id="show_author_inthis_post" name="show_author_inthis_post" value="inthis_post" <?php checked($show_author_inthis_post, 'inthis_post'); ?>>
			<label>Show Author Name</label>
		</div>
		<div>
			<input type="radio" id="hide_author_inthis_post" name="show_author_inthis_post" value="not_inthis_post" <?php checked($show_author_inthis_post, 'not_inthis_post'); ?>>
			<label>Hide Author Name</label>
		</div>
	<?php
}

/**
 * Creates the metaboxes calls
 */
function add_author_metabox() {
	$all_post_type_names = get_post_types();
	if(isset($all_post_type_names)) {
		foreach($all_post_type_names as $post_type_name) {
			add_meta_box( 'show_author_metabox', 'Show Hide Author Name', 'show_author_metabox', $post_type_name, 'normal' );
		}
	}
}

/**
 * Saves/updates the meta-box settings
 */
function show_author_metabox_save($post_id) {
	if ( isset( $_POST['show_author_inthis_post'] ) ) {
		update_post_meta( $post_id, '_show_author_inthis_post', strip_tags( $_POST['show_author_inthis_post'] ) );
	}
}

/**
 * Removes the author name.
 * Also overrides the core function in wp themes
 */
function remove_author() {
	// Remove the "by" word with hooks
	remove_the_by_hooks();
}

/**
 * Gets the current - full url
 * Source: http://webcheatsheet.com/php/get_current_page_url.php
 */
function current_full_url() {
	$pageURL = 'http';
	if (isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] )) {$pageURL .= "s";}
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

/**
 * Checks whether to remove or not the author name
 */
function show_hide_author() {
	$url = current_full_url();
	// Get post id
	global $post;
	$ID = $post->ID;
	// Get and prepare current url
	$replace = array('/http:\/\/www./', '/https:\/\/www./', '/www./', '/\n\r/');
	$with = array('', '', '', '\n');
	$current_url_nohttp = preg_replace($replace, $with, $url);
	$options_array = get_option('show_author_options');
	// Get and prepare Show url
	$option_urls_show_nohttp = preg_replace($replace, $with, $options_array['option_url_show']);
	$urls_show_array = explode("\n", $option_urls_show_nohttp);
	// Get and prepare Hide url
	$option_urls_hide_nohttp = preg_replace($replace, $with, $options_array['option_url_hide']);
	$urls_hide_array = explode("\n", $option_urls_hide_nohttp);
	// If nothing submitted, hide the name everywhere
	if(!is_array($options_array)) {
		remove_author();
	}
	// If current url in Show urls
	else if(in_array($current_url_nohttp, $urls_show_array)) {
		// Show Author
	}
	// If current url in Hide urls
	else if(in_array($current_url_nohttp, $urls_hide_array)) {
		remove_author();
	}
	// Post Types
	else if(in_array('individual', $options_array) && get_post_meta( $ID, '_show_author_inthis_post', true ) == 'inthis_post') {
		// Show Author
	} else if(in_array('individual', $options_array) && get_post_meta( $ID, '_show_author_inthis_post', true ) == 'not_inthis_post') {
		remove_author();
	} else if(empty($options_array) || !in_array(get_post_type($ID), $options_array)) {
		remove_author();
	} else {
		// Show Author
	}
}

/**
 * Removes the author name
 */
function remove_author_name_filter() {
	remove_filter('the_author', 'the_author');
}

/**
 * Removes the author link
 */
function remove_author_link_filter() {
	remove_filter('author_link', 'get_author_posts_url');
}

/**
 * Removes the html remains, such as the word by, <a> tags, etc..
 * Uses javascript
 */
function remove_the_by() {	
	$options_by = get_option('show_author_options');
	// Get the classes submittes
	$custom_classes = '';
	if(isset($options_by['option_by_class']) && $options_by['option_by_class'] != '') {
		$custom_classes_array = explode(';', $options_by['option_by_class']);
	} else {
		$custom_classes_array = array('');
	}
	foreach($custom_classes_array as $custom_class) {
		$custom_classes .= "'" . $custom_class . "', ";
	}
	$custom_classes = substr($custom_classes, 0, -2);
	// Get the regex submitted
	if(isset($options_by['option_by_regex']) && $options_by['option_by_regex'] != '') {
		$custom_regex_string = preg_replace('/\//', '\/', $options_by['option_by_regex']);
	} else {
		$custom_regex_string = '';
	}
	
	//$custom_regexs = substr($custom_classes, 0, -2);
	?>
	<script>
	<?php if(isset($options_by['option_by_class']) && $options_by['option_by_class'] != '') : ?>
		// Custom Classes and Regexs
		var classes = [<?php echo $custom_classes; ?>];
		<?php if(isset($custom_regex_string) && $custom_regex_string != '') : ?>
		var regex = '<?php echo $custom_regex_string; ?>';
		<?php else : ?>
		var regex = ' by <a(.*?)\/a>;<span class="sep"> by <\/span>';
		<?php endif; ?>
	<?php else : ?>
		// List of Themes, Classes and Regular expressions
		var template_name = '<?php echo get_template(); ?>';
		switch(template_name) {
			case 'twentyeleven' :
					var classes = ['entry-meta'];
					var regex = ' by <a(.*?)\/a>;<span class="sep"> by <\/span>';
					break;
			case 'twentyten' :
					var classes = ['entry-meta'];
					var regex = ' by <a(.*?)\/a>;<span class="meta-sep">by<\/span>';
					break;
			case 'object' :
					var classes = ['post-details', 'post'];
					var regex = ' by <span(.*?)\/span>;<p class="post-details">by';
					break;
			case 'theme-revenge' :
					var classes = ['post-entry-meta'];
					var regex = 'By <a(.*?)\/a> on';
					break;
			case 'Magnificent' :
					var classes = ['meta-info'];
					var regex = '<span>by<\/span> <a(.*?)\/a>;Posted by <a(.*?)\/a> on ';
					break;
			case 'wp-clearphoto' :
					var classes = ['meta'];
					var regex = '\\|\\s*<\/span>\\s*<span class="meta-author">\\s*<a(.*?)\/a>';
					break;
			case 'wp-clearphoto1' :
					var classes = ['meta'];
					var regex = '\\|\\s*<\/span>\\s*<span class="meta-author">\\s*<a(.*?)\/a>';
					break;
			default:
					var classes = ['entry-meta'];
					var regex = ' by <a(.*?)\/a>;<span class="sep"> by <\/span>; <span class="meta-sep">by<\/span>;<span class="meta-sep"> by <\/span>';
		}
	<?php endif; ?>
			if (typeof classes[0] !== 'undefined' && classes[0] !== null) {
				for(var i = 0; i < classes.length; i++) {
					var elements = document.querySelectorAll('.'+classes[i]);
					for (var j = 0; j < elements.length; j++) {
						var regex_array = regex.split(";");
						for(var k = 0; k < regex_array.length; k++) {
							 if (elements[j].innerHTML.match(new RegExp(regex_array[k], "i"))) {
								 var replaced_txt = elements[j].innerHTML.replace(new RegExp(regex_array[k], "i"), '');
								 elements[j].innerHTML = replaced_txt;
							 }
						}
					}			
				}
			}
						
		</script>
	<?php
}

/**
 * Hooks the functions to remove the author
 */
function remove_the_by_hooks() {
	add_filter('the_author', 'remove_author_name_filter');
	add_filter('author_link', 'remove_author_link_filter');
	add_action('wp_footer', 'remove_the_by');
}


/**
 * 
 * HOOKS
 * 
 */

// After plugin activation, create default settings values
register_activation_hook( __FILE__, 'show_author_after_activation' );

// When uninstall, delete plugin's options from the database
register_uninstall_hook( __FILE__, 'show_author_after_uninstall' );

// Add submenu to the plugin menu that links to the plugin's settings page
add_action('admin_menu', 'adminPanelPage');

// Register the settings to the ini
add_action('admin_init', 'show_hide_author_init');

// Adds the metabox
// ! backwards compatible (before WP 3.0)
// add_action( 'admin_init', 'add_author_metabox', 1 );
add_action( 'add_meta_boxes', 'add_author_metabox' );

// Save the meta box settings
add_action( 'save_post', 'show_author_metabox_save' );

// Decides whether to show or hide the author
add_action('wp_head', 'show_hide_author');


