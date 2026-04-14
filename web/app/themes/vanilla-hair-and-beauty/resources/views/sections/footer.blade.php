
@php

    global $wpdb;

    $slug = 'Footer';

    $footer = $wpdb->get_row($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_type = 'wp_block'
         AND post_status IN ('publish', 'inherit')
         AND post_title = %s",
        $slug
    ));

    $footerId = $footer ? (int) $footer->ID : 0;

    if (! $footerId ) {
        $footer = $wpdb->get_row($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts}
             WHERE post_type = 'wp_block'
             AND post_status IN ('publish', 'inherit')
             AND post_title = %s",
            'Footer'
        ));
        $footerId = $footer ? (int) $footer->ID : 0;
    }

    if ($footerId) {
        $block = [
            'blockName'    => 'core/block',
            'attrs'        => ['ref' => $footerId],
            'innerHTML'    => '',
            'innerContent' => [],
            'innerBlocks'  => [],
        ];

        $footerContent = render_block($block);
    }
@endphp

@if ($footerId)
  <footer class="content-info">
    {!! $footerContent !!}
  </footer>
@endif


