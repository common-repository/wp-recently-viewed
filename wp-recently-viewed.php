<?php
/*

**************************************************************************

Plugin Name:  WP Recently Viewed
Plugin URI:   http://www.arefly.com/wp-recently-viewed/
Description:  Let visitors see there recently view post. 讓訪客查看他們最近訪問過的文章
Version:      1.0
Author:       Arefly
Author URI:   http://www.arefly.com/
Text Domain:  wp-recently-viewed
Domain Path:  /lang/

**************************************************************************

	Copyright 2014  Arefly  (email : eflyjason@gmail.com)

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

**************************************************************************/

define("WP_RECENTLY_VIEWED_PLUGIN_URL", plugin_dir_url( __FILE__ ));
define("WP_RECENTLY_VIEWED_FULL_DIR", plugin_dir_path( __FILE__ ));
define("WP_RECENTLY_VIEWED_TEXT_DOMAIN", "wp-recently-viewed");

/* Plugin Localize */
function wp_recently_viewed_load_plugin_textdomain() {
	load_plugin_textdomain(WP_RECENTLY_VIEWED_TEXT_DOMAIN, false, dirname(plugin_basename( __FILE__ )).'/lang/');
}
add_action('plugins_loaded', 'wp_recently_viewed_load_plugin_textdomain');

function wp_recently_viewed_js() {
	wp_enqueue_script(WP_RECENTLY_VIEWED_TEXT_DOMAIN.'-view-history', WP_RECENTLY_VIEWED_PLUGIN_URL.'js/view-history.js', NULL, NULL, TRUE);
	if(is_single()){
		wp_enqueue_script(WP_RECENTLY_VIEWED_TEXT_DOMAIN.'-add-history', WP_RECENTLY_VIEWED_PLUGIN_URL.'js/add-history.js', NULL, NULL, TRUE);
	}
}
add_action("wp_enqueue_scripts", "wp_recently_viewed_js");

class wp_recently_viewed_widget extends WP_Widget {
	// Controller
	function __construct(){
		$widget_ops = array('classname' => 'wp_recently_viewed_class', 'description' => __("Show what visitor recently viewed on your blog.", WP_RECENTLY_VIEWED_TEXT_DOMAIN));
		$control_ops = array('width' => 400, 'height' => 300);
		parent::WP_Widget(false, $name = __('Visitor Recently Viewed', WP_RECENTLY_VIEWED_TEXT_DOMAIN), $widget_ops, $control_ops);
	}

	// Constructor
	function wp_my_plugin(){
		parent::WP_Widget(false, $name = __('Visitor Recently Viewed', WP_RECENTLY_VIEWED_TEXT_DOMAIN) );
	}

	// Display widget
	function widget($args, $instance){
		extract($args);
		// These are the widget options
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		// Display the widget
		echo '<div class="'.WP_RECENTLY_VIEWED_TEXT_DOMAIN.'" id="recently_viewed">';

		// Check if title is set
		if(empty($title)){
			$title = "Recently Viewed";
		}
		echo $before_title . $title . $after_title;
		echo '</div>';
		echo $after_widget;
	}

	// Update widget
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Fields
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	// Widget form creation
	function form($instance) {
		// Check values
		if($instance) {
			$title = esc_attr($instance['title']);
		} else {
			$title = __('Recently Viewed', WP_RECENTLY_VIEWED_TEXT_DOMAIN);
		}
?>
<p>
<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', WP_RECENTLY_VIEWED_TEXT_DOMAIN); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<?php
	}
}

function wp_recently_viewed_register_widgets(){
	register_widget('wp_recently_viewed_widget');
}
add_action('widgets_init', 'wp_recently_viewed_register_widgets');
