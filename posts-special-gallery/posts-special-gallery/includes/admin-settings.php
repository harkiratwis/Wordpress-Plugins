<?php
add_action('admin_menu', 'add_settings_menu');

function add_settings_menu(){
     add_options_page('Special Gallery Options', 'Special Gallery', 'manage_options', 'special-gallery-settings', 'special_gallery_options');
}


function special_gallery_options() {
	if(isset($_POST)){
		if ( get_option( 'hostname_gallery' ) !== false ) {
			update_option( 'hostname_gallery', $_POST['hostname_gallery'] );

		} else {
			$deprecated = null;
			$autoload = 'no';
			add_option( 'hostname_gallery', $_POST['hostname_gallery'], $deprecated, $autoload );
		}
		if ( get_option( 'ga_gallery' ) !== false ) {
			update_option( 'ga_gallery', $_POST['ga_gallery'] );

		} else {
			$deprecated = null;
			$autoload = 'no';
			add_option( 'ga_gallery', $_POST['ga_gallery'], $deprecated, $autoload );
		}
		if ( get_option( 'js_color' ) !== false ) {
			update_option( 'js_color', $_POST['js_color'] );

		} else {
			$deprecated = null;
			$autoload = 'no';
			add_option( 'js_color', $_POST['js_color'], $deprecated, $autoload );
		}
	}
	?>
	<h2> Page View Settings for GA</h2>
	<form action="" method="post">
	<table>
	<tr>
		<td><label>Hostname:</label></td>
		<td><input type="text" name="hostname_gallery" value="<?php echo get_option( 'hostname_gallery' );?>"></td>
	</tr>
	<tr>
		<td><label>GA Key:</label></td>
		<td><input type="text" name="ga_gallery"  value="<?php echo get_option( 'ga_gallery' );?>"></td>
	</tr>
	<tr>
		<td><label>Button Background Color:</label></td>
		<td><input type="text" name="js_color" class="jscolor"  value="<?php echo get_option( 'js_color' );?>"></td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" value="Save">
		</td>
	</tr>
	</table>
	</form>
	<?php
}

?>