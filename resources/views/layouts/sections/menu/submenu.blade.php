<ul class="menu-sub">
    @if (isset($menu))
        @foreach ($menu as $submenu)
            {{-- active menu method --}}
            @php
                $activeClass = null;
                $active = $configData['layout'] === 'vertical' ? 'active open' : 'active';
                $currentRouteName = Route::currentRouteName();

                if (isset($submenu->slug)) {
                    if (is_array($submenu->slug)) {
                        foreach ($submenu->slug as $slug) {
                            if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                                $activeClass = 'active';
                            }
                        }
                    } else {
                        if ($currentRouteName === $submenu->slug || (str_contains($currentRouteName, $submenu->slug) && strpos($currentRouteName, $submenu->slug) === 0)) {
                            $activeClass = 'active';
                        }
                    }
                }

                // Check submenu levels
                if (isset($submenu->submenu) && is_array($submenu->submenu)) {
                    foreach ($submenu->submenu as $submenu) {
                        if (isset($submenu->slug)) {
                            if (is_array($submenu->slug)) {
                                foreach ($submenu->slug as $slug) {
                                    if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                                        $activeClass = 'active open';
                                    }
                                }
                            } else {
                                if (str_contains($currentRouteName, $submenu->slug) && strpos($currentRouteName, $submenu->slug) === 0) {
                                    $activeClass = 'active open';
                                }
                            }
                        }

                        // Check nested submenu
                        if (isset($submenu->submenu) && is_array($submenu->submenu)) {
                            foreach ($submenu->submenu as $child) {
                                if (isset($child->slug)) {
                                    if (is_array($child->slug)) {
                                        foreach ($child->slug as $slug) {
                                            if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                                                $activeClass = 'active open';
                                            }
                                        }
                                    } else {
                                        if (str_contains($currentRouteName, $child->slug) && strpos($currentRouteName, $child->slug) === 0) {
                                            $activeClass = 'active open';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            @endphp

            @php
                $permissions = is_array($submenu->permission) ? $submenu->permission : [$submenu->permission];
            @endphp
            @if(auth()->check() && auth()->user()->hasAnyPermission($permissions))
                
                <li class="menu-item {{ $activeClass }}">
                    <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0)' }}"
                        class="{{ isset($submenu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                        @if (isset($submenu->target) and !empty($submenu->target)) target="_blank" @endif>
                        @if (isset($submenu->icon))
                            <i class="{{ $submenu->icon }}"></i>
                        @endif
                        <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>
                    </a>

                    {{-- submenu --}}
                    @if (isset($submenu->submenu))
                        @include('layouts.sections.menu.submenu', ['menu' => $submenu->submenu])
                    @endif
                </li>
            @endif

        @endforeach
    @endif
</ul>
