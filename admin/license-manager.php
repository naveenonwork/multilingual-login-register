<?php
if ( ! class_exists( 'Wp_License_Manager_Client' ) ) {
 
    class Wp_License_Manager_Client {
	

			private $api_endpoint;
			private $product_id;
			private $product_name;
			private $type;
			private $plugin_name;
			private $plugin_file;
				
	
	
	public function __construct( $product_id, $product_name, $plugin_name, $api_url,
                             $type = 'plugin', $plugin_file = '' ) {
        // Store setup data
        $this->product_id = $product_id;
        $this->product_name = $product_name;
        $this->plugin_name = $plugin_name;
        $this->api_endpoint = $api_url;
        $this->type = $type;
        $this->plugin_file = $plugin_file;
    }

	public function add_license_settings_page() {
    $title = sprintf( __( '%s License', $this->plugin_name ), $this->product_name );
 
    add_options_page(
        $title,
        $title,
        'read',
        $this->get_settings_page_slug(),
        array( $this, 'render_licenses_menu' )
    );
}
			 
			/**
			 * Creates the settings fields needed for the license settings menu.
			 */
			public function add_license_settings_fields() {
				$settings_group_id = $this->product_id . '-license-settings-group';
				$settings_section_id = $this->product_id . '-license-settings-section';
			 
				register_setting( $settings_group_id, $this->get_settings_field_name() );
			 
				add_settings_section(
					$settings_section_id,
					__( 'License', $this->plugin_name ),
					array( $this, 'render_settings_section' ),
					$settings_group_id
				);
			 
				add_settings_field(
					$this->product_id . '-license-email',
					__( 'License e-mail address', $this->plugin_name ),
					array( $this, 'render_email_settings_field' ),
					$settings_group_id,
					$settings_section_id
				);
			 
				add_settings_field(
					$this->product_id . '-license-key',
					__( 'License key', $this->plugin_name ),
					array( $this, 'render_license_key_settings_field' ),
					$settings_group_id,
					$settings_section_id
				);
			}
	
	
	
						public function render_settings_section() {
						_e( 'Insert your license information to enable updates.', $this->plugin_name);
					}
					 
					/**
					 * Renders the settings page for entering license information.
					 */
					public function render_licenses_menu() {
						$title = sprintf( __( '%s License', $this->plugin_name ), $this->product_name );
						$settings_group_id = $this->product_id . '-license-settings-group';
					 
						?>
							<div class="wrap">
								<form action='options.php' method='post'>
					 
									<h2><?php echo $title; ?></h2>
					 
									<?php
										settings_fields( $settings_group_id );
										do_settings_sections( $settings_group_id );
										submit_button();
									?>
					 
								</form>
							</div>
						<?php
					}
					 
					/**
					 * Renders the email settings field on the license settings page.
					 */
					public function render_email_settings_field() {
						$settings_field_name = $this->get_settings_field_name();
						$options = get_option( $settings_field_name );
						?>
							<input type='text' name='<?php echo $settings_field_name; ?>[email]'
							   value='<?php echo $options['email']; ?>' class='regular-text'>
						<?php
					}
					 
					/**
					 * Renders the license key settings field on the license settings page.
					 */
					public function render_license_key_settings_field() {
						$settings_field_name = $this->get_settings_field_name();
						$options = get_option( $settings_field_name );
						?>
							<input type='text' name='<?php echo $settings_field_name; ?>[license_key]'
							   value='<?php echo $options['license_key']; ?>' class='regular-text'>
						<?php
					}
						
	
	
	
	
	
	protected function get_settings_field_name() {
    return $this->product_id . '-license-settings';
}
 
/**
 * @return string   The slug id of the licenses settings page.
 */
protected function get_settings_page_slug() {
    return $this->product_id . '-licenses';
}


						/**
			 * If the license has not been configured properly, display an admin notice.
			 */
			public function show_admin_notices() {
				$options = get_option( $this->get_settings_field_name() );
			 
				if ( !$options || ! isset( $options['email'] ) || ! isset( $options['license_key'] ) ||
					$options['email'] == '' || $options['license_key'] == '' ) {
			 
					$msg = __( 'Please enter your email and license key to enable updates to %s.', $this->plugin_name );
					$msg = sprintf( $msg, $this->product_name );
					?>
						<div class="update-nag">
							<p>
								<?php echo $msg; ?>
							</p>
			 
							<p>
								<a href="<?php echo admin_url( 'options-general.php?page=' . $this->get_settings_page_slug() ); ?>">
									<?php _e( 'Complete the setup now.', $this->plugin_name ); ?>
								</a>
							</p>
						</div>
					<?php
				}
			}
	
	
	
	
				private function call_api( $action, $params ) {
				$url = $this->api_endpoint . '/' . $action;
			 
				// Append parameters for GET request
			  	$url .= '?' . http_build_query( $params );
			 
				// Send the request
				$response = wp_remote_get( $url );
				 
				if ( is_wp_error( $response ) ) {
					return false;
				}
					 
				$response_body = wp_remote_retrieve_body( $response );
				$result = json_decode( $response_body );
				 
				return $result;
			}
	
				private function is_api_error( $response ) {
				if ( $response === false ) {
					return true;
				}
			 
				if ( ! is_object( $response ) ) {
					return true;
				}
			 
				if ( isset( $response->error ) ) {
					return true;
				}
			 
				return false;
			}
	
	
	
	
	
				public function get_license_info($license_data) {
				
				/*$options = get_option( $this->get_settings_field_name() );
				if ( ! isset( $options['email'] ) || ! isset( $options['license_key'] ) ) {
					// User hasn't saved the license to settings yet. No use making the call.
					return false;
				}*/
			 
				$info = $this->call_api(
					'info',
					array(
						'p' => $this->product_id,
						'e' => $license_data['license_email'],
						'l' => $license_data['license_key']
					)
				);
			 
				return $info;
			}
	
	
	
	
	
	
	
	
	
	
     
    }//////////////class
 
}




























?>