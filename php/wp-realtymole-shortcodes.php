<?php

	//	SHORTCODES
	//	----------------------------------------------------------------------------------------------------

		function wp_realtymole_shortcode($atts, $content = null) {

			extract(shortcode_atts(array(
			), $atts));

		    ob_start(); ?>

                <!-- WP REALTY MOLE FORM -->
                <div id="wp-realtymole-form">
                    <form method="post" action="#">
                        <div class="form-group" id="wp-realtymole-form-row">
                            <div id="wp-realtymole-form-column">
                                <label for="wp-realtymole-address"><?php _e('Address', 'wp-realtymole'); ?></label>
                            </div>
                            <div id="wp-realtymole-form-column">    
                            <input type="text" name="wp-realtymole-address" id="wp-realtymole-address" required><br>
								<small><?php _e('Required', 'wp-realtymole'); ?>.</small>
                            </div>
                        </div>
						<div class="form-group" id="wp-realtymole-form-row">
                            <div id="wp-realtymole-form-column">
                               	<label for="wp-realtymole-city"><?php _e('City', 'wp-realtymole'); ?></label>
                            </div>
                            <div id="wp-realtymole-form-column">
                                <input type="text" name="wp-realtymole-city" id="wp-realtymole-city" required><br>
								<small><?php _e('Required', 'wp-realtymole'); ?>.</small>
                            </div>
                        </div>
						<div class="form-group" id="wp-realtymole-form-row">
                            <div id="wp-realtymole-form-column">
                               	<label for="wp-realtymole-state"><?php _e('State', 'wp-realtymole'); ?></label>
                            </div>
                            <div id="wp-realtymole-form-column">
                                <input type="text" name="wp-realtymole-state" id="wp-realtymole-state" required><br>
								<small><?php _e('Required', 'wp-realtymole'); ?>.</small>
                            </div>
                        </div>
                        <div class="form-group" id="wp-realtymole-form-row">
                            <div id="wp-realtymole-form-column">
                                <label for="wp-realtymole-bedrooms"><?php _e('Bedrooms', 'wp-realtymole'); ?></label>
                            </div>
                            <div id="wp-realtymole-form-column">
                                <input type="number" min="0" name="wp-realtymole-bedrooms" id="wp-realtymole-bedrooms"><br>
								<small><?php _e('Optional', 'wp-realtymole'); ?>. <?php _e('Number only', 'wp-realtymole'); ?>.</small>
                            </div>
                        </div>
                        <div class="form-group" id="wp-realtymole-form-row">
                            <div id="wp-realtymole-form-column">
                                <label for="wp-realtymole-bathrooms"><?php _e('Bathrooms', 'wp-realtymole'); ?></label>
                            </div>
                            <div id="wp-realtymole-form-column">
                                <input type="number" min="0" name="wp-realtymole-bathrooms" id="wp-realtymole-bathrooms"><br>
								<small><?php _e('Optional', 'wp-realtymole'); ?>. <?php _e('Number only', 'wp-realtymole'); ?>.</small>
                            </div>
                        </div>
                        <div class="form-group" id="wp-realtymole-form-row">
                            <div id="wp-realtymole-form-column">
                                <label for="wp-realtymole-property-type"><?php _e('Property Type', 'wp-realtymole'); ?></label>
                            </div>
                            <div id="wp-realtymole-form-column">
                                <select name="wp-realtymole-property-type" id="wp-realtymole-property-type">
                                    <option value="">-</option>
                                    <option value="Apartment">Apartment</option>
                                    <option value="Single Family">Single Family</option>
                                    <option value="Townhouse">Townhouse</option>
                                    <option value="Condo">Condo</option>
                                    <option value="Duplex-Triplex">Duplex-Triplex</option>
                                </select><br>
								<small><?php _e('Optional', 'wp-realtymole'); ?>.</small>
                            </div>
                        </div>
                        <div class="form-group" id="wp-realtymole-form-row">
                            <div id="wp-realtymole-form-column">
                                <label for="wp-realtymole-square-footage"><?php _e('Square Footage', 'wp-realtymole'); ?></label>
                            </div>
                            <div id="wp-realtymole-form-column">
                                <input type="number" min="0" name="wp-realtymole-square-footage" id="wp-realtymole-square-footage"><br>
								<small><?php _e('Optional', 'wp-realtymole'); ?>. <?php _e('Number only', 'wp-realtymole'); ?>.</small>
                            </div>
                        </div>
                        <?php if( get_option('wp-realtymole-capture-email') == 'true' ): ?>
                        <div class="form-group" id="wp-realtymole-form-row">
                            <div id="wp-realtymole-form-column">
                                <label for="wp-realtymole-form-email"><?php _e('Email Address', 'wp-realtymole'); ?></label>
                            </div>
                            <div id="wp-realtymole-form-column">
                                <input type="email" name="wp-realtymole-form-email" id="wp-realtymole-form-email" required><br>
								<small><?php _e('Required', 'wp-realtymole'); ?>.</small>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if( get_option('wp-realtymole-capture-free-text') == 'true' ): ?>
                        <div class="form-group" id="wp-realtymole-form-row">
                            <div id="wp-realtymole-form-column"> &nbsp; </div>
                            <div id="wp-realtymole-form-column" class="wp-realtymole-free-text"> <?php echo get_option('wp-realtymole-free-text'); ?> </div>
                        </div>
                        <?php endif; ?>
                        <p>
							<button type="submit" id="wp-realtymole-search"><?php _e('Search', 'wp-realtymole'); ?></button>
							<span id="wp-realtymole-loading"><?php _e('Loading...', 'wp-realtymole'); ?></span>
						</p>
                    </form>
				</div>

    	        <!-- WP REALTY MOLE QUOTA REACHED -->
    	        <div id="wp-realtymole-quota-exceeded">
					<p>WARNING: Quota reached. Contact the website administrator.</p>
                </div>

    	        <!-- WP REALTY MOLE RESULTS -->
    	        <div id="wp-realtymole-results">
                    <ul>
                        <li><h4><?php _e('<strong>Address:</strong>', 'wp-realtymole'); ?> <span id="wp-realtymole-searched-address"></span></h4></li>
						<li><h4><?php _e('<strong>Your property rental estimate is:</strong>', 'wp-realtymole'); ?> $<span id="wp-realtymole-estimated-rent"></span></h4></li>
						<li><h4><?php _e('<strong>Properties like this typically rent for:</strong>', 'wp-realtymole'); ?> $<span id="wp-realtymole-rent-range-low"></span> - $<span id="wp-realtymole-rent-range-high"></span></h4></li>
                    </ul>
                    <h3><?php _e('Comparable Rentals', 'wp-realtymole'); ?></h3>
    	            <div id="wp-realtymole-listings"><ol></ol></div>
    	            <?php if( get_option('wp-realtymole-google-maps-api') ): ?><div id="wp-realtymole-google-map"></div><?php endif; ?>
					<p>
						<button type="submit" id="wp-realtymole-search-again"><?php _e('Search again', 'wp-realtymole'); ?></button>
					</p>
                </div>

				<p><small><?php printf(__('Powered by <a href="%s" target="_blank">RealtyMole.com</a>.', 'wp-realtymole'), 'https://www.realtymole.com/'); ?></small></p>

                <!-- WP REALTY MOLE GOOGLE MAPS -->
                <?php if( get_option('wp-realtymole-google-maps-api') ): ?>
                    <script>
                        var wp_realtymole_map;
                        var wp_realtymole_map_bounds;
                        function wp_realtymole_initMap() {
                            wp_realtymole_map = new google.maps.Map(document.getElementById('wp-realtymole-google-map'), { maxZoom: 16 });
                            wp_realtymole_map_bounds = new google.maps.LatLngBounds();
                        }
                    </script>
                    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option('wp-realtymole-google-maps-api'); ?>&callback=wp_realtymole_initMap"></script>
                <?php endif; ?>

	        <?php $content = ob_get_clean();

	        return $content;

		} add_shortcode('wp-realtymole-form', 'wp_realtymole_shortcode');

?>
