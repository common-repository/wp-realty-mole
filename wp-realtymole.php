<?php

	//
	//	Plugin Name:	Rent Analysis Plugin for Property Managers
	//	Plugin URI:
	//	Description:	Rent Analysis Plugin provides accurate, real world rent estimates for a given rental property. It works by using a sophisticated correlation algorithm against thousands of recent rental listings.
	//	Version:		1.0.11
	//	Author:			Realty Mole
	//	Author URI:		https://www.realtymole.com
	//	Text Domain:	wp-realtymole
	//	License:		GPLv3
	//	License URI: 	https://www.gnu.org/licenses/gpl-3.0.html
	//


	//	PHP FILES
	//	----------------------------------------------------------------------------------------------------

		require_once('php/unirest/Unirest.php');
		foreach( glob(dirname(__FILE__) . '/php/*.php') as $file ) require_once($file);


	//	STYLES AND SCRIPTS
	//	----------------------------------------------------------------------------------------------------

		//  ADMIN STYLES
		function wp_realtymole_admin_styles() {

			wp_enqueue_style('wp-realtymole', plugins_url('/css/wp-realtymole-admin.css', __FILE__));

		} add_action('admin_print_styles', 'wp_realtymole_admin_styles');

        //  ADMIN SCRIPTS
		function wp_realtymole_admin_scripts() {

			wp_enqueue_script('wp-realtymole', plugins_url('/js/wp-realtymole-admin.js', __FILE__), array('jquery'));

		} add_action('admin_enqueue_scripts', 'wp_realtymole_admin_scripts');

		//  FRONTEND STYLES AND SCRIPTS
		function wp_realtymole_frontend_styles_scripts() {

			wp_enqueue_style('wp-realtymole', plugins_url('/css/wp-realtymole-frontend.css', __FILE__));
			wp_enqueue_script('wp-realtymole', plugins_url('/js/wp-realtymole-frontend.js', __FILE__), array('jquery'));

			$wp_realtymole_translation_array = array(
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'pluginUrl' => plugin_dir_url(__FILE__),
				'googleMapsApi' => get_option('wp-realtymole-google-maps-api'),
				'rent' => __('Rent: ', 'wp-realtymole'),
				'propertyType' => __('Property Type: ', 'wp-realtymole'),
				'squareFootage' => __('Square Footage: ', 'wp-realtymole'),
				'bedrooms' => __('bedrooms', 'wp-realtymole'),
				'bathrooms' => __('bathrooms', 'wp-realtymole'),
				'miles' => __('miles', 'wp-realtymole'),
				'email' => __('email', 'wp-realtymole')
			);

			wp_localize_script('wp-realtymole', 'wp_realtymole_object', $wp_realtymole_translation_array);

		} add_action('wp_enqueue_scripts', 'wp_realtymole_frontend_styles_scripts');


	//	NAVIGATION
	//	----------------------------------------------------------------------------------------------------

		function wp_realtymole_admin_menu() {

			add_submenu_page('options-general.php', __('WP Realty Mole', 'wp-realtymole'), __('WP Realty Mole', 'wp-realtymole'), 'edit_posts', 'wp-realtymole', 'wp_realtymole_settings');

		} add_action('admin_menu', 'wp_realtymole_admin_menu');


	//	PLUGIN INIT
	//	----------------------------------------------------------------------------------------------------

        function wp_realtymole_plugin_init() {

			//  SETTINGS
			register_setting('wp-realtymole-settings-group', 'wp-realtymole-mashape-api');
			register_setting('wp-realtymole-settings-group', 'wp-realtymole-google-maps-api');
			register_setting( 'wp-realtymole-settings-group', 'wp-realtymole-capture-email' );
			register_setting( 'wp-realtymole-settings-group', 'wp-realtymole-admin-email' );
			register_setting( 'wp-realtymole-settings-group', 'wp-realtymole-capture-free-text' );
			register_setting( 'wp-realtymole-settings-group', 'wp-realtymole-free-text' );
			
			//  REDIRECT AFTER INSTALL
			if( get_option('wp_realtymole_plugin_activation_redirect', false) ):
                delete_option('wp_realtymole_plugin_activation_redirect');
                if( !isset($_GET['activate-multi']) ) wp_redirect('options-general.php?page=wp-realtymole');
            endif;
            
		} add_action('admin_init', 'wp_realtymole_plugin_init');

		function wp_realtymole_plugin_activate() {

            add_option('wp_realtymole_plugin_activation_redirect', true);
            update_option( 'wp-realtymole-capture-email', 'true' );
            update_option( 'wp-realtymole-admin-email', '' );
            update_option( 'wp-realtymole-capture-free-text', 'true' );
            update_option( 'wp-realtymole-free-text', '' );
		}

		register_activation_hook(__FILE__, 'wp_realtymole_plugin_activate');


	//	SETTINGS SCREEN
	//	----------------------------------------------------------------------------------------------------

		function wp_realtymole_settings() {

			if( !current_user_can('manage_options') ):
				wp_die(__('You do not have sufficient permissions to access this page.', 'wp-realtymole'));
			endif; ?>

			<div id="wp-realtymole" class="wrap">

				<h2><?php _e('Rent Analysis for Property Managers - Settings', 'wp-realtymole'); ?></h2>

				<form method="post" action="options.php">

				    <?php settings_fields('wp-realtymole-settings-group'); ?>

				    <p><?php _e('Please provide the required information below:', 'wp-realtymole'); ?></p>

				    <table class="form-table" style="">
                        <tbody>
                            <tr>
		                        <th><label for="wp-realtymole-mashape-api"><?php _e('Realty Mole - API Key (optional)', 'wp-realtymole'); ?></label></th>
		                        <td>
		                            <input name="wp-realtymole-mashape-api" id="wp-realtymole-mashape-api" type="text" value="<?php echo get_option('wp-realtymole-mashape-api'); ?>" class="regular-text code"><br>
		                            <small> 50 reqs per month are free. Get a <a href="https://rapidapi.com/moneals/api/rent-estimate" target="_blank">Realty Mole Rapid API key</a> to enable more searches.</small>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="wp-realtymole-google-maps-api"><?php _e('Google Maps - API Key (mandatory)', 'wp-realtymole'); ?></label></th>
                                <td>
                                    <input name="wp-realtymole-google-maps-api" id="wp-realtymole-google-maps-api" type="text" value="<?php echo get_option('wp-realtymole-google-maps-api'); ?>" class="regular-text code"><br>
                                    <small><a href="https://console.cloud.google.com/marketplace/details/google/maps-backend.googleapis.com" target="_blank">Maps JavaScript API</a> and <a href="https://console.cloud.google.com/marketplace/details/google/street-view-image-backend.googleapis.com" target=_"blank">Street View Static API</a> must be enabled.</small>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="wp-realtymole-admin-email"><?php _e('Email Address <br /> (optional - if not added, email will be sent to WordPress Site Email)', 'wp-realtymole'); ?></label></th>
                                <td>
                                    <?php
                                        $capture_email_val = get_option('wp-realtymole-capture-email');
                                        if ( $capture_email_val == 'false' ):
                                            $capture_email_false = 'checked';
                                            $capture_email_true = '';
                                        else:
                                            $capture_email_true = 'checked';
                                            $capture_email_false = '';
                                        endif;
                                    ?>                                    
                                    <small>
                                        <input type="radio" name="wp-realtymole-capture-email" value="true" <?php echo $capture_email_true; ?>> Include Email Capture
                                        <input type="radio" name="wp-realtymole-capture-email" value="false" <?php echo $capture_email_false; ?>>Do Not Include Email Capture <br /> <br />
                                    </small>
                                    <input name="wp-realtymole-admin-email" id="wp-realtymole-admin-email" type="text" value="<?php echo get_option('wp-realtymole-admin-email'); ?>" class="regular-text code"><br>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="wp-realtymole-free-text"><?php _e('Disclaimer Text <br /> (optional - Text will appear below the \'Email Address\' on the front-end)', 'wp-realtymole'); ?></label></th>
                                <td>
                                    <?php
                                        $capture_free_text_val = get_option('wp-realtymole-capture-free-text');
                                        if ( $capture_free_text_val == 'false' ):
                                            $capture_free_text_false = 'checked';
                                            $capture_free_text_true = '';
                                        else:
                                            $capture_free_text_true = 'checked';
                                            $capture_free_text_false = '';
                                        endif;
                                    ?>                                    
                                    <small>
                                        <input type="radio" name="wp-realtymole-capture-free-text" value="true" <?php echo $capture_free_text_true; ?>> Display Free Text
                                        <input type="radio" name="wp-realtymole-capture-free-text" value="false" <?php echo $capture_free_text_false; ?>>Do Not Display Free Text <br /> <br />
                                    </small>
                                    <input name="wp-realtymole-free-text" id="wp-realtymole-free-text" type="text" value="<?php echo get_option('wp-realtymole-free-text'); ?>" class="regular-text code" maxlength="200" ><br>
                                    <small>Maximum 200 characters allowed.</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>

					<?php submit_button(); ?>

				</form>

			</div>

		<?php }


	//	ADMIN NOTICES
	//	----------------------------------------------------------------------------------------------------

		function wp_realtymole_admin_notices() {

            $screen = get_current_screen();

            if( !get_option('wp-realtymole-google-maps-api') ): ?>
                <div id="wp-realtymole-settings-error" class="notice notice-warning <?php if( $screen->id != 'settings_page_wp-realtymole' ): ?>is-dismissible<?php endif; ?>">
                    <p><strong>WP Realty Mole:</strong> Google Maps API key required! Don't have a Google Maps API key? Not sure how all this works? <a href="https://developers.google.com/maps/documentation/embed/get-api-key" target="_blank">Click here for more information &rsaquo;</a></p>
                </div>
            <?php endif;

        } add_action('admin_notices', 'wp_realtymole_admin_notices');


	//	AJAX CALLS
	//	----------------------------------------------------------------------------------------------------

	    function wp_realtymole_ajax() {

            //  PARSES DATA FROM FORM
            parse_str($_POST['formdata'], $formdata);

            //  REALTY MOLE ENDPOINT
            $realtymoleEndpoint = get_option('wp-realtymole-mashape-api') ? 'https://realtymole-rental-estimate-v1.p.rapidapi.com/rentalPrice' : 'https://api.realtymole.com/public/rentalPrice';

            //  URL CONSTRUCTION
            $concatenatedAddress = $formdata['wp-realtymole-address'] . ',' . $formdata['wp-realtymole-city'] . ',' . $formdata['wp-realtymole-state'];
			$apiUrl = $realtymoleEndpoint . '?address=' . urlencode($concatenatedAddress);
            if( $formdata['wp-realtymole-bedrooms'] ) $apiUrl .= '&bedrooms=' . urlencode($formdata['wp-realtymole-bedrooms']);
            if( $formdata['wp-realtymole-bathrooms'] ) $apiUrl .= '&bathrooms=' . urlencode($formdata['wp-realtymole-bathrooms']);
            if( $formdata['wp-realtymole-property-type'] ) $apiUrl .= '&propertyType=' . urlencode($formdata['wp-realtymole-property-type']);
            if( $formdata['wp-realtymole-square-footage'] ) $apiUrl .= '&squareFootage=' . urlencode($formdata['wp-realtymole-square-footage']);
            $apiUrl .= '&compCount=8';
            $apiUrl .= '&sourceDomain=' . get_home_url();
            
            
            $response = Unirest\Request::get($apiUrl,
                array(
                    'X-Mashape-Key' => get_option('wp-realtymole-mashape-api'),
                    'Accept' => 'text/plain'
                )
            );
            
            $getResponseVal = $response->raw_body;
            $getDecodeData = json_decode($getResponseVal);
            
            $has_form_email = isset( $formdata['wp-realtymole-form-email'] ) ? $formdata['wp-realtymole-form-email'] : '';
            
            if ( $has_form_email ) {
                $to = get_option( 'wp-realtymole-admin-email' ) ? get_option( 'wp-realtymole-admin-email' ) : get_bloginfo('admin_email');
                $form_email = $formdata['wp-realtymole-form-email'];
                $subject = "New Rent-Estimate Search Alert";
                $email_body = "<strong><u>New Rent-Estimate Search Alert</u></strong> <br /> <br />".
                "The following user has performed a rent-estimate using the WP Realty Mole Rent Estimate Plugin on your Wordpress site: <br /> <br />";
                $email_body .= "<strong>Email:</strong> $form_email <br />";
                $email_body .= "<strong>Address:</strong> " . $formdata['wp-realtymole-address'] . ", " . $formdata['wp-realtymole-city'] . ', ' . $formdata['wp-realtymole-state'] .". <br />";
                $email_body .= "<strong>Rent Price:</strong> $" . $getDecodeData->rent . "<br />";
                $email_body .= "<strong>Rent Estimate Range:</strong> $" . $getDecodeData->rentRangeLow . " - $" .  $getDecodeData->rentRangeHigh . "<br /> <br />";
                $email_body .= "Thank you! <br />";
                $email_body .= "The Realty Mole Team";
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($to, $subject, $email_body, $headers);
            }
            
            echo $response->raw_body;

            die();

	    }
	    add_action('wp_ajax_wp_realtymole_ajax', 'wp_realtymole_ajax');
	    add_action('wp_ajax_nopriv_wp_realtymole_ajax', 'wp_realtymole_ajax');

?>
