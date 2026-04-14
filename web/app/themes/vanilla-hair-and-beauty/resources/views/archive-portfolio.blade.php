@extends('layouts.app')

@section('content')
@php

    global $wpdb;


    $slug = 'Archive Portfolio';

    $pattern = $wpdb->get_row($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_type = 'wp_block'
         AND post_status IN ('publish', 'inherit')
         AND post_title = %s",
        $slug
    ));

    if (! $pattern) {
        $pattern = $wpdb->get_row($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts}
             WHERE post_type = 'wp_block'
             AND post_status IN ('publish', 'inherit')
             AND post_title = %s",
            'Archive Portfolio'
        ));
    }

    $patternId = $pattern ? (int) $pattern->ID : 0;

    if ($patternId) {
        $block = [
            'blockName'    => 'core/block',
            'attrs'        => ['ref' => $patternId],
            'innerHTML'    => '',
            'innerContent' => [],
            'innerBlocks'  => [],
        ];

        $portfolioContent = render_block($block);
    }
@endphp

@if ($patternId)
  {!! $portfolioContent !!}
@else
  <div class="mx-auto max-w-[1100px] px-4 py-8">
    <p>{{ __('Archive Portfolio pattern not found. Create a synced pattern titled "Archive Portfolio" or "Archive Portfolio".', 'sage') }}</p>
  </div>
@endif
@endsection
