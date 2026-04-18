@extends('layouts.app')

@section('content')
@while( have_posts() ) @php the_post(); @endphp

  <div class="grid grid-cols-[1fr_300px] min-h-screen max-w-[1140px] mx-auto">

    {{-- ── BANNER ── --}}
    <div class="col-span-full bg-mid relative overflow-hidden flex items-end min-h-[280px] h-[40vh] px-12 pb-10">

      <div class="absolute inset-0"
          style="background-image:repeating-linear-gradient(45deg,rgba(255,255,255,0.02) 0px,rgba(255,255,255,0.02) 1px,transparent 1px,transparent 12px),repeating-linear-gradient(-45deg,rgba(255,255,255,0.02) 0px,rgba(255,255,255,0.02) 1px,transparent 1px,transparent 12px)"></div>
      <div class="absolute bottom-[-180px] right-[-80px] w-[520px] h-[520px] rounded-full border-[60px] border-sand/10"></div>
      <div class="absolute top-[-60px] left-[35%] w-[300px] h-[300px] rounded-full bg-stone/5"></div>

      <div class="relative z-10 flex items-end justify-between w-full">
        <div>
          <p class="font-sans text-[11px] tracking-[0.2em] uppercase text-stone mb-2">
            {{ __( get_field( 'title'), 'vanilla' ) }}
          </p>
          <h1 class="font-serif text-[clamp(2.6rem,6vw,4.4rem)] font-light italic text-warm leading-none">
            {{ __( get_the_title(), 'vanilla' ) }}
          </h1>
          <p class="mt-4 inline-flex items-center gap-2 font-sans text-[12px] text-sand tracking-[0.05em]">
            <span class="block w-6 h-px bg-sand"></span>
            {{ __( 'With us since ' . get_field('joined'), 'vanilla' ) }}
            <span class="block w-6 h-px bg-sand"></span>
          </p>
        </div>

        <div class="self-end flex-shrink-0">
          <div class="w-[200px] h-[260px] rounded-t-[120px] bg-stone/30 flex items-center justify-center">
            <span class="font-serif italic text-5xl text-mid">MB</span>
          </div>
        </div>
      </div>
    </div>

    {{-- ── MAIN ── --}}
    <main class="min-w-0 p-12 flex flex-col gap-12">

      {{-- Pull quote --}}
      <blockquote class="font-serif text-2xl italic font-normal text-stone leading-relaxed relative pl-7 border-l-2 border-sand">
        &ldquo;{{ __( get_field('quote'), 'vanilla' ) }}&rdquo;
      </blockquote>

      {{-- Stats --}}
      @php
        $field_group_key = 'stats';
        $stats_fields = get_field($field_group_key);
      @endphp

      <div class="grid grid-cols-3 border border-stone/15">
        <div class="px-5 py-5 border-r border-stone/15 text-center">
          <div class="font-serif italic text-[2rem] text-stone leading-none">{{ __( $stats_fields['years_in_industry'], 'vanilla'  ) }}</div>
          <div class="mt-1.5 font-sans text-[11px] uppercase tracking-[0.12em] text-stone/60">{{ __( 'Years in the industry', 'vanilla' ) }}</div>
        </div>
        <div class="px-5 py-5 border-r border-stone/15 text-center">
          <div class="font-serif italic text-[2rem] text-stone leading-none">{{ __( $stats_fields['years_at_salon'], 'vanilla' ) }}</div>
          <div class="mt-1.5 font-sans text-[11px] uppercase tracking-[0.12em] text-stone/60">{{ __( 'Years at the salon', 'vanilla' ) }}</div>
        </div>
        <div class="px-5 py-5 text-center">
          <div class="font-serif italic text-[2rem] text-stone leading-none">{{ __( $stats_fields['coffees_consumed'], 'vanilla' ) }}</div>
          <div class="mt-1.5 font-sans text-[11px] uppercase tracking-[0.12em] text-stone/60">{{ __( 'Coffees consumed', 'vanilla' ) }}</div>
        </div>
      </div>

      {{-- Right now --}}
      <section>

        @php $right_now = get_field('right_now') @endphp
        @if ( $right_now )
          <div class="flex items-center gap-3 mb-4">
            <span class="font-sans text-[10px] tracking-[0.2em] uppercase text-terra">{{ __( 'Right now', 'vanilla' ) }}</span>
            <span class="flex-1 h-px bg-stone/15"></span>
          </div>
          <p class="font-sans text-[15px] font-light text-stone leading-relaxed mb-5">
            {{ __( get_field( 'right_now'), 'vanilla' ) }}
          </p>
        @endif


        @php
          $fields = get_the_terms( get_the_ID(), 'field' );

        @endphp

        @if ( ! empty( $fields ) && ! is_wp_error( $fields ) )
          <div class="flex flex-wrap gap-2">
            @foreach ( $fields as $field )
              <span class="font-sans text-[13px] tracking-[0.04em] rounded-sm px-4 py-1 bg-warm border border-transparent text-mid">
                {{ $field->name }}
              </span>
            @endforeach
          </div>
        @endif
      </section>

      {{-- Journey --}}
      <section>
        <div class="flex items-center gap-3 mb-6">
          <span class="font-sans text-[10px] tracking-[0.2em] uppercase text-terra">{{ __( 'The journey', 'vanilla' ) }}</span>
          <span class="flex-1 h-px bg-stone/15"></span>
        </div>
        <div class="relative pt-6">
          <div class="absolute top-0 left-0 right-0 h-px bg-stone/15"></div>
          <div class="grid grid-cols-4 gap-px">
            @foreach ( [
              [ 'year' => '2012', 'role' => 'Apprentice Stylist',      'place' => 'Toni &amp; Guy, London' ],
              [ 'year' => '2015', 'role' => 'Senior Colourist',        'place' => 'Hare &amp; Bone, Marylebone' ],
              [ 'year' => '2017', 'role' => 'Colour Education Lead',   'place' => 'Wella Professionals UK' ],
              [ 'year' => '2018 →', 'role' => 'Lead Stylist',          'place' => 'Vanilla Hair &amp; Beauty' ],
            ] as $stop )
              <div class="relative pt-6">
                <div class="absolute top-0 left-0 w-2 h-2 rounded-full bg-sand -translate-y-1/2"></div>
                <div class="font-sans text-[11px] text-terra font-medium tracking-[0.1em] mb-1">{{ $stop['year'] }}</div>
                <div class="font-sans text-[13px] text-dark font-medium leading-snug mb-0.5">{!! $stop['role'] !!}</div>
                <div class="font-sans text-[12px] text-stone">{!! $stop['place'] !!}</div>
              </div>
            @endforeach
          </div>
        </div>
      </section>

      {{-- Beyond the chair --}}
      <section>
        <div class="flex items-center gap-3 mb-5">
          <span class="font-sans text-[10px] tracking-[0.2em] uppercase text-terra">{{ __( 'Beyond the chair', 'vanilla' ) }}</span>
          <span class="flex-1 h-px bg-stone/15"></span>
        </div>
        <div class="grid grid-cols-2 gap-8">
          @foreach ( [
            [ 'label' => 'Currently into',     'value' => 'Ceramics &amp; wheel throwing' ],
            [ 'label' => 'Weekend ritual',      'value' => 'Coast walks with two whippets' ],
            [ 'label' => 'Morning routine',     'value' => 'Yoga, no exceptions' ],
            [ 'label' => 'Always photographing','value' => 'Colour work for Instagram' ],
          ] as $item )
            <div>
              <div class="font-sans text-[12px] uppercase tracking-[0.12em] text-stone/60 mb-1">{!! $item['label'] !!}</div>
              <div class="font-sans text-[14px] text-dark">{!! $item['value'] !!}</div>
            </div>
          @endforeach
        </div>
      </section>

      {{-- Find her --}}
      <section>
        <div class="flex items-center gap-3 mb-4">
          <span class="font-sans text-[10px] tracking-[0.2em] uppercase text-terra">{{ __( 'Find her', 'vanilla' ) }}</span>
          <span class="flex-1 h-px bg-stone/15"></span>
        </div>
        <div class="flex flex-wrap gap-3">
          <a href="#" class="font-sans text-[13px] text-mid border-b border-stone/30 pb-px hover:border-stone transition-colors">@madeleine_hair</a>
          <a href="#" class="font-sans text-[13px] text-mid border-b border-stone/30 pb-px hover:border-stone transition-colors">{{ __( 'LinkedIn', 'vanilla' ) }}</a>
          <a href="#" class="font-sans text-[13px] text-mid border-b border-stone/30 pb-px hover:border-stone transition-colors">{{ __( 'Pinterest inspiration board', 'vanilla' ) }}</a>
        </div>
      </section>

    </main>

    {{-- ── SIDEBAR ── --}}
    <aside class="border-l border-stone/15 bg-cream flex flex-col">

      {{-- Bookings --}}
      <div class="p-7 border-b border-stone/15">
        <p class="font-sans text-[10px] tracking-[0.18em] uppercase text-stone/60 mb-4">{{ __( 'Bookings', 'vanilla' ) }}</p>


        @foreach ( [
          [ 'key' => 'Days in',           'val' => get_field('days_in') ],
          [ 'key' => 'First appointment', 'val' => get_field('time_start') ],
          // [ 'key' => 'Bookings',          'val' => get_field('bookings') ],
          [ 'key' => 'Consultation',      'val' => get_field('consultation') ],
        ] as $row )
          <div class="flex justify-between items-baseline py-1.5 border-b border-stone/15 last:border-b-0">
            <span class="font-sans text-[12px] text-stone">{{ $row['key'] }}</span>
            <span class="font-sans text-[12px] text-dark font-medium text-right">{{ $row['val'] }}</span>
          </div>
        @endforeach

        <a href="#"
          class="block w-full mt-5 py-3 bg-mid hover:bg-dark transition-colors text-cream font-sans text-[12px] tracking-[0.12em] uppercase text-center">
          {{ __( 'Book with Maddie', 'vanilla' ) }}
        </a>
      </div>

      {{-- Team nav --}}
      <div class="px-7 pt-6 pb-3 font-sans text-[10px] tracking-[0.18em] uppercase text-stone/60">
        {{ __( 'The team', 'vanilla' ) }}
      </div>

      @php
  $team_query = new WP_Query( [
    'post_type'      => 'team',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
  ] );
