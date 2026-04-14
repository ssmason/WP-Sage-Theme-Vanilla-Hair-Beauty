@extends('layouts.app')

@section('content')
@php

    global $wpdb;


    $slug = 'Archive Team';

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
            'Archive Team'
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

        $teamContent = render_block($block);
    }
@endphp

@if ($patternId)
  {!! $teamContent !!}
@else
  <div class="mx-auto max-w-[1100px] px-4 py-8">
    <p>{{ __('Archive Team pattern not found. Create a synced pattern titled "Archive Team" or "Archive Team".', 'sage') }}</p>
  </div>
@endif
@endsection
