@if (has_nav_menu('primary_navigation'))
  <nav class="flex items-center gap-8" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
    @php
      wp_nav_menu([
        'theme_location' => 'primary_navigation',
        'container'      => false,
        'items_wrap'     => '<ul class="flex items-center gap-8 m-0 p-0">%3$s</ul>',
        'walker'         => new \App\NavWalker(),
        'fallback_cb'    => false,
        'depth'          => 1,
      ]);
    @endphp
  </nav>
@endif
