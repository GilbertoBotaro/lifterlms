<?php
/**
 * LifterLMS file loader.
 *
 * @package LifterLMS/Classes
 *
 * @since [version]
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

/**
 * LLMS_Loader
 *
 * @since [version]
 */
class LLMS_Loader {

	/**
	 * Constructor
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function __construct() {

		spl_autoload_register( array( $this, 'autoload' ) );

		$this->includes_libraries();

		$this->includes();

		if ( is_admin() ) {
			$this->includes_admin();
		} else {
			$this->includes_frontend();
		}

	}

	/**
	 * Auto-load LLMS classes.
	 *
	 * @since 1.0.0
	 * @since 3.15.0 Unknown.
	 * @since [version] Moved from `LifterLMS` class.
	 *
	 * @param string $class Class name being called.
	 * @return void
	 */
	public function autoload( $class ) {

		$class = strtolower( $class );

		$path    = null;
		$fileize = str_replace( '_', '.', $class );
		$file    = 'class.' . $fileize . '.php';

		if ( strpos( $class, 'llms_meta_box' ) === 0 ) {
			$path = LLMS_PLUGIN_DIR . '/includes/admin/post-types/meta-boxes/';
		} elseif ( strpos( $class, 'llms_widget_' ) === 0 ) {
			$path = LLMS_PLUGIN_DIR . '/includes/widgets/';
		} elseif ( strpos( $class, 'llms_integration_' ) === 0 ) {
			$path = LLMS_PLUGIN_DIR . '/includes/integrations/';
		} elseif ( strpos( $class, 'llms_controller_' ) === 0 ) {
			$path = LLMS_PLUGIN_DIR . '/includes/controllers/';
		} elseif ( 0 === strpos( $class, 'llms_abstract' ) ) {
			$path = LLMS_PLUGIN_DIR . '/includes/abstracts/';
			$file = $fileize . '.php';
		} elseif ( 0 === strpos( $class, 'llms_interface' ) ) {
			$path = LLMS_PLUGIN_DIR . '/includes/interfaces/';
			$file = $fileize . '.php';
		} elseif ( strpos( $class, 'llms_' ) === 0 ) {
			$path = LLMS_PLUGIN_DIR . '/includes/';
		}

		if ( $path && is_readable( $path . $file ) ) {
			require_once $path . $file;
			return;
		}
	}

