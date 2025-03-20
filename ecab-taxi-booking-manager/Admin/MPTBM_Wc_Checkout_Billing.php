<?php
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	/**
	 * Class MPTBM_Wc_Checkout_Billing
	 *
	 * @since 1.0
	 *
	 * */
	if (!class_exists('MPTBM_Wc_Checkout_Billing')) {
		class MPTBM_Wc_Checkout_Billing {
			private $error;
			public function __construct() {
				$this->error = new WP_Error();
				add_action('mptbm_wc_checkout_tab_content', array($this, 'tab_content'), 10, 1);
				add_action('admin_notices', array($this, 'mp_admin_notice'));
			}
			public function tab_content($contents) {
				?>
                <div class="tab-content active" id="mptbm_wc_billing_field_settings">
                    <h2>Woocommerce Billing Fields</h2>
					<?php if (class_exists('MPTBM_Plugin_Pro')): ?>
						<?php do_action('mptbm_wc_checkout_add', 'billing'); ?>
					<?php endif; ?>
                    <!-- <table class="wc_gateways wp-list-table widefat striped"> -->
                    <div>
                        <table class="wc_gateways widefat striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Label</th>
                                <th>Type</th>
                                <th>Placeholder</th>
                                <th>Validations</th>
                                <th>Required</th>
                                <th>Disabled</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach ($contents['billing'] as $key => $checkout_field) : ?>
								<?php $status = '';
								$status = (isset($checkout_field['disabled']) && $checkout_field['disabled'] == '1') ? '' : 'checked'; ?>
                                <tr>
                                    <input id="<?php echo esc_attr(esc_html($key)) ?>" type="hidden" name="<?php echo esc_attr(esc_html($key)) ?>" value="<?php echo esc_attr(esc_html(json_encode(array('name' => $key, 'attributes' => $checkout_field)))) ?>"/>
                                    <td><?php echo esc_html($key); ?></td>
                                    <td><?php echo esc_html(isset($checkout_field['label']) ? $checkout_field['label'] : ''); ?></td>
                                    <td><?php echo esc_html(isset($checkout_field['type']) ? $checkout_field['type'] : ''); ?></td>
                                    <td><?php echo esc_html(isset($checkout_field['placeholder']) ? $checkout_field['placeholder'] : ''); ?></td>
                                    <td><?php echo esc_html(implode(',', (isset($checkout_field['validate']) && is_array($checkout_field['validate'])) ? $checkout_field['validate'] : array())); ?></td>
                                    <td><span class="<?php echo esc_attr(esc_html((isset($checkout_field['required']) && $checkout_field['required'] == '1') ? 'dashicons dashicons-yes tips' : '')); ?>"></span></td>
                                    <td><span class="checkout-disabled <?php echo esc_attr(esc_html((isset($checkout_field['disabled']) && $checkout_field['disabled'] == '1') ? 'dashicons dashicons-yes tips' : '')); ?>"></span></td>
                                    <td>
										<?php if (class_exists('MPTBM_Plugin_Pro')): ?>
											<?php MPTBM_Wc_Checkout_Fields::switch_button($key, 'checkoutSwitchButton', $key, $status, array('key' => 'billing', 'name' => $key)); ?>
											<?php do_action('mptbm_wc_checkout_action', 'billing', $key, $checkout_field); ?>
										<?php else: ?>
											<?php MPTBM_Wc_Checkout_Fields::switch_button($key, 'checkoutSwitchButton', $key, $status, array('key' => 'billing', 'name' => $key)); ?>
										<?php endif; ?>
                                    </td>
                                </tr>
							<?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
				<?php
			}
			public function mp_admin_notice() {
				MPTBM_Wc_Checkout_Fields::mp_error_notice($this->error);
			}
		}
		new MPTBM_Wc_Checkout_Billing();
	}