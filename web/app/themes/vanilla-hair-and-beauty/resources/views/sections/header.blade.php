<header class="banner">
  <a class="brand" href="{{ home_url('/') }}">
    @if (has_custom_logo())
      {!! get_custom_logo() !!}
    @else
      {!! $siteName !!}
    @endif
  </a>

  @include('partials.navigation')
</header>