	/**
	 * Includes that are included everywhere
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function includes() {

		// Abstract classes that are not caught by the autoloader.
		require_once LLMS_PLUGIN_DIR . 'includes/abstracts/abstract.llms.database.query.php';
		require_once LLMS_PLUGIN_DIR . 'includes/abstracts/abstract.llms.payment.gateway.php';
		require_once LLMS_PLUGIN_DIR . 'includes/abstracts/abstract.llms.post.model.php';
		require_once LLMS_PLUGIN_DIR . 'includes/abstracts/llms-abstract-session-data.php';
		require_once LLMS_PLUGIN_DIR . 'includes/abstracts/llms-abstract-session-database-handler.php';

		// Models.
		require_once LLMS_PLUGIN_DIR . 'includes/models/class-llms-event.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.access.plan.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.add-on.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.coupon.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.course.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.instructor.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.lesson.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.membership.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.notification.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.order.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.post.instructors.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.product.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.question.choice.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.question.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.quiz.attempt.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.quiz.attempt.question.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.quiz.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.section.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.student.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.student.quizzes.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.transaction.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.user.achievement.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.user.certificate.php';
		require_once LLMS_PLUGIN_DIR . 'includes/models/model.llms.user.postmeta.php';

		// Functions.
		require_once LLMS_PLUGIN_DIR . 'includes/llms.functions.core.php';

		// Classes.
		require_once LLMS_PLUGIN_DIR . 'includes/class-llms-events.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class-llms-events-core.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class-llms-events-query.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class-llms-grades.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class-llms-mime-type-extractor.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class-llms-sessions.php';

		// Classes (files to be renamed).
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class.llms.admin.assets.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.ajax.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.ajax.handler.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.cache.helper.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.comments.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.date.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.gateway.manual.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.hasher.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.install.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.l10n.js.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.lesson.handler.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.nav.menus.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.oembed.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.person.handler.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.playnice.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.post.handler.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.post.relationships.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.post-types.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.query.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.query.quiz.attempt.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.query.user.postmeta.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.question.types.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.review.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.session.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.sidebars.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.student.dashboard.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.student.query.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.user.permissions.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.view.manager.php';

		// Controllers.
		require_once LLMS_PLUGIN_DIR . 'includes/controllers/class.llms.controller.achievements.php';
		require_once LLMS_PLUGIN_DIR . 'includes/controllers/class.llms.controller.certificates.php';
		require_once LLMS_PLUGIN_DIR . 'includes/controllers/class.llms.controller.lesson.progression.php';
		require_once LLMS_PLUGIN_DIR . 'includes/controllers/class.llms.controller.orders.php';
		require_once LLMS_PLUGIN_DIR . 'includes/controllers/class.llms.controller.quizzes.php';

		// Form controllers.
		require_once LLMS_PLUGIN_DIR . 'includes/forms/controllers/class.llms.controller.account.php';
		require_once LLMS_PLUGIN_DIR . 'includes/forms/controllers/class.llms.controller.login.php';
		require_once LLMS_PLUGIN_DIR . 'includes/forms/controllers/class.llms.controller.registration.php';

		// Hooks.
		require_once LLMS_PLUGIN_DIR . 'includes/llms.template.hooks.php';

		// Notifications.
		require_once LLMS_PLUGIN_DIR . 'includes/notifications/class.llms.notifications.php';
		require_once LLMS_PLUGIN_DIR . 'includes/notifications/class.llms.notifications.query.php';

		// Privacy components.
		require_once LLMS_PLUGIN_DIR . 'includes/privacy/class-llms-privacy.php';

		// Processors.
		require_once LLMS_PLUGIN_DIR . 'includes/processors/class.llms.processors.php';

		// Shortcodes.
		require_once LLMS_PLUGIN_DIR . 'includes/shortcodes/class.llms.shortcode.checkout.php';
		require_once LLMS_PLUGIN_DIR . 'includes/shortcodes/class.llms.shortcode.my.account.php';
		require_once LLMS_PLUGIN_DIR . 'includes/shortcodes/class.llms.shortcodes.php';

		// Theme support.
		require_once LLMS_PLUGIN_DIR . 'includes/theme-support/class-llms-theme-support.php';

		// Widgets.
		require_once LLMS_PLUGIN_DIR . 'includes/widgets/class.llms.widget.php';
		require_once LLMS_PLUGIN_DIR . 'includes/widgets/class.llms.widgets.php';

	}

	/**
	 * Includes that are required only on the admin panel
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function includes_admin() {

		// This should be an abstract.
		require_once LLMS_PLUGIN_DIR . 'includes/admin/post-types/meta-boxes/fields/llms.class.meta.box.fields.php';

		// This should be moved to the interfaces directory.
		require_once LLMS_PLUGIN_DIR . 'includes/admin/post-types/meta-boxes/fields/llms.interface.meta.box.field.php';

		// Abstracts.
		require_once LLMS_PLUGIN_DIR . 'includes/abstracts/abstract.llms.admin.metabox.php';
		require_once LLMS_PLUGIN_DIR . 'includes/abstracts/abstract.llms.admin.table.php';

		// Functions.
		require_once LLMS_PLUGIN_DIR . 'includes/admin/llms.functions.admin.php';

		// Admin classes.
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class-llms-admin-export-download.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class-llms-admin-review.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class-llms-admin-users-table.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class-llms-sendwp.php';

		// Admin classes (files to be renamed).
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class.llms.admin.import.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class.llms.admin.menus.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class.llms.admin.notices.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class.llms.admin.notices.core.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class.llms.admin.post-types.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class.llms.admin.reviews.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class.llms.admin.user.custom.fields.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/class.llms.student.bulk.enroll.php';

		// Post types.
		require_once LLMS_PLUGIN_DIR . 'includes/admin/post-types/class.llms.post.tables.php';
		require_once LLMS_PLUGIN_DIR . 'includes/admin/post-types/tables/class.llms.table.student.management.php';

		// Classes.
		require_once LLMS_PLUGIN_DIR . 'includes/class-llms-staging.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.dot.com.api.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.generator.php';

		// Controllers.
		require_once LLMS_PLUGIN_DIR . 'includes/controllers/class.llms.controller.admin.quiz.attempts.php';

		// Reporting.
		require_once LLMS_PLUGIN_DIR . 'includes/admin/reporting/widgets/class.llms.analytics.widget.ajax.php';

		// Load setup wizard conditionally.
		if ( 'llms-setup' === llms_filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) ) {
			require_once LLMS_PLUGIN_DIR . 'includes/admin/class.llms.admin.setup.wizard.php';
		}

	}

	/**
	 * Include libraries
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function includes_libraries() {

		// Block library.
		if ( function_exists( 'has_blocks' ) && ! defined( 'LLMS_BLOCKS_VERSION' ) ) {
			require_once LLMS_PLUGIN_DIR . 'vendor/lifterlms/lifterlms-blocks/lifterlms-blocks.php';
		}

		// Rest API.
		if ( ! class_exists( 'LifterLMS_REST_API' ) ) {
			require_once LLMS_PLUGIN_DIR . 'vendor/lifterlms/lifterlms-rest/lifterlms-rest.php';
		}

		// Action Scheduler.
		require_once LLMS_PLUGIN_DIR . 'vendor/woocommerce/action-scheduler/action-scheduler.php';

	}

	/**
	 * Includes that are required only on the frontend
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function includes_frontend() {

		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.frontend.assets.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.https.php';
		require_once LLMS_PLUGIN_DIR . 'includes/class.llms.template.loader.php';

		// Form controllers.
		require_once LLMS_PLUGIN_DIR . 'includes/forms/frontend/class.llms.frontend.forms.php';
		require_once LLMS_PLUGIN_DIR . 'includes/forms/frontend/class.llms.frontend.password.php';

	}


}

return new LLMS_Loader();