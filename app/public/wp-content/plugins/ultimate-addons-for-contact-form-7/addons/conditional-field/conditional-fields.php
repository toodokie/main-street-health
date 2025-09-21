<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UACF7_CF {

	private $hidden_fields = array();

	public $invalid_field_key = null;

	/*
	 * Construct function
	 */
	public function __construct() {
		global $pagenow;
		if ( isset( $_GET['page'] ) ) {
			if ( ( $pagenow == 'admin.php' ) && ( $_GET['page'] == 'wpcf7' ) || ( $_GET['page'] == 'wpcf7-new' ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_cf_admin_script' ) );
			}
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_cf_frontend_script' ) );
		add_action( 'wpcf7_init', array( __CLASS__, 'add_shortcodes' ) );
		add_action( 'admin_init', array( $this, 'tag_generator' ) );


		add_filter( 'wpcf7_contact_form_properties', array( $this, 'uacf7_properties' ), 10, 2 );

		add_action( 'wpcf7_form_hidden_fields', array( $this, 'uacf7_form_hidden_fields' ), 10, 1 );

		add_filter( 'wpcf7_posted_data', array( $this, 'remove_hidden_post_data' ) );
		add_filter( 'wpcf7_validate', array( $this, 'skip_validation_for_hidden_fields' ), 2, 2 );

		add_action( 'wpcf7_validate_checkbox*', array($this, 'skip_hidden_checkbox_required') , 10, 2 );

		add_filter( 'wpcf7_validate_file*', array( $this, 'skip_validation_for_hidden_file_field' ), 30, 3 );
		add_filter( 'wpcf7_validate_multifile*', array( $this, 'skip_validation_for_hidden_file_field' ), 30, 3 );
		
		add_action( 'wpcf7_config_validator_validate', array( $this, 'uacf7_config_validator_validate' ) );

		add_action( 'wpcf7_before_send_mail', array( $this, 'uacf7_conditional_mail_properties' ) );

		add_filter( 'uacf7_post_meta_options', array( $this, 'uacf7_post_meta_options_conditional_field' ), 11, 2 );
		add_filter( 'uacf7_pdf_generator_replace_condition_data', array( $this, 'uacf7_condition_replace_pdf' ), 11, 3 );

		// add_filter( 'wpcf7_load_js', '__return_false' );

	}

	public function enqueue_cf_admin_script() {
		wp_enqueue_script( 'uacf7-cf-script', UACF7_ADDONS . '/conditional-field/js/cf-script.js', array( 'jquery' ), UACF7_VERSION, true );
	}

	public function enqueue_cf_frontend_script() {
		wp_enqueue_script( 'uacf7-cf-script', UACF7_ADDONS . '/conditional-field/js/uacf7-cf-script.js', array( 'jquery' ), UACF7_VERSION, true );
		wp_localize_script( 'uacf7-cf-script', 'uacf7_cf_object', $this->get_forms() );
	}

	public function uacf7_post_meta_options_conditional_field( $value, $post_id ) {

		$conditional = apply_filters( 'uacf7_post_meta_options_conditional_field_pro', $data = array(
			'title' => __( 'Conditional Fields', 'ultimate-addons-cf7' ),
			'icon' => 'fa-solid fa-fan',
			'checked_field' => 'conditional_repeater',
			'fields' => array(
				'conditional_heading' => array(
					'id' => 'conditional_heading',
					'type' => 'heading',
					'label' => __( 'Conditional Fields Settings', 'ultimate-addons-cf7' ),
					'subtitle' => sprintf(
						__( 'Show or hide Contact Form 7 fields based on Conditional Logic. See Demo %1s.', 'ultimate-addons-cf7' ),
						'<a href="https://cf7addons.com/preview/contact-form-7-conditional-fields/" target="_blank">Example</a>'
					)
				),
				'conditional_field_docs' => array(
					'id' => 'conditional_field_docs',
					'type' => 'notice',
					'style' => 'success',
					'content' => sprintf(
						__( 'Confused? Check our Documentation on  %1s and %2s.', 'ultimate-addons-cf7' ),
						'<a href="https://themefic.com/docs/uacf7/free-addons/contact-form-7-conditional-fields/" target="_blank">Conditional Fields</a>',
						'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-conditional-fields-pro/" target="_blank">Conditional Fields (Pro)</a>'
					)
				),
				'conditional_form_options_heading' => array(
					'id' => 'conditional_form_options_heading',
					'type' => 'heading',
					'label' => __( 'Conditional Option ', 'ultimate-addons-cf7' ),
				),
				'conditional_repeater' => array(
					'id' => 'conditional_repeater',
					'type' => 'repeater',
					'label' => __( 'Setup your Conditional Logic', 'ultimate-addons-cf7' ),
					'subtitle' => __( "The process involves selecting a field and determining its visibility (either visible or hidden) based on whether any or all specified conditions are met. These conditions are triggered by the conditional value you establish for another field.", 'ultimate-addons-cf7' ),
					'class' => 'tf-field-class',
					'fields' => array(
						'uacf7_cf_group' => array(
							'id' => 'uacf7_cf_group',
							'type' => 'select',
							'label' => __( 'Choose Field', 'ultimate-addons-cf7' ),
							'subtitle' => "Wrap a field with this shortcode: [conditional conditional-123][/conditional]. Replace 'conditional-123' with your specific ID.",
							'class' => 'tf-field-class',
							'options' => 'uacf7',
							'query_args' => array(
								'post_id' => $post_id,
								'specific' => 'conditional',
							),
							'field_width' => '100',
						),
						'uacf7_cf_hs' => array(
							'id' => 'uacf7_cf_hs',
							'type' => 'select',
							'label' => __( 'Visibility', 'ultimate-addons-cf7' ),
							'subtitle' => "Select whether this field should be visible or hidden when the condition below is met.",
							'class' => 'tf-field-class',
							'options' => array(
								'show' => 'Show',
								'hide' => 'Hide',
							),
							'field_width' => '50',
						),
						'uacf7_cf_condition_for' => array(
							'id' => 'uacf7_cf_condition_for',
							'type' => 'select',
							'label' => __( 'If', 'ultimate-addons-cf7' ),
							'subtitle' => "Choose the trigger for the condition: it should activate if 'any' one of the conditions is met or when 'all' conditions.",
							'class' => 'tf-field-class',
							'options' => array(
								'any' => 'Any',
								'all' => 'All',
							),
							'field_width' => '50',

						),
						'uacf7_cf_conditions' => array(
							'id' => 'uacf7_cf_conditions',
							'type' => 'repeater',
							'label' => __( 'Add Condition', 'ultimate-addons-cf7' ),
							'class' => 'tf-field-class',
							'fields' => array(

								'uacf7_cf_tn' => array(
									'id' => 'uacf7_cf_tn',
									'type' => 'select',
									'label' => __( 'Conditional Field', 'ultimate-addons-cf7' ),
									'class' => 'tf-field-class',
									'options' => 'uacf7',
									'query_args' => array(
										'post_id' => $post_id,
										'exclude' => [ 'submit', 'conditional' ],
									),
									'field_width' => '50',
								),
								'uacf7_cf_operator' => array(
									'id' => 'uacf7_cf_operator',
									'type' => 'select',
									'label' => __( 'is', 'ultimate-addons-cf7' ),
									'class' => 'tf-field-class',
									'options' => array(
										'equal' => 'equal',
										'not_equal' => 'Not Equal',
										'greater_than' => 'Greater than',
										'less_than' => 'Less than',
										'greater_than_or_equal_to' => 'Greater than or equal to',
										'less_than_or_equal_to' => 'Less than or equal to',
										'starts_with' => 'Starts with',
										'ends_with' => 'Ends With',
										'contains' => 'Contains',
										'does_not_contain' => 'Does not contain'
									),
									'field_width' => '50',
								),
								'uacf7_cf_val' => array(
									'id' => 'uacf7_cf_val',
									'type' => 'text',
									'label' => 'Conditional Value',
									'subtitle' => 'Input the specific value that will trigger the condition.',
									'description' => '',
									'class' => 'tf-field-class',
								)
							),
						)
					),
				)

			),
		), $post_id );
		$value['conditional'] = $conditional;
		return $value;
	}

	/*
	 * Form tag
	 */
	public static function add_shortcodes() {
		if ( function_exists( 'wpcf7_add_form_tag' ) ) {
			wpcf7_add_form_tag( 'conditional', array( __CLASS__, 'custom_conditional_form_tag_handler' ), true );
		}
	}

	public static function custom_conditional_form_tag_handler( $tag ) {
		ob_start();
		$tag = new WPCF7_FormTag( $tag );
		?>
		<div>
			<?php $tag->content; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/*
	 * Generate tag - conditional
	 */
	public function tag_generator() {
		$tag_generator = WPCF7_TagGenerator::get_instance();

		$tag_generator->add(
			'conditional',
			__( 'Conditional Wraper', 'ultimate-addons-cf7' ),
			[ $this, 'tg_pane_conditional' ],
			array( 'version' => '2' )
		);
	}

	static function tg_pane_conditional( $contact_form, $options ) {
		$field_types = array(
			'conditional' => array(
				'display_name' => __( 'conditional area', 'contact-form-7' ),
				'heading' => __( 'Generate a conditional tag to wrap the elements that can be shown conditionally.', 'ultimate-addons-cf7' ),
				'description' => __( 'Check "Conditional Fields" tab located under the Addons for CF7 Options for additional settings. Make sure to set those, otherwise the conditions may not work correctly.', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );
		// $uacf7_field_type = 'conditional';
		?>

		<header class="description-box">
			<h3>
				<?php echo esc_html( $field_types['conditional']['heading'] ); ?>
			</h3>

			<p><?php
			$description = wp_kses(
				$field_types['conditional']['description'],
				array(
					'a' => array( 'href' => true ),
					'strong' => array(),
				),
				array( 'http', 'https' )
			);

			echo $description;
			?></p>
			<div class="uacf7-doc-notice">
				Confused? Check our Documentation on
				<a href="https://themefic.com/docs/uacf7/free-addons/contact-form-7-conditional-fields/" target="_blank">
					Conditional Fields
				</a>.
			</div>
		</header>

		<div class="control-box uacf7-control-box version2">
			<?php

			$tgg->print( 'field_type', array(
				'select_options' => array(
					'conditional' => $field_types['conditional']['display_name'],
				),
			) );

			$tgg->print( 'field_name' );
			?>
		</div>

		<footer class="insert-box">
			<?php
			$tgg->print( 'insert_box_content' );

			$tgg->print( 'mail_tag_tip' );
			?>
		</footer>
		<?php
	}


	public function get_forms() {
		$args = array(
			'post_type' => 'wpcf7_contact_form',
			'posts_per_page' => -1,
		);
		$query = new WP_Query( $args );

		$forms = array();

		if ( $query->have_posts() ) :

			while ( $query->have_posts() ) :
				$query->the_post();

				$post_id = get_the_ID();

				// if($post_id != 128) continue;

				$conditional = uacf7_get_form_option( $post_id, 'conditional' );

				if ( $conditional != false ) {
					$conditional_repeater = $conditional['conditional_repeater'];
					if ( $conditional_repeater != false ) {
						$count = 0;
						$data = [];
						// beaf_print_r($conditional_repeater);
						foreach ( $conditional_repeater as $item ) {
							$newItem = [ 
								'uacf7_cf_hs' => $item['uacf7_cf_hs'],
								'uacf7_cf_group' => $item['uacf7_cf_group'],
								'uacf7_cf_condition_for' => isset( $item['uacf7_cf_condition_for'] ) ? $item['uacf7_cf_condition_for'] : 'any',
								'uacf7_cf_conditions' => [ 
									'uacf7_cf_tn' => [],
									'uacf7_cf_operator' => [],
									'uacf7_cf_val' => [],
								],
							];

							if ( isset( $item['uacf7_cf_conditions'] ) ) {
								foreach ( $item['uacf7_cf_conditions'] as $condition ) {
									$newItem['uacf7_cf_conditions']['uacf7_cf_tn'][] = $condition['uacf7_cf_tn'];
									$newItem['uacf7_cf_conditions']['uacf7_cf_operator'][] = $condition['uacf7_cf_operator'];
									$newItem['uacf7_cf_conditions']['uacf7_cf_val'][] = $condition['uacf7_cf_val'];
								}
								$data[] = $newItem;
							}


						}
						// uacf7_print_r($data);
						// $data = get_post_meta( get_the_ID(), 'uacf7_conditions', true );
						$forms[ $post_id ] = $data;
					}
				}


			endwhile;
			wp_reset_postdata();
		endif;

		return $forms;
	}

	public function uacf7_properties( $properties, $cfform ) {

		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

			$form = $properties['form'];

			$form_parts = preg_split( '/(\[\/?conditional(?:\]|\s.*?\]))/', $form, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );

			ob_start();

			$stack = array();

			foreach ( $form_parts as $form_part ) {
				if ( substr( $form_part, 0, 13 ) == '[conditional ' ) {
					$tag_parts = explode( ' ', rtrim( $form_part, ']' ) );

					array_shift( $tag_parts );

					$tag_id = $tag_parts[0];
					$tag_html_type = 'div';

					array_push( $stack, $tag_html_type );

					echo '<' . $tag_html_type . ' class="uacf7_conditional ' . esc_attr( $tag_id ) . '">';
				} else if ( $form_part == '[/conditional]' ) {
					echo '</' . array_pop( $stack ) . '>';
				} else {
					echo $form_part;
				}
			}

			$properties['form'] = ob_get_clean();
		}
		return $properties;
	}
	

	function skip_validation_for_hidden_fields( $result, $tags ) {
		if ( isset( $_POST ) ) {
			$this->set_hidden_fields_arrays( $_POST );
		}

		$invalid_fields = $result->get_invalid_fields();
		$return_result = new WPCF7_Validation();

		if ( count( $this->hidden_fields ) == 0 || ! is_array( $invalid_fields ) || count( $invalid_fields ) == 0 ) {
			return $result;
		}

		// Normalize hidden field names (strip [] if exists)
		$normalized_hidden_fields = array_map(function($field) {
			return rtrim($field, '[]');
		}, $this->hidden_fields);

		foreach ( $invalid_fields as $invalid_field_key => $invalid_field_data ) {
			$normalized_key = rtrim($invalid_field_key, '[]');

			if ( ! in_array( $normalized_key, $normalized_hidden_fields ) ) {

				foreach ( $tags as $tag ) {
					if ( ($tag->basetype == 'checkbox' && $tag->is_required()) || 
						($tag->basetype == 'dragdropfile' && $tag->is_required()) ) {

						$tag_name = rtrim($tag->name, '[]');
						if ( $tag_name === $normalized_key && in_array($tag_name, $normalized_hidden_fields) ) {
							$this->invalid_field_key = $invalid_field_key;
							continue 2; // skip invalidation
						}
					}
				}

				$return_result->invalidate( $invalid_field_key, $invalid_field_data['reason'] );
			}
		}

		return apply_filters( 'uacf7_validate', $return_result, $tags );
	}
	

	public function uacf7_form_hidden_fields( $hidden_fields ) {

		$current_form = wpcf7_get_current_contact_form();
		$current_form_id = $current_form->id();

		return array_merge( $hidden_fields, array(
			'_uacf7_hidden_conditional_fields' => '',
		) );
	}

	public function remove_hidden_post_data( $posted_data ) {

		$this->set_hidden_fields_arrays( $posted_data );

		foreach ( $this->hidden_fields as $name => $value ) {
			unset( $posted_data[ $name ] );
		}

		return $posted_data;

	}

	public function set_hidden_fields_arrays( $posted_data = false ) {

		if ( ! $posted_data ) {
			$posted_data = WPCF7_Submission::get_instance()->get_posted_data();
		}
		if ( isset( $posted_data['_uacf7_hidden_conditional_fields'] ) ) {
			$hidden_fields = json_decode( stripslashes( $posted_data['_uacf7_hidden_conditional_fields'] ) );
		} else {
			$hidden_fields = [];
		}
		if ( is_array( $hidden_fields ) && count( $hidden_fields ) > 0 ) {
			foreach ( $hidden_fields as $field ) {

				$this->hidden_fields[] = $field;
			}
		}

	}

	public function skip_hidden_checkbox_required($result, $tag){

		if ( ! count( $result->get_invalid_fields() ) ) {
			return $result;
		}
		if ( isset( $_POST ) ) {
			$this->set_hidden_fields_arrays( $_POST );
		}

		$invalid_field_keys = array_keys( $result->get_invalid_fields() );
		if ( isset( $this->hidden_fields ) && is_array( $this->hidden_fields ) && in_array( $tag->name. '[]', $this->hidden_fields ) ) {

			return new WPCF7_Validation();
		}

		return $result;

	}

	/* Skip validation for hidden file field */
	function skip_validation_for_hidden_file_field( $result, $tag, $args = [] ) {
		
		if ( ! count( $result->get_invalid_fields() ) ) {
			return $result;
		}
		if ( isset( $_POST ) ) {
			$this->set_hidden_fields_arrays( $_POST );
		}

		$invalid_field_keys = array_keys( $result->get_invalid_fields() );

		if ( isset( $this->hidden_fields ) && is_array( $this->hidden_fields ) && in_array( $tag->name, $this->hidden_fields ) && count( $invalid_field_keys ) == 1 ) {
			return new WPCF7_Validation();
		}

		return $result;
	}

	public function uacf7_config_validator_validate( WPCF7_ConfigValidator $wpcf7_config_validator ) {

		$cf = $wpcf7_config_validator->contact_form();
		$all_group_tags = $cf->scan_form_tags();

		foreach ( $wpcf7_config_validator->collect_error_messages() as $err_type => $err ) {

			$parts = explode( '.', $err_type );

			$property = $parts[0];

			if ( $property == 'form' )
				continue;

			$sub_prop = $parts[1];
			$prop_val = $cf->prop( $property )[ $sub_prop ];

			if ( strpos( $prop_val, '[/' ) !== false ) {
				$wpcf7_config_validator->remove_error( $err_type, WPCF7_ConfigValidator::error_invalid_mailbox_syntax );
				continue;
			}
		}

		return new WPCF7_ConfigValidator( $wpcf7_config_validator->contact_form() );
	}


	/**
	 * uacf7_conditional_mail_properties Function
	 * @author Sydur Rahman
	 * @since 3.2.1
	 */
	public function uacf7_conditional_mail_properties( $WPCF7_ContactForm ) {
		$wpcf7 = WPCF7_ContactForm::get_current();
		$submission = WPCF7_Submission::get_instance();

		// Get the conditional fields
		// $uacf7_conditions = get_post_meta( $wpcf7->id(), 'conditional', true );
		$uacf7_conditions = uacf7_get_form_option( $wpcf7->id(), 'conditional' );
		$conditional_repeater = isset( $uacf7_conditions['conditional_repeater'] ) ? $uacf7_conditions['conditional_repeater'] : array();

		$posted_data = $submission->get_posted_data();
		$form_tags = $submission->get_contact_form()->scan_form_tags();

		// Set the email body in the mail properties
		$properties = $submission->get_contact_form()->get_properties();

		// Get the email body
		$mail_body   = $properties['mail']['body'];
		$mail_body_2 = $properties['mail_2']['body'];


		if ( $submission && is_array( $conditional_repeater ) && ! empty( $conditional_repeater ) ) {
			
			// Loop through the conditional fields
			foreach ( $conditional_repeater as $key => $condition ) {
				$uacf7_cf_hs = $condition['uacf7_cf_hs'];
				$uacf7_cf_group = $condition['uacf7_cf_group'];
				$uacf7_cf_conditions_for = $condition['uacf7_cf_condition_for'];
				$uacf7_cf_conditions = $condition['uacf7_cf_conditions'] ?? [];
				$condition_status = [];
				
				// Check if the conditional field is hidden or shown
				foreach ( $uacf7_cf_conditions as $key => $value ) {
					$uacf7_cf_val = $value['uacf7_cf_val'];
					$uacf7_cf_operator = $value['uacf7_cf_operator'];
					$uacf7_cf_tn = rtrim($value['uacf7_cf_tn'], '[]');
					
					// $posted_value = is_array( $posted_data[ $uacf7_cf_tn ] ) && in_array( $uacf7_cf_val, $posted_data[ $uacf7_cf_tn ] ) ? $uacf7_cf_val : $posted_data[ $uacf7_cf_tn ];
					@$posted_value = is_array($posted_data[$uacf7_cf_tn]) ? implode(',', $posted_data[$uacf7_cf_tn]) : $posted_data[$uacf7_cf_tn];
				
					// Condition for Equal  
					if ( $uacf7_cf_operator == 'equal' && $posted_value == $uacf7_cf_val ) {
						$condition_status[] = 'true';
					}
					// Condition for Not Equal
					else if ( $uacf7_cf_operator == 'not_equal' && $posted_value != $uacf7_cf_val ) {

						$condition_status[] = 'true';
					}
					// Condition for Greater than
					else if ( $uacf7_cf_operator == 'greater_than' && $posted_value > $uacf7_cf_val ) {
						$condition_status[] = 'true';
					}
					// Condition for Less than
					else if ( $uacf7_cf_operator == 'less_than' && $posted_value < $uacf7_cf_val ) {
						$condition_status[] = 'true';
					}
					// Condition for Greater than or equal to
					else if ( $uacf7_cf_operator == 'greater_than_or_equal_to' && $posted_value >= $uacf7_cf_val ) {
						$condition_status[] = 'true';
					}
					// Condition for Less than or equal to
					else if ( $uacf7_cf_operator == 'less_than_or_equal_to' && $posted_value <= $uacf7_cf_val ) {
						$condition_status[] = 'true';
					}
					// Condition for Starts With
					else if ( $uacf7_cf_operator == 'starts_with' && substr( $posted_value, 0, strlen( $uacf7_cf_val ) ) === $uacf7_cf_val ) {
						$condition_status[] = 'true';
					} 
					// Condition for Ends With
					else if ( $uacf7_cf_operator == 'ends_with' && substr( $posted_value, -strlen( $uacf7_cf_val ) ) === $uacf7_cf_val ) {
						$condition_status[] = 'true';
					}     
					// Condition for Contains
					else if ( $uacf7_cf_operator == 'contains' && strpos( $posted_value, $uacf7_cf_val ) !== false ) {
						$condition_status[] = 'true';
					}     
					// Condition for Excludes (does not contain)
					else if ( $uacf7_cf_operator == 'does_not_contain' && strpos( $posted_value, $uacf7_cf_val ) === false ) {
						$condition_status[] = 'true';
					}else {
						$condition_status[] = 'false';
					}
				}

				// Check if the conditions for all  
				if ( $uacf7_cf_conditions_for == 'all' ) {
					if ( ! in_array( 'false', $condition_status ) ) {
						if ( $uacf7_cf_hs == 'show' ) {
							$mail_body = preg_replace( '/\[' . $uacf7_cf_group . '\]/s', '', $mail_body );
							$mail_body = preg_replace( '/\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body );

							// Mail 2 
							$mail_body_2 = preg_replace( '/\[' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
							$mail_body_2 = preg_replace( '/\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
						}else{
							$mail_body = preg_replace( '/\[' . $uacf7_cf_group . '\].*?\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body );

							// Mail 2 
							$mail_body_2 = preg_replace( '/\[' . $uacf7_cf_group . '\].*?\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
						}
					}else if($uacf7_cf_hs == 'hide' ){
						$mail_body = preg_replace( '/\[' . $uacf7_cf_group . '\]/s', '', $mail_body );
						$mail_body = preg_replace( '/\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body );

						// Mail 2 
						$mail_body_2 = preg_replace( '/\[' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
						$mail_body_2 = preg_replace( '/\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
					 }else {
						$mail_body = preg_replace( '/\[' . $uacf7_cf_group . '\].*?\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body );

						// Mail 2 
						$mail_body_2 = preg_replace( '/\[' . $uacf7_cf_group . '\].*?\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
					}
				}

				// Check if the conditions for any 
				if ( $uacf7_cf_conditions_for == 'any' ) {
					
					$normalized_conditions = array_map(fn($v) => $v === 'true', (array) $condition_status);

					if ( in_array(true, $normalized_conditions, true) ) {
						if ( $uacf7_cf_hs == 'show' ) {
							$mail_body = preg_replace( '/\[' . $uacf7_cf_group . '\]/s', '', $mail_body );
							$mail_body = preg_replace( '/\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body );

							// Mail 2 
							$mail_body_2 = preg_replace( '/\[' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
							$mail_body_2 = preg_replace( '/\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
						}else {
							$mail_body = preg_replace( '/\[' . $uacf7_cf_group . '\].*?\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body );
	
							// Mail 2 
							$mail_body_2 = preg_replace( '/\[' . $uacf7_cf_group . '\].*?\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
						}
					}else if($uacf7_cf_hs == 'hide' ){
						$mail_body = preg_replace( '/\[' . $uacf7_cf_group . '\]/s', '', $mail_body );
						$mail_body = preg_replace( '/\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body );

						// Mail 2 
						$mail_body_2 = preg_replace( '/\[' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
						$mail_body_2 = preg_replace( '/\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
					} else {
						$mail_body = preg_replace( '/\[' . $uacf7_cf_group . '\].*?\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body );

						// Mail 2 
						$mail_body_2 = preg_replace( '/\[' . $uacf7_cf_group . '\].*?\[\/' . $uacf7_cf_group . '\]/s', '', $mail_body_2 );
					}
				}

			}

			// Set the email body in the mail properties
			$properties['mail']['body'] = $mail_body;

			// Mail 2
			$properties['mail_2']['body'] = $mail_body_2;

			$submission->get_contact_form()->set_properties( $properties );

		}
	}

	public function uacf7_condition_replace_pdf( $pdf_content, $id, $contact_form_data ) {
		$uacf7_conditions = uacf7_get_form_option( $id, 'conditional' );
		$conditional_repeater = isset( $uacf7_conditions['conditional_repeater'] ) ? $uacf7_conditions['conditional_repeater'] : array();

		// ✅ Use raw posted data from submission, not the normalized $contact_form_data
		$submission = WPCF7_Submission::get_instance();
		$posted_data = $submission ? $submission->get_posted_data() : (array) $contact_form_data;

		if ( is_array( $conditional_repeater ) && ! empty( $conditional_repeater ) ) {
			foreach ( $conditional_repeater as $key => $condition ) {

				$uacf7_cf_hs = $condition['uacf7_cf_hs'];
				$uacf7_cf_group = $condition['uacf7_cf_group'];
				$uacf7_cf_conditions_for = $condition['uacf7_cf_condition_for'];
				$uacf7_cf_conditions = $condition['uacf7_cf_conditions'];
				$condition_status = [];

				foreach ( $uacf7_cf_conditions as $c ) {
					$uacf7_cf_val = $c['uacf7_cf_val'];
					$uacf7_cf_operator = $c['uacf7_cf_operator'];
					$uacf7_cf_tn = rtrim( $c['uacf7_cf_tn'], '[]' );

					// ✅ Normalize posted value
					$posted_value = isset($posted_data[$uacf7_cf_tn])
						? ( is_array($posted_data[$uacf7_cf_tn]) ? implode(',', $posted_data[$uacf7_cf_tn]) : $posted_data[$uacf7_cf_tn] )
						: '';

					// ✅ Same condition checks as your mail function
					if ( $uacf7_cf_operator == 'equal' && $posted_value == $uacf7_cf_val ) {
						$condition_status[] = 'true';
					}
					elseif ( $uacf7_cf_operator == 'not_equal' && $posted_value != $uacf7_cf_val ) {
						$condition_status[] = 'true';
					}
					elseif ( $uacf7_cf_operator == 'contains' && strpos($posted_value, $uacf7_cf_val) !== false ) {
						$condition_status[] = 'true';
					}
					elseif ( $uacf7_cf_operator == 'does_not_contain' && strpos($posted_value, $uacf7_cf_val) === false ) {
						$condition_status[] = 'true';
					}
					else {
						$condition_status[] = 'false';
					}
				}

				// Replace content (same as your mail logic)
				if ( $uacf7_cf_conditions_for == 'all' && ! in_array('false', $condition_status) ) {
					if ( $uacf7_cf_hs == 'show' ) {
						$pdf_content = preg_replace( '/\['.$uacf7_cf_group.'\]/s', '', $pdf_content );
						$pdf_content = preg_replace( '/\[\/'.$uacf7_cf_group.'\]/s', '', $pdf_content );
					}
				} elseif ( $uacf7_cf_conditions_for == 'any' && in_array('true', $condition_status) ) {
					if ( $uacf7_cf_hs == 'show' ) {
						$pdf_content = preg_replace( '/\['.$uacf7_cf_group.'\]/s', '', $pdf_content );
						$pdf_content = preg_replace( '/\[\/'.$uacf7_cf_group.'\]/s', '', $pdf_content );
					}
				} else {
					$pdf_content = preg_replace( '/\['.$uacf7_cf_group.'\].*?\[\/'.$uacf7_cf_group.'\]/s', '', $pdf_content );
				}
			}
		}

		return $pdf_content;
	}



}
new UACF7_CF();