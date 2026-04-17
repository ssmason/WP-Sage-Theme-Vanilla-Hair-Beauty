<?php

declare(strict_types=1);

namespace App\Features\TailwindManager;

/**
 * Executes `npm run build` in the theme directory.
 *
 * Has no awareness of HTTP, WordPress hooks, or admin pages.
 * Returns a plain result array; callers decide how to present it.
 */
class BuildRunner {

	/**
	 * Runs `npm run build` from the theme root.
	 *
	 * Returns an associative array with:
	 *   - output  (string)  Combined stdout and stderr.
	 *   - success (bool)    True when the process exited with code 0.
	 *
	 * @return array{output: string, success: bool}
	 */
	public function run(): array {
		$theme_dir   = get_stylesheet_directory();
		$descriptors = array(
			0 => array( 'pipe', 'r' ),
			1 => array( 'pipe', 'w' ),
			2 => array( 'pipe', 'w' ),
		);

		$env = array(
			'PATH' => '/usr/local/bin:/usr/bin:/bin',
			'HOME' => '/root',
		);

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_proc_open
		$process = proc_open( 'npm run build', $descriptors, $pipes, $theme_dir, $env );

		if ( ! is_resource( $process ) ) {
			return array(
				'output'  => __( 'Failed to start the build process.', 'vanilla-hair-and-beauty' ),
				'success' => false,
			);
		}

		fclose( $pipes[0] );

		$stdout = stream_get_contents( $pipes[1] );
		$stderr = stream_get_contents( $pipes[2] );

		fclose( $pipes[1] );
		fclose( $pipes[2] );

		$exit_code = proc_close( $process );

		$output = trim(
			( false !== $stdout ? $stdout : '' ) .
			( false !== $stderr ? "\n" . $stderr : '' )
		);

		return array(
			'output'  => $output,
			'success' => 0 === $exit_code,
		);
	}
}
