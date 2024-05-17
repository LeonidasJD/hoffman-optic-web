<?php
/**
 * Singleton class trait.
 *
 * @package YITH/Search/Traits
 */

/**
 * Singleton trait.
 */
trait YITH_WCAS_Trait_Singleton {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 */
	protected static $instance = array();

	/**
	 * The logger
	 *
	 * @var YITH_WCAS_Logger
	 */
	protected $logger;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function __construct() {
		$this->logger = YITH_WCAS_Logger::get_instance();
	}

	/**
	 * Get class instance.
	 *
	 * @return self
	 */
	public static function get_instance() {

		$calledClass = get_called_class();

		if ( ! isset( static::$instance[ $calledClass ] ) ) {
			static::$instance[ $calledClass ] = new $calledClass();
		}

		return static::$instance[ $calledClass ];
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {
	}

	/**
	 * Prevent un-serializing.
	 */
	public function __wakeup() {
		_doing_it_wrong( get_called_class(), 'Unserializing instances of this class is forbidden.', YITH_WCAS_VERSION ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
