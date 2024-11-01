<?php
/*
Plugin Name: WP-EthTipJar Plugin
Description: Receive Ethereum tips from a widget. MetaMask required.
Version: 1.1.3
Author: web3 devs
Author URI: http://web3devs.com
License: GPLv2 or later
*/

// The widget class
class EthTipJar_Widget extends WP_Widget {

	// Main constructor
	public function __construct() {
		parent::__construct(
			'ethtipjar_widget',
			__( 'ETHTipJar', 'text_domain' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	// The widget form (for the backend )
	public function form( $instance ) {	
		/* ... */
	}

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		/* ... */
	}

	// Display the widget
	public function widget( $args, $instance ) {
		wp_register_script( 'custom-script', plugins_url( '/js/main.1116a0c0.js', __FILE__ ) );
		wp_register_style( 'custom-css', plugins_url( '/css/styles.css', __FILE__ ) );
		wp_enqueue_script( 'custom-script' );
		wp_enqueue_style('custom-css');
		wp_enqueue_script('jquery');
		if (get_option('mt_favorite_color')) {
			echo '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">';

			echo '<div class="widget-text wp_widget_plugin_box wp_widget_wp_ethtipjar">';
			
			echo '<div class="ethtipjar_button" onclick="jQuery(\'.ethtipjar_content\').toggle();">';
			
			echo '<i class="fab fa-ethereum"></i> Tip</div>';
			echo '<div class="ethtipjar_content" style="background:#ccc">';
			echo "<script>window.ETJAddress = '".get_option('mt_favorite_color')."';</script>";
			echo '<div id="root"></div>';
		
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}

}

// Register the widget
function register_ethtipjar_widget() {
	register_widget( 'EthTipJar_Widget' );
}
add_action( 'widgets_init', 'register_ethtipjar_widget' );



// Create admin settings page

add_action( 'admin_menu', 'ethtipjar_menu' );

/** Step 1. */
function ethtipjar_menu() {
	add_options_page( 'EthTipJar Settings', 'EthTipJar', 'manage_options', 'ethtipjar-plugin-identifier', 'ethtipjar_options' );
}

/** Step 3. */
function ethtipjar_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	// variables for the field and option names 
    $opt_name = 'mt_favorite_color';
    $hidden_field_name = 'mt_submit_hidden';
    $data_field_name = 'mt_favorite_color';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put a "settings saved" message on the screen

?>
<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'EthTipJar Plugin Settings', 'menu-test' ) . "</h2>";

    // settings form
    
    ?>
<div class="card">
	<h3>Get Started:</h3>

<p>In order to be able to receive Ether tips, you need to deploy a "smart contract" to the Ethereum blockchain. This will create an address for users to send Ethereum Cryptocurrency(Ether), as well as verify that the funds then get forwarded to your preferred address.</p>

</div>
<?php if (!get_option($data_field_name)) { ?>
<div class="error"><p><strong>Your contract is not yet deployed:</strong></p>
<div class="card">
	<p>In order to deploy contracts, you need a browser plugin called <a href="https://metamask.io">MetaMask</a> installed.</p>

<p>There is a small fee you will need to pay to deploy the contract. This fee, known as “gas” goes to the Ethereum network to pay for the network executing your code.</p>

<p>Once you have MetaMask installed, return to the EthTipJar plugin setting in your WordPress site dashboard. Click on the button labeled "Initialize EthTipJar" and MetaMask will walk you through the rest of the process.</p>

<p>Once your contract is deployed, you will be able to view it on EtherScan.</p> 

<button value="deploycontract" onclick="contract.deployContract();">Deploy</button>

</div>
<?php 

wp_register_script( 'jquery-script', 'https://code.jquery.com/jquery-3.1.0.min.js', __FILE__ ) ;
wp_enqueue_script( 'jquery-script' );

	wp_register_script( 'custom-script', plugins_url( '/js/web3deploy.jquery.js', __FILE__ ) );
		wp_enqueue_script( 'custom-script' );

		
		echo '<div class="widget-text wp_widget_plugin_box">';
		echo '<div id="root"></div>';
		
		
		echo '</div>';
		echo '</div>';
	?>


</p>

</div>
<hr />
<?php } ?>
<h3>Your Contract: </h3>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<small>View on Etherscan: https://etherscan.io/address/<?php echo get_option($data_field_name); ?></small>
<div class="card">
	<p>EtherScan is a service that allows you to see any publicly available data on any item in the Ethereum blockchain. It will tell you how many tips you’ve received at your newly deployed contract.</p>
<h3>Display your EthTipJar on your website.</h3>

<p>EthTipJar is a WordPress widget.</p>

<p><a href="/wp-admin/widgets.php">Display the widget on your site</a> for visitors to send you Ether.</p>
</div>
<p>
	<?php _e("Contract Address:", 'menu-test' ); ?>
<input type="text" id="ethtipjar-contract-address-field" name="<?php echo $data_field_name; ?>" value="<?php echo get_option($data_field_name); ?>">
</p>
<hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>

<?php
	echo '</div>';
}


// END Create admin settings page