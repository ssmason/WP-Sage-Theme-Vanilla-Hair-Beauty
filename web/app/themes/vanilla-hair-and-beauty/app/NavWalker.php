<?php

declare(strict_types=1);

namespace App;

/**
 * Custom navigation walker with Tailwind classes.
 */
class NavWalker extends \Walker_Nav_Menu
{
    /**
     * Start the element output.
     *
     * @param string        $output Passed by reference.
     * @param \WP_Post      $item   The current menu item.
     * @param int           $depth  Depth of menu item.
     * @param \stdClass     $args   An object of wp_nav_menu() arguments.
     * @param int           $id     Current item ID.
     * @return void
     */
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ): void
    {
        $is_current = in_array( 'current-menu-item', (array) $item->classes, true );

        $link_classes = implode( ' ', array_filter( [
            'font-sans text-[15px] font-light tracking-wide transition-colors duration-200',
            $is_current ? 'text-terra' : 'text-mid hover:text-terra',
        ] ) );

        $output .= sprintf(
            '<li class="list-none"><a href="%s" class="%s"%s>%s</a></li>',
            esc_url( $item->url ),
            esc_attr( $link_classes ),
            $item->target ? ' target="' . esc_attr( $item->target ) . '"' : '',
            esc_html( $item->title )
        );
    }
}
