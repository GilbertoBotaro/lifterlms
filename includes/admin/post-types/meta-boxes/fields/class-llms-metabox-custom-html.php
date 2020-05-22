<?php
/**
 * Meta box Field: Custom HTML
 *
 * @package LifterLMS/Admin/PostTypes/MetaBoxes/Fields/Classes
 *
 * @since Unknown
 * @version Unknown
 */

defined( 'ABSPATH' ) || exit;

/**
 * LLMS_Metabox_Custom_Html_Field class
 *
 * @since Unknown
 */
class LLMS_Metabox_Custom_Html_Field extends LLMS_Abstract_Metabox_Field {

	/**
	 * Class constructor
	 *
	 * @param array $_field Array containing information about field
	 */
	public function __construct( $_field ) {

		$this->field = $_field;
	}

	/**
	 * outputs the Html for the given field
	 *
	 * @return void
	 */
	public function output() {

		global $post;

		parent::output();
		echo $this->field['value'];
		parent::close_output();
	}
}

