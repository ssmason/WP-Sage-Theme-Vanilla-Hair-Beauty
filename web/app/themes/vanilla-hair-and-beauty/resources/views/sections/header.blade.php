@php
    global $wpdb;

    $slug = 'Navigation';

    $header = $wpdb->get_row($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_type = 'wp_block'
         AND post_status IN ('publish', 'inherit')
         AND post_title = %s",
        $slug
    ));

    $headerId = $header ? (int) $header->ID : 0;

    if (! $headerId ) {
        $header = $wpdb->get_row($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts}
             WHERE post_type = 'wp_block'
             AND post_status IN ('publish', 'inherit')
             AND post_title = %s",
            'header_EN'
        ));
        $headerId = $header ? (int) $header->ID : 0;
    }

    if ($headerId) {
        $block = [
            'blockName'    => 'core/block',
            'attrs'        => ['ref' => $headerId],
            'innerHTML'    => '',
            'innerContent' => [],
            'innerBlocks'  => [],
        ];

        $headerContent = render_block($block);
    }
@endphp

@if ($headerId)
<header class="header-theme">
  {!! $headerContent !!}
</header>
@endif
