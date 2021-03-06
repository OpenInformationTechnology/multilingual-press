<?php # -*- coding: utf-8 -*-

interface Mlp_Module_Mapper_Interface {

	/**
	 * Constructor
	 *
	 * @param Mlp_Module_Manager_Interface $modules
	 */
	public function __construct( Mlp_Module_Manager_Interface $modules );

	/**
	 * Save module options.
	 *
	 * @return	void
	 */
	public function update_modules();

	/**
	 * Wrapper for the same method of $modules.
	 *
	 * @param unknown_type $status
	 */
	public function get_modules( $status = 'all' );

	/**
	 * Get name for nonce action parameter.
	 *
	 * @return string
	 */
	public function get_nonce_action();
}