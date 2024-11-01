<?php

	//	WIDGETS
	//	----------------------------------------------------------------------------------------------------
	
        //  REGISTER WIDGET
        function wp_realtymole_widget_load() {

            register_widget('wp_realtymole_widget');


        } add_action('widgets_init', 'wp_realtymole_widget_load');
 
        // CREATE THE WIDGET
        class wp_realtymole_widget extends WP_Widget {
 
            function __construct() {

                parent::__construct('wp_realtymole_widget', __('WP Realty Mole Widget', 'wp-realtymole'), array('description' => __('WP Realty Mole lets you use the RealtyMole.com API to scan thousands of rental listings to caculate and estimate rent for the address you enter.', 'wp-realtymole')));

            }
 
            //  WIDGET FRONTEND
            public function widget($args, $instance) {

                $title = apply_filters('widget_title', $instance['title']);

                echo $args['before_widget'];

                if( !empty($title) ) echo $args['before_title'] . $title . $args['after_title'];
 
                echo do_shortcode('[wp-realtymole-form]');

                echo $args['after_widget'];

            }
         
            //  WIDGET BACKEND
            public function form($instance) {

                if( isset($instance['title']) ):
                    $title = $instance['title'];
                else:
                    $title = __('New title', 'wp-realtymole');
                endif;

            // WIDGET ADMIN FORM ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>

            <?php }
     
            // UPDATE WIDGET, REPLACING OLD INSTANCE
            public function update($new_instance, $old_instance) {

                $instance = array();
                $instance['title'] = ( !empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';

                return $instance;

            }
            
        }
	
?>