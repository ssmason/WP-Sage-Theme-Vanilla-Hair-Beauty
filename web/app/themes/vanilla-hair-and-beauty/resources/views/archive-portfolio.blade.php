@extends('layouts.app')

@section('content')


  {{-- Output the Portfolio page content --}}
  @php
    $portfolio_page = get_page_by_path('portfolio');
    if ($portfolio_page) {
      echo apply_filters('the_content', $portfolio_page->post_content);
    }
  @endphp




<div class="mx-auto max-w-[1100px] px-4 py-8">

    <div class="wp-block-columns">
      <div class="wp-block-column" style="flex-basis:66.66%">
        @php
            $paged = get_query_var('paged') ?: 1;
            $portfolio = new WP_Query([
                'post_type'      => 'portfolio',
                'posts_per_page' => 10,
                'paged'          => $paged,
            ]);
        @endphp
        <masonry-grid-lanes min-column-width="200" gap="8" ">
          @while($portfolio->have_posts())
              @php $portfolio->the_post() @endphp
              @if(has_post_thumbnail())
              <div>
                <div class="relative">
                  <figure>
                      {!! get_the_post_thumbnail(null, 'large') !!}
                  </figure>

                   <div style="position: absolute !important;
    bottom: 0;
    color: white;" class="!absolute bottom-0 text-white p-4 font-bold">
                      {{ get_the_title() }}
                  </div>
                </div>
                  </div>
              @endif
          @endwhile
        </masonry-grid-lanes>

        @php wp_reset_postdata() @endphp
      </div>

      <div class="wp-block-column px-4 py-8" style="flex-basis:33.33%" >

           @php
              $sidebar_portfolio = new WP_Query([
                  'post_type'      => 'portfolio',
                  'posts_per_page' => 10,
                  'paged'          => $paged,
              ]);
          @endphp
          <ul class="divide-y divide-warm font-sans text-[15px] font-light text-dark mt-4">
              @while($sidebar_portfolio->have_posts())
                  @php ($sidebar_portfolio->the_post())  @endphp
                  <li class="py-2.5">
                      <a href="{{ get_permalink() }}" class="hover:text-terra transition-colors" style="text-decoration:none">{{ get_the_title() }}</a>
                  </li>
              @endwhile
          </ul>

         @if($sidebar_portfolio->max_num_pages > 1)

              <div class="mt-4 flex items-center justify-center gap-2 font-sans text-sm">

                  @if(get_previous_posts_link())
                      {!! get_previous_posts_link('&larr; Previous') !!}
                  @endif

                  @php for ($i = 1; $i <= $sidebar_portfolio->max_num_pages; $i++) : @endphp
                      <a href="{{ get_pagenum_link($i) }}"
                         class="{{ $i === $paged ? 'font-semibold underline' : '' }}">
                          {{ $i }}
                      </a>
                  @php endfor; @endphp

                  @if(get_next_posts_link('', $sidebar_portfolio->max_num_pages))
                      {!! get_next_posts_link('Next &rarr;', $sidebar_portfolio->max_num_pages) !!}
                  @endif
              </div>
          @endif

          @php(wp_reset_postdata())

      </div>
    </div>

</div>
@endsection

