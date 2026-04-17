<?php

declare(strict_types=1);

namespace App\Features\TailwindManager;

/**
 * Handles the wp_ajax_tailwind_manager_build AJAX request.
 *
 * Performs the capability check and nonce verification, then delegates
 * execution to BuildRunner and returns the result as JSON.
 */
class BuildAjaxHandler {

	/**
	 * AJAX action name.
	 */
	public const ACTION = 'tailwind_manager_build';

	/**
	 * @var BuildRunner
	 */
	private BuildRunner $build_runner;

	/**
	 * Constructor.
	 *
	 * @param BuildRunner $build_runner Injected build runner.
	 */
	public function __construct( BuildRunner $build_runner ) {
		$this->build_runner = $build_runner;
	}

	/**
	 * Processes the AJAX request.
	 *
	 * Sends a JSON response and exits.
	 *
	 * @return void
	 */
	public function handle(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array( 'message' => __( 'You do not have permission to perform this action.', 'vanilla-hair-and-beauty' ) ),
				403
			);
		}

		$raw_nonce = isset( $_POST['nonce'] )
			? sanitize_text_field( wp_unslash( $_POST['nonce'] ) )
			: '';

		if ( ! wp_verify_nonce( $raw_nonce, AdminPage::NONCE_ACTION_BUILD ) ) {
			wp_send_json_error(
				array( 'message' => __( 'Security check failed.', 'vanilla-hair-and-beauty' ) ),
				403
			);
		}

		$result = $this->build_runner->run();

		wp_send_json_success( $result );
	}
}
