<?php

declare(strict_types=1);

namespace App\Features\TailwindManager;

/**
 * Handles the settings form POST on admin_init.
 *
 * Performs the capability check, nonce verification, input sanitisation,
 * and delegates the write to CssEditor before redirecting.
 */
class SaveHandler {

	/**
	 * @var CssEditor
	 */
	private CssEditor $css_editor;

	/**
	 * Constructor.
	 *
	 * @param CssEditor $css_editor Injected CSS editor.
	 */
	public function __construct( CssEditor $css_editor ) {
		$this->css_editor = $css_editor;
	}

	/**
	 * Processes the form submission when the save button is present in $_POST.
	 *
	 * Exits after redirect on success. Does nothing when the request is not
	 * a Tailwind Manager save submission.
	 *
	 * @return void
	 */
	public function handle(): void {
		if ( ! isset( $_POST['tailwind_manager_save'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'vanilla-hair-and-beauty' ) );
		}

		$raw_nonce = isset( $_POST[ AdminPage::NONCE_NAME_SAVE ] )
			? sanitize_text_field( wp_unslash( $_POST[ AdminPage::NONCE_NAME_SAVE ] ) )
			: '';

		if ( ! wp_verify_nonce( $raw_nonce, AdminPage::NONCE_ACTION_SAVE ) ) {
			wp_die( esc_html__( 'Security check failed.', 'vanilla-hair-and-beauty' ) );
		}

		$value = isset( $_POST['tailwind_inline_source'] )
			? sanitize_text_field( wp_unslash( $_POST['tailwind_inline_source'] ) )
			: '';

		$this->css_editor->update_inline_source( $value );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'  => AdminPage::MENU_SLUG,
					'saved' => '1',
				),
				admin_url( 'options-general.php' )
			)
		);

		exit;
	}
}
