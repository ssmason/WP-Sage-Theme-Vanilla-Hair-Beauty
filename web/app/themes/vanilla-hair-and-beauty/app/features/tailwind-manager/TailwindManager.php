<?php

declare(strict_types=1);

namespace App\Features\TailwindManager;

/**
 * Bootstraps the Tailwind Manager feature.
 *
 * Instantiates all dependencies and wires them to WordPress hooks.
 * Contains no business logic.
 */
class TailwindManager {

	/**
	 * @var AdminPage
	 */
	private AdminPage $admin_page;

	/**
	 * @var SaveHandler
	 */
	private SaveHandler $save_handler;

	/**
	 * @var BuildAjaxHandler
	 */
	private BuildAjaxHandler $build_ajax_handler;

	/**
	 * Constructor.
	 *
	 * Builds the dependency graph.
	 */
	public function __construct() {
		$css_editor               = new CssEditor();
		$build_runner             = new BuildRunner();
		$this->admin_page         = new AdminPage( $css_editor );
		$this->save_handler       = new SaveHandler( $css_editor );
		$this->build_ajax_handler = new BuildAjaxHandler( $build_runner );
	}

	/**
	 * Registers all WordPress hooks for this feature.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this->admin_page, 'register_menu' ) );
		add_action( 'admin_init', array( $this->save_handler, 'handle' ) );
		add_action( 'wp_ajax_' . BuildAjaxHandler::ACTION, array( $this->build_ajax_handler, 'handle' ) );
	}
}
