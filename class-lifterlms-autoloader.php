<?php
/**
 * LifterLMS class autoloader
 *
 * Yes, we should implement namespaces and use PSR-4 autoloading via composer. Thank you.
 *
 * We didn't all start our plugin yesteday.
 *
 * Some of us remember a time when everyone loved cPanel and refused to upgrade from PHP 5.3.
 *
 * @package LifterLMS/Classes
 *
 * @since [version]
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

class LifterLMS_Autoloader {

	protected $prefix = 'LLMS';
	protected $class_separator = '_';
	protected $file_separator = '-';
	protected $source_dir = 'includes/';
	protected $base_dir = '';
	protected $sub_dirs = array(
		'abstract'    => 'abstracts/',
		'admin'       => 'admin/',
		'integration' => 'integrations/',
		'interface'   => 'interfaces/',
		'model'       => 'models/',
		// 'settings'    => 'admin/settings/',
		'trait'       => 'traits/',
		'widget'      => 'widgets/',
	);
	protected $alias_map = array(
		'LLMS_Admin_Metabox'   => 'LLMS_Abstract_Admin_Metabox',
		'LLMS_Admin_Table'     => 'LLMS_Abstract_Admin_Table',
		'LLMS_Database_Query'  => 'LLMS_Abstract_Database_Query',
		'LLMS_Metabox_Field'   => 'LLMS_Abstract_Metabox_Field',
		'LLMS_Payment_Gateway' => 'LLMS_Abstract_Payment_Gateway',
		'LLMS_Post_Model'      => 'LLMS_Abstract_Post_Model',
		'LLMS_Shortcode'       => 'LLMS_Abstract_Shortcode'
	);

	public function __construct() {

		$this->base_dir = LLMS_PLUGIN_DIR;


		spl_autoload_register( array( $this, 'autoloader' ) );
		$this->register_class_aliases();
		$this->load_files();

		if ( is_admin() ) {
			$this->load_files_admin();
		}

	}

	protected function register_class_aliases() {

		foreach ( $this->alias_map as $deprecated => $replacement ) {
			class_alias( $replacement, $deprecated );
		}

	}

	public function autoloader( $class ) {

		// Only autoload classes that match the specified prefix, eg: `LLMS_`.
		if ( 0 === strpos( $class, $this->prefix . $this->class_separator ) ) {

			// If a deprecated classname is being loaded, we should autoload the replacement instead.
			$class = in_array( $class, array_keys( $this->alias_map ), true ) ? $this->alias_map[ $class ] : $class;

			$path     = $this->source_dir;
			$filename = strtolower( str_replace( $this->class_separator, $this->file_separator, $class ) );

			$parts = explode( $this->file_separator, $filename );
			if ( count( $parts ) >= 2 && in_array( $parts[1], array_keys( $this->sub_dirs ), true ) ) {
				$path .= $this->sub_dirs[ $parts[1] ];
			}

			$file = $this->base_dir . $path . 'class' . $this->file_separator . $filename . '.php';
// var_dump( $file );
			if ( is_readable( $file ) ) {
				require_once $file;
			}

		}

	}

	protected function load_files() {

		$base_path = $this->base_dir . $this->source_dir;

		// Functions.
		require_once $base_path . 'llms-functions-core.php';

		// Classes.
		require_once $base_path . 'notifications/class-llms-notifications.php';
		require_once $base_path . 'notifications/class-llms-notifications-query.php';
		require_once $base_path . 'processors/class-llms-processors.php';
		require_once $base_path . 'shortcodes/class-llms-shortcodes.php';

		// Models
		require_once $base_path . 'models/class-llms-access-plan.php';
		require_once $base_path . 'models/class-llms-add-on.php';
		require_once $base_path . 'models/class-llms-coupon.php';
		require_once $base_path . 'models/class-llms-course.php';
		require_once $base_path . 'models/class-llms-event.php';
		require_once $base_path . 'models/class-llms-instructor.php';
		require_once $base_path . 'models/class-llms-lesson.php';
		require_once $base_path . 'models/class-llms-membership.php';
		require_once $base_path . 'models/class-llms-notification.php';
		require_once $base_path . 'models/class-llms-order.php';
		require_once $base_path . 'models/class-llms-post-instructors.php';
		require_once $base_path . 'models/class-llms-product.php';
		require_once $base_path . 'models/class-llms-question-choice.php';
		require_once $base_path . 'models/class-llms-question.php';
		require_once $base_path . 'models/class-llms-quiz-attempt.php';
		require_once $base_path . 'models/class-llms-quiz-attempt-question.php';
		require_once $base_path . 'models/class-llms-quiz.php';
		require_once $base_path . 'models/class-llms-section.php';
		require_once $base_path . 'models/class-llms-student.php';
		require_once $base_path . 'models/class-llms-student-quizzes.php';
		require_once $base_path . 'models/class-llms-transaction.php';
		require_once $base_path . 'models/class-llms-user-achievement.php';
		require_once $base_path . 'models/class-llms-user-certificate.php';
		require_once $base_path . 'models/class-llms-user-postmeta.php';

	}

	protected function load_files_admin() {

		$base_path = $this->base_dir . $this->source_dir . '/admin/';

		require_once $base_path . 'class-llms-admin-assets.php';
		require_once $base_path . 'class-llms-admin-menus.php';

	}

}

		// $class = strtolower( $class );

		// $path    = null;
		// $fileize = str_replace( '_', '.', $class );
		// $file    = 'class.' . $fileize . '.php';

		// if ( strpos( $class, 'llms_meta_box' ) === 0 ) {
		// 	$path = $this->plugin_path() . '/includes/admin/post-types/meta-boxes/';
		// } elseif ( strpos( $class, 'llms_widget_' ) === 0 ) {
		// 	$path = $this->plugin_path() . '/includes/widgets/';
		// } elseif ( strpos( $class, 'llms_integration_' ) === 0 ) {
		// 	$path = $this->plugin_path() . '/includes/integrations/';
		// } elseif ( strpos( $class, 'llms_controller_' ) === 0 ) {
		// 	$path = $this->plugin_path() . '/includes/controllers/';
		// } elseif ( 0 === strpos( $class, 'llms_abstract' ) ) {
		// 	$path = $this->plugin_path() . '/includes/abstracts/';
		// 	$file = $fileize . '.php';
		// } elseif ( 0 === strpos( $class, 'llms_interface' ) ) {
		// 	$path = $this->plugin_path() . '/includes/interfaces/';
		// 	$file = $fileize . '.php';
		// } elseif ( strpos( $class, 'llms_' ) === 0 ) {
		// 	$path = $this->plugin_path() . '/includes/';
		// }

		// if ( $path && is_readable( $path . $file ) ) {
		// 	include_once $path . $file;
		// 	return;
		// }


return new LifterLMS_Autoloader();
