<?php
/**
 * Singleton class trait.
 *
 * @package LifterLMS/Traits
 *
 * @since [version]
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

/**
 * LLMS_REST_Trait_Singleton class.
 *
 * @since [version]
 */
trait LLMS_Trait_Singleton {

	/**
	 * Singleton instance of the class.
	 *
	 * @var obj
	 */
	private static $instance = null;

	/**
	 * Private Constructor.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Singleton Instance of the class.
	 *
	 * @since [version]
	 *
	 * @return obj Instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

}
