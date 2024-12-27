<?php
/**
 * SureEmailsMailSent.
 * php version 5.6
 *
 * @category SureEmailsMailSent
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\SureEmails\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'SureEmailsMailSent' ) ) :

	/**
	 * SureEmailsMailSent
	 *
	 * @category SureEmailsMailSent
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class SureEmailsMailSent {


		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'SureEmails';


		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'sureemails_mail_sent';

		use SingletonLoader;


		/**
		 * Constructor
		 *
		 * @since  1.0.0
		 */
		public function __construct() {
			add_filter( 'sure_trigger_register_trigger', [ $this, 'register' ] );
		}

		/**
		 * Register action.
		 *
		 * @param array $triggers trigger data.
		 * @return array
		 */
		public function register( $triggers ) {

			$triggers[ $this->integration ][ $this->trigger ] = [
				'label'         => __( 'Mail Sent', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'wp_mail_succeeded',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];
			return $triggers;

		}

		/**
		 *  Trigger listener
		 *
		 * @param array $mail_data trigger data.
		 *
		 * @return void
		 */
		public function trigger_listener( $mail_data ) {
			$context = $mail_data;

			AutomationController::sure_trigger_handle_trigger(
				[
					'trigger' => $this->trigger,
					'context' => $context,
				]
			);
		}
	}

	/**
	 * Ignore false positive
	 *
	 * @psalm-suppress UndefinedMethod
	 */
	SureEmailsMailSent::get_instance();

endif;
