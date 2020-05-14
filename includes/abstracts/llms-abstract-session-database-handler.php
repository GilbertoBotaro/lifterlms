<?php
/**
 * Base session data class
 *
 * @package LifterLMS/Abstracts/Classes
 *
 * @since [version]
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

/**
 * LLMS_Abstract_Session
 *
 * @since [version]
 */
abstract class LLMS_Abstract_Session_Database_Handler extends LLMS_Abstract_Session_Data {

	/**
	 * Cache group name, used for WP caching functions
	 *
	 * @var string
	 */
	protected $cache_group = 'llms_session_id';

	/**
	 * Unprefixed database table name
	 *
	 * @var string
	 */
	protected $table = 'lifterlms_sessions';

	/**
	 * Delete all expired sessions from the database
	 *
	 * This method is the callback function for the `llms_delete_expired_session_data` cron event.
	 *
	 * @since [version]
	 *
	 * @return int
	 */
	public function clean() {

		LLMS_Cache_Helper::invalidate_group( $this->cache_group );

		global $wpdb;
		return $wpdb->query( $wpdb->prepare( "DELETE FROM {$this->get_table_name()} WHERE expires < %s", time() ) );

	}

	/**
	 * Delete a session from the database
	 *
	 * @since [version]
	 *
	 * @param string $id Session key.
	 * @return boolean
	 */
	public function delete( $id ) {

		wp_cache_delete( $this->get_cache_key( $id ), $this->cache_group );

		global $wpdb;
		return (bool) $wpdb->delete(
			$this->get_table_name(),
			array(
				'session_key' => $id,
			)
		);

	}

	/**
	 * Retrieve a prefixed cache key
	 *
	 * @since [version]
	 *
	 * @param string $key Unprefixed cache key.
	 * @return string
	 */
	protected function get_cache_key( $key ) {
		return LLMS_Cache_Helper::get_prefix( $this->cache_group ) . $key;
	}

	/**
	 * Retrieve the prefixed database table name
	 *
	 * @since [version]
	 *
	 * @return string
	 */
	protected function get_table_name() {

		global $wpdb;
		return "{$wpdb->prefix}{$this->table}";

	}

	/**
	 * Save the session to the database
	 *
	 * @since [version]
	 *
	 * @param int $expires Timestamp of the session expiration.
	 * @return boolean
	 */
	public function save( $expires ) {

		// Only save if we have data to save.
		if ( $this->is_clean ) {
			return false;
		}

		global $wpdb;
		$save = $wpdb->query(
			$wpdb->prepare(
				"INSERT INTO {$this->get_table_name()} ( `session_key`, `data`, `expires` ) VALUES ( %s, %s, %d )
				ON DUPLICATE KEY UPDATE `data` = VALUES ( `data` ), `expires` = VALUES ( `expires` )",
				$this->get_id(),
				maybe_serialize( $this->data ),
				$expires
			)
		);

		wp_cache_set( $this->get_cache_key( $this->get_id() ), $this->data, $this->cache_group, $expires - time() );
		$this->is_clean = true;

		return (bool) $save;

	}

	/**
	 * Retrieve session data from the database
	 *
	 * @since [version]
	 *
	 * @param string $key     Session key.
	 * @param array  $default Default value used when no data exists.
	 * @return string|array
	 */
	public function read( $key, $default = array() ) {

		$cache_key = $this->get_cache_key( $key );
		$data      = wp_cache_get( $cache_key, $this->cache_group );

		if ( false === $data ) {

			global $wpdb;

			$data = $wpdb->get_var( $wpdb->prepare( "SELECT `data` FROM {$this->get_table_name()} WHERE `session_key` = %s", $key ) );

			if ( is_null( $data ) ) {
				$data = $default;
			}

			$duration = $this->expires - time();
			if ( 0 < $duration ) {
				wp_cache_set( $cache_key, $data, $this->cache_group, $duration );
			}
		}

		return maybe_unserialize( $data );

	}

}