@endphp

@if ( $team_query->have_posts() )
  @php $first = true; @endphp

  @while ( $team_query->have_posts()  )



    @php $team_query->the_post(); @endphp
@if ( has_post_thumbnail() )
      <a href="{{ get_permalink() }}"
        class="flex items-center gap-3 px-7 py-3 border-b border-stone/15 relative transition-colors {{ $first ? 'bg-warm' : 'hover:bg-warm/50' }}">

        <div class="w-10 h-10 rounded-full bg-sand/50 overflow-hidden flex-shrink-0">
          @if ( has_post_thumbnail() )
            {!! get_the_post_thumbnail( get_the_ID(), 'thumbnail', [ 'class' => 'w-full h-full object-cover' ] ) !!}
          @else
            <div class="w-full h-full flex items-center justify-center">
              <span class="font-serif italic text-mid leading-none">
                {{ mb_strtoupper( mb_substr( get_the_title(), 0, 1 ) ) }}
              </span>
            </div>
          @endif
        </div>

        <div class="min-w-0">
          <div class="font-sans text-[13px] text-dark font-medium truncate">{{ get_the_title() }}</div>
          <div class="font-sans text-[11px] text-stone truncate">{{ get_field( 'title' ) }}</div>
        </div>

        <span class="ml-auto font-sans text-[11px] text-stone flex-shrink-0">
          {!! $first ? '&mdash;' : '&rarr;' !!}
        </span>

        @if ( $first )
          <span class="absolute right-0 top-0 bottom-0 w-0.5 bg-mid"></span>
        @endif

      </a>

      @php $first = false; @endphp

    @endif
  @endwhile

  @php wp_reset_postdata(); @endphp
@endif

    </aside>

  </div>
@endwhile
@endsection
