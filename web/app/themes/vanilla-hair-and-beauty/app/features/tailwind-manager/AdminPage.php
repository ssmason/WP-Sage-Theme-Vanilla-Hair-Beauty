<?php

declare(strict_types=1);

namespace App\Features\TailwindManager;

/**
 * Registers the Tailwind Manager settings page and renders its HTML.
 *
 * Reads the current @source inline() value via CssEditor to pre-populate
 * the textarea. All build interactions are handled via AJAX.
 */
class AdminPage {

	/**
	 * Settings page slug.
	 */
	public const MENU_SLUG = 'tailwind-manager';

	/**
	 * Nonce action for the save form.
	 */
	public const NONCE_ACTION_SAVE = 'tailwind_manager_save';

	/**
	 * Nonce field name for the save form.
	 */
	public const NONCE_NAME_SAVE = 'tailwind_manager_save_nonce';

	/**
	 * Nonce action for the build AJAX request.
	 */
	public const NONCE_ACTION_BUILD = 'tailwind_manager_build';

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
	 * Registers the page under the Settings menu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_options_page(
			__( 'Tailwind Manager', 'vanilla-hair-and-beauty' ),
			__( 'Tailwind Manager', 'vanilla-hair-and-beauty' ),
			'manage_options',
			self::MENU_SLUG,
			array( $this, 'render' )
		);
	}

	/**
	 * Renders the full settings page.
	 *
	 * @return void
	 */
	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'vanilla-hair-and-beauty' ) );
		}

		$current_value = $this->css_editor->get_inline_source();
		$saved         = isset( $_GET['saved'] ) && '1' === sanitize_key( $_GET['saved'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$build_nonce   = wp_create_nonce( self::NONCE_ACTION_BUILD );
		$ajax_url      = admin_url( 'admin-ajax.php' );

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Tailwind Manager', 'vanilla-hair-and-beauty' ); ?></h1>

			<?php if ( $saved ) : ?>
				<div class="notice notice-success is-dismissible">
					<p><?php esc_html_e( 'Settings saved.', 'vanilla-hair-and-beauty' ); ?></p>
				</div>
			<?php endif; ?>

			<form method="post" action="">
				<?php wp_nonce_field( self::NONCE_ACTION_SAVE, self::NONCE_NAME_SAVE ); ?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="tailwind_inline_source">
								<?php esc_html_e( '@source inline() classes', 'vanilla-hair-and-beauty' ); ?>
							</label>
						</th>
						<td>
							<textarea
								id="tailwind_inline_source"
								name="tailwind_inline_source"
								rows="4"
								cols="80"
								class="large-text code"
							><?php echo esc_textarea( $current_value ); ?></textarea>
							<p class="description">
								<?php esc_html_e( 'Space-separated Tailwind utility classes to force-include via @source inline(). Changes are written directly to resources/css/app.css.', 'vanilla-hair-and-beauty' ); ?>
							</p>
						</td>
					</tr>
				</table>

				<p class="submit">
					<button type="submit" name="tailwind_manager_save" value="1" class="button button-primary">
						<?php esc_html_e( 'Save', 'vanilla-hair-and-beauty' ); ?>
					</button>
				</p>
			</form>

			<hr />

			<h2><?php esc_html_e( 'Build', 'vanilla-hair-and-beauty' ); ?></h2>

			<p class="description">
				<?php esc_html_e( 'Runs npm run build from the theme directory. Output is streamed below.', 'vanilla-hair-and-beauty' ); ?>
			</p>

			<p>
				<button id="tailwind-build-btn" class="button button-secondary">
					<?php esc_html_e( 'Run npm run build', 'vanilla-hair-and-beauty' ); ?>
				</button>
			</p>

			<pre id="tailwind-build-output" hidden></pre>
		</div>

		<script>
		( function () {
			var btn    = document.getElementById( 'tailwind-build-btn' );
			var output = document.getElementById( 'tailwind-build-output' );

			var labelIdle    = <?php echo wp_json_encode( __( 'Run npm run build', 'vanilla-hair-and-beauty' ) ); ?>;
			var labelRunning = <?php echo wp_json_encode( __( 'Building\u2026', 'vanilla-hair-and-beauty' ) ); ?>;
			var ajaxUrl      = <?php echo wp_json_encode( $ajax_url ); ?>;
			var nonce        = <?php echo wp_json_encode( $build_nonce ); ?>;

			btn.addEventListener( 'click', function () {
				btn.disabled    = true;
				btn.textContent = labelRunning;
				output.hidden   = false;
				output.textContent = '';

				var body = new FormData();
				body.append( 'action', 'tailwind_manager_build' );
				body.append( 'nonce', nonce );

				fetch( ajaxUrl, { method: 'POST', body: body, credentials: 'same-origin' } )
					.then( function ( response ) { return response.json(); } )
					.then( function ( json ) {
						output.textContent = json.data ? json.data.output : '';
					} )
					.catch( function ( error ) {
						output.textContent = 'Fetch error: ' + error.message;
					} )
					.finally( function () {
						btn.disabled    = false;
						btn.textContent = labelIdle;
					} );
			} );
		}() );
		</script>
		<?php
	}
}
