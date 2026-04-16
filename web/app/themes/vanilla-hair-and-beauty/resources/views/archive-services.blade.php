@extends('layouts.app')

@section('content')



  {{-- Output the Portfolio page content --}}
  @php
    $services_page = get_page_by_path('services');
    if ($services_page) {
      echo apply_filters('the_content', $services_page->post_content);
    }
  @endphp




@php

    global $wpdb;


    $slug = 'Archive Services';

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
            'Archive Services'
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

        $servicesContent = render_block($block);
    }
@endphp

@if ($patternId)
  {!! $servicesContent !!}
@else
  <div class="mx-auto max-w-[1100px] px-4 py-8">
    <p>{{ __('Archive Services pattern not found. Create a synced pattern titled "Archive Services" or "Archive Services".', 'sage') }}</p>
  </div>
@endif
@endsection
