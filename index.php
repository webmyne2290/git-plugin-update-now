<?php
/*
PLugin Name: Github Custom Plugin Update
Plugin URI: http://www.webmyne.com/
Description: Github Custom Plugin Update
version: 2.0
author: Webmyne
Author URI: http://www.webmyne.com/
*/

/* Start code at Update Plugin */

add_action( 'init', 'github_plugin_updater_test_init' );

function github_plugin_updater_test_init() {

	include_once 'updater.php';

	define( 'WP_GITHUB_FORCE_UPDATE', true );

	if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin

		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'git-plugin-update-now',
			'api_url' => 'https://api.github.com/repos/ajaywebmyne/custompluginupdate',
			'raw_url' => 'https://raw.github.com/ajaywebmyne/custompluginupdate/master',
			'github_url' => 'https://github.com/ajaywebmyne/custompluginupdate',
			'zip_url' => 'https://github.com/ajaywebmyne/custompluginupdate/archive/master.zip',
			'sslverify' => false,
			'requires' => '3.8.1',
			'tested' => '5.3.2',
			'readme' => 'README.md',
			'access_token' => 'c93e35f3c225c26ab85532d3746442944e78126b',
		);

		//echo "<pre>";print_r($config);exit;

		new WP_GitHub_Updater( $config );

	}

}

/* End code at Update Plugin */

function register_my_custom_menu_page()
{
    add_menu_page( 
        __( 'Custom Menu Plugin', 'textdomain' ),
        'Custom Menu Plugin',
        'manage_options',
        'custompagenew',
        'customdata_new',
        'dashicons-admin-page',
        6
    ); 
}
add_action( 'admin_menu', 'register_my_custom_menu_page' );

function customdata_new()
{
?>
	<div class="wrap">
		<?php    echo "<h2>" . __( 'OSCommerce Product Display Options', 'oscimp_trdom' ) . "</h2>"; ?>
		
		<?php
			$dbhost = get_option('oscimp_dbhost');
			$dbname = get_option('oscimp_dbname');
			$dbuser = get_option('oscimp_dbuser');
			$dbpwd = get_option('oscimp_dbpwd');
			$prod_img_folder = get_option('oscimp_prod_img_folder');
			$store_url = get_option('oscimp_store_url');
			$updateverson="2.0";
		?>
		 
		<form name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="oscimp_hidden" value="Y">
			<?php    echo "<h4>" . __( 'OSCommerce Database Settings', 'oscimp_trdom' ) . "</h4>"; ?>
			<p><?php _e("Database host: " ); ?><input type="text" name="oscimp_dbhost" value="<?php echo $dbhost; ?>" size="20"><?php _e(" ex: localhost" ); ?></p>
			<p><?php _e("Database name: " ); ?><input type="text" name="oscimp_dbname" value="<?php echo $dbname; ?>" size="20"><?php _e(" ex: oscommerce_shop" ); ?></p>
			<p><?php _e("Database user: " ); ?><input type="text" name="oscimp_dbuser" value="<?php echo $dbuser; ?>" size="20"><?php _e(" ex: root" ); ?></p>
			<p><?php _e("Database password: " ); ?><input type="text" name="oscimp_dbpwd" value="<?php echo $dbpwd; ?>" size="20"><?php _e(" ex: secretpassword" ); ?></p>
			<p><?php _e("Update Version: " ); ?><input type="text" name="update_verson" value="<?php echo $updateverson; ?>" size="20"><?php _e(" ex: secretpassword" ); ?></p>
			<hr />
			<?php    echo "<h4>" . __( 'OSCommerce Store Settings', 'oscimp_trdom' ) . "</h4>"; ?>
			<p><?php _e("Store URL: " ); ?><input type="text" name="oscimp_store_url" value="<?php echo $store_url; ?>" size="20"><?php _e(" ex: http://www.yourstore.com/" ); ?></p>
			<p><?php _e("Product image folder: " ); ?><input type="text" name="oscimp_prod_img_folder" value="<?php echo $prod_img_folder; ?>" size="20"><?php _e(" ex: http://www.yourstore.com/images/" ); ?></p>
			 
		 
			<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update Options', 'oscimp_trdom' ) ?>" />
			</p>
		</form>
	</div>
<?php
    if($_POST['oscimp_hidden'] == 'Y')
	{
        //Form data sent
        $dbhost = $_POST['oscimp_dbhost'];
        update_option('oscimp_dbhost', $dbhost);
         
        $dbname = $_POST['oscimp_dbname'];
        update_option('oscimp_dbname', $dbname);
         
        $dbuser = $_POST['oscimp_dbuser'];
        update_option('oscimp_dbuser', $dbuser);
         
        $dbpwd = $_POST['oscimp_dbpwd'];
        update_option('oscimp_dbpwd', $dbpwd);
 
        $prod_img_folder = $_POST['oscimp_prod_img_folder'];
        update_option('oscimp_prod_img_folder', $prod_img_folder);
 
        $store_url = $_POST['oscimp_store_url'];
        update_option('oscimp_store_url', $store_url);
        ?>
        <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
        <?php
    }
	else
	{
        //Normal page display
		$dbhost = get_option('oscimp_dbhost');
        $dbname = get_option('oscimp_dbname');
        $dbuser = get_option('oscimp_dbuser');
        $dbpwd = get_option('oscimp_dbpwd');
        $prod_img_folder = get_option('oscimp_prod_img_folder');
        $store_url = get_option('oscimp_store_url');
    }
	
	function oscimp_getproducts($product_cnt=1)
	{
		//Connect to the OSCommerce database
		$oscommercedb = new wpdb(get_option('oscimp_dbuser'),get_option('oscimp_dbpwd'), get_option('oscimp_dbname'), get_option('oscimp_dbhost'));
	 
		$retval = '';
		for ($i=0; $i<$product_cnt; $i++) {
			//Get a random product
			$product_count = 0;
			while ($product_count == 0) {
				$product_id = rand(0,30);
				$product_count = $oscommercedb->get_var("SELECT COUNT(*) FROM products WHERE products_id=$product_id AND products_status=1");
			}
			 
			//Get product image, name and URL
			$product_image = $oscommercedb->get_var("SELECT products_image FROM products WHERE products_id=$product_id");
			$product_name = $oscommercedb->get_var("SELECT products_name FROM products_description WHERE products_id=$product_id");
			$store_url = get_option('oscimp_store_url');
			$image_folder = get_option('oscimp_prod_img_folder');
	 
			//Build the HTML code
			$retval .= '<div class="oscimp_product">';
			$retval .= '<a href="'. $store_url . 'product_info.php?products_id=' . $product_id . '"><img src="' . $image_folder . $product_image . '" /></a><br />';
			$retval .= '<a href="'. $store_url . 'product_info.php?products_id=' . $product_id . '">' . $product_name . '</a>';
			$retval .= '</div>';
	 
		}
		return $retval;
	}
}
?>