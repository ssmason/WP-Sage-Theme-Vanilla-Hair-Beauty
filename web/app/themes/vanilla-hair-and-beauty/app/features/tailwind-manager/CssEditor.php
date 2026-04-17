<?php

declare(strict_types=1);

namespace App\Features\TailwindManager;

/**
 * Reads and writes the @source inline() value in app.css.
 *
 * Uses WP_Filesystem for all file I/O. Has no awareness of HTTP,
 * WordPress hooks, or admin pages — only the CSS file.
 */
class CssEditor {

	/**
	 * Path to app.css relative to the theme root.
	 */
	private const CSS_FILE = 'resources/css/app.css';

	/**
	 * Regex pattern to locate @source inline("...").
	 */
	private const PATTERN = '/@source\s+inline\("([^"]*)"\)/';

	/**
	 * Absolute path to app.css.
	 *
	 * @var string
	 */
	private string $css_path;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->css_path = get_stylesheet_directory() . '/' . self::CSS_FILE;
	}

	/**
	 * Returns the current value inside @source inline("...").
	 *
	 * Returns an empty string when the directive is absent or the file
	 * cannot be read.
	 *
	 * @return string
	 */
	public function get_inline_source(): string {
		$content = $this->read_file();

		if ( null === $content ) {
			return '';
		}

		if ( preg_match( self::PATTERN, $content, $matches ) ) {
			return $matches[1];
		}

		return '';
	}

	/**
	 * Replaces the value inside @source inline("...") with $value and
	 * writes the file back.
	 *
	 * Returns true on success, false when the directive is absent or the
	 * file cannot be written.
	 *
	 * @param string $value Space-separated Tailwind classes.
	 * @return bool
	 */
	public function update_inline_source( string $value ): bool {
		$content = $this->read_file();

		if ( null === $content ) {
			return false;
		}

		if ( ! preg_match( self::PATTERN, $content, $matches ) ) {
			return false;
		}

		$old_directive = $matches[0];
		$new_directive = '@source inline("' . $value . '")';
		$new_content   = str_replace( $old_directive, $new_directive, $content );

		return $this->write_file( $new_content );
	}

	/**
	 * Reads app.css via WP_Filesystem.
	 *
	 * Returns null on failure.
	 *
	 * @return string|null
	 */
	private function read_file(): ?string {
		$filesystem = $this->get_filesystem();

		if ( null === $filesystem ) {
			return null;
		}

		if ( ! $filesystem->exists( $this->css_path ) ) {
			return null;
		}

		$content = $filesystem->get_contents( $this->css_path );

		return false === $content ? null : $content;
	}

	/**
	 * Writes content to app.css via WP_Filesystem.
	 *
	 * @param string $content File contents to write.
	 * @return bool
	 */
	private function write_file( string $content ): bool {
		$filesystem = $this->get_filesystem();

		if ( null === $filesystem ) {
			return false;
		}

		return $filesystem->put_contents( $this->css_path, $content, FS_CHMOD_FILE );
	}

	/**
	 * Initialises and returns the WP_Filesystem object.
	 *
	 * Returns null when initialisation fails.
	 *
	 * @return \WP_Filesystem_Base|null
	 */
	private function get_filesystem(): ?\WP_Filesystem_Base {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! WP_Filesystem() ) {
			return null;
		}

		return $wp_filesystem;
	}
}
