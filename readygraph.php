<?php
/*
Plugin Name: ReadyGraph
Plugin URI: http://www.readygraph.com/ 
Version: 1.0
Author: ReadyGraph team
Description: ReadyGraph is a simple friend invite tool that drives large number of traffic to your site
Author URI: http://www.readygraph.com/
*/

// plugin updater
include_once('updater.php');

if (is_admin()) {
    $config = array(
        'slug' => plugin_basename(__FILE__),
        'proper_folder_name' => 'readygraph-wordpress',
	'source_folder_name' => 'readygraph-wordpress',
        'api_url' => 'https://api.github.com/repos/baddth/readygraph-wordpress',
        'raw_url' => 'https://raw.github.com/baddth/readygraph-wordpress/master',
        'github_url' => 'https://github.com/baddth/readygraph-wordpress',
        'zip_url' => 'https://github.com/baddth/readygraph-wordpress/zipball/master',
        'sslverify' => true,
        'requires' => '3.0',
        'tested' => '3.5',
        'readme' => 'README.md',
        'access_token' => ''
    );
    new WP_GitHub_Updater($config);
}

// create custom plugin settings menu
add_action('admin_menu', 'rg_create_menu');
add_action('wp_head', 'rg_script_head');

function rg_create_menu() {
	//create new top-level menu
	add_menu_page('ReadyGraph', 'ReadyGraph', 'administrator', __FILE__, 'rg_settings_page',plugins_url('/images/rg_logo_sml.png', __FILE__));
	//call register settings function
	add_action( 'admin_init', 'rg_register_mysettings' );
}

function rg_register_mysettings() {
	//register our settings
	register_setting( 'rg-settings-group', 'rg_application_id' );
	register_setting( 'rg-settings-group', 'rg_autopop' );
}

function rg_settings_page() {
	if (get_option('rg_autopop') === false) {
		$rg_autopop = '1';
	} else {
		$rg_autopop = get_option('rg_autopop');
	}
?>
	<div class="wrap">
		<h2>ReadyGraph Setting</h2>

		<form method="post" action="options.php">
		    <?php settings_fields( 'rg-settings-group' ); ?>
		    <?php
			// do_settings( 'rg-settings-group' );
		    ?>
		    <table class="form-table">
			  <tr valign="top">
				   <td scope="row" colspan="2">
					To learn more about ReadyGraph&trade;, please visit <a href="http://www.readygraph.com" target="_blank">http://www.readygraph.com</a>
				   </td>
			  </tr>
		        <tr valign="top">
			        <th scope="row">ReadyGraph&trade; Application ID</th>
			        <td>
					<input type="text" name="rg_application_id" value="<?php echo get_option('rg_application_id'); ?>" />
				  </td>
			  </tr>
			  <tr valign="top">
				   <td scope="row" colspan="2">
					<hr/>
				   </td>
			  </tr>
		        <tr valign="top">
				  <th scope="row"><a href="#" onclick="document.getElementById('rg_advance_setting').style.display='block';return false">Show Advanced Setting</a></th>
				  <td></td>
		        </tr>
		    </table>
		    <table class="form-table" id="rg_advance_setting" style="display:none;">
			  <tr valign="top">
				   <th scope="row">Prompt user to connect to their social on their first visit</th>
				   <td><input type="checkbox" name="rg_autopop" value="1" <?php checked( true, $rg_autopop ); ?> /></td>
			  </tr>
		    </table>
		    <?php submit_button(); ?>
		</form>
	</div>
</div>
<?php
}

function rg_script_head() {
	$app_id = get_option('rg_application_id');
	if ($app_id === false) {
		return;
	}
	if (get_option('rg_autopop') === false) {
		$rg_autopop = '1';
	} else {
		$rg_autopop = get_option('rg_autopop');
	}
?>
	<script type="text/javascript" src="//www.readygraph.com/scripts/readygraph.js"></script>
	<script type="text/javascript">
		ReadyGraph.setup({applicationId: '<?php echo $app_id; ?>', overrideFacebookSDK: true});
		console.log('<?php echo get_the_title(); ?>');
<?php
	if (((int)$rg_autopop) == 1) {
?>
		ReadyGraph.show(ReadyGraph.Plugins.ReadyInvite, {
		  lazyShowing: true,
		  runOnlyOnce: true,
		}, function(results) {
		  /*callback*/
		});
<?php
	}
?>
	</script>
<?php
}
?>
