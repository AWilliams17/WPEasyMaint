<?php

/*
Plugin Name: Easy Maintenance Mode
Plugin URI: none
Description: Simple plugin which prevents non-admin users from accessing a site while the setting is checked.
Version: 1.0
Author: f
License: GPL2
*/

function emm_setup_plugin() {
	add_option( 'maintenance_mode_enabled', false, '', 'yes' );
}

function emm_uninstall_plugin(){
	delete_option('maintenance_mode_enabled');

}

function emm_section_text() {
	echo '<p>Enable/Disable Easy Maintenance Mode.</p>';
}

function emm_enable_checkbox() {
	?>
	<input type="checkbox" name="maintenance_mode_enabled" value="1" <?php checked( '1', get_option( 'maintenance_mode_enabled' ) ); ?> />
	<?php
}

function emm_register_settings() {
	register_setting( 'emm_group', 'maintenance_mode_enabled' );

	add_settings_section('emm_settings_section', 'Easy Maintenance Mode', 'emm_section_text', 'maintenance-mode');
	add_settings_field('emm_enable_checkbox_field', 'Maintenance Mode Enabled', 'emm_enable_checkbox', 'maintenance-mode', 'emm_settings_section');
}

function emm_options_page() {
	?>
	<div class="wrap">
		<form method="post" action="options.php">
			<?php settings_fields( 'emm_group' ); ?>
			<?php do_settings_sections( 'maintenance-mode' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

function emm_menu() {
	add_options_page(
		'Maintenance Mode',
		'Maintenance Mode',
		'manage_options',
		'maintenance-mode.php',
		'emm_options_page'
	);};

function wp_mode_maintenance(){
	if ( get_option( 'maintenance_mode_enabled' )){
		if ( !current_user_can('edit_themes' )){
			wp_die("Sorry, under maintenance.");
		}
	}
}

if ( is_admin()) {
	add_action( 'admin_menu', 'emm_menu' );
	add_action( 'admin_init', 'emm_register_settings' );
}

register_activation_hook( __FILE__, 'emm_setup_plugin' );
register_uninstall_hook( __FILE__, 'emm_uninstall_plugin' );
add_action( 'get_header', 'wp_mode_maintenance' );