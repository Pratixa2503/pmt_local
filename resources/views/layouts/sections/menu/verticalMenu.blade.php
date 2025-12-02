@php
    $configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    @if (!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
                <span class="app-brand-logo demo main-logo ">
                    @include('_partials.macros', ['height' => 20])
                </span>
                <span class="app-brand-logo logo-icon">
                    <img src="{{ asset('assets/img/favicon/favicon.svg') }}" alt class="h-auto">
                </span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
        </div>
    @endif


    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $menu)
            {{-- adding active and open class if child is active --}}

            {{-- menu headers --}}
            @if (isset($menu->menuHeader))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">{{ $menu->menuHeader }}</span>
                </li>
            @else
                {{-- active menu method --}}
                @php
                    $activeClass = null;
                    $currentRouteName = Route::currentRouteName();
                    if (isset($menu->slug)) {
                        if (is_array($menu->slug)) {
                            foreach ($menu->slug as $slug) {
                                if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                                    $activeClass = 'active';
                                }
                            }
                        } else {
                            if ($currentRouteName === $menu->slug || (str_contains($currentRouteName, $menu->slug) && strpos($currentRouteName, $menu->slug) === 0)) {
                                $activeClass = 'active';
                            }
                        }
                    }

                    // Check submenu levels
                    if (isset($menu->submenu) && is_array($menu->submenu)) {
                        foreach ($menu->submenu as $submenu) {
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
                    $permissions = is_array($menu->permission) ? $menu->permission : [$menu->permission];
                    
                    // Hide Masters menu for reviewer, abstractor, and sense check users
                    $isRestrictedRole = false;
                    if (isset($menu->name) && $menu->name === 'Masters') {
                        $user = auth()->user();
                        if ($user && method_exists($user, 'hasAnyRole')) {
                            $isRestrictedRole = $user->hasAnyRole([
                                'reviewer', 'Reviewer',
                                'abstractor', 'Abstractor',
                                'sense check', 'sense_check', 'Sense Check / DDR','sales'
                            ]);
                        }
                    }
                @endphp

                @if(auth()->check() && auth()->user()->hasAnyPermission($permissions) && !$isRestrictedRole)
                    <li class="menu-item {{ $activeClass }}">
                        <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                            class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                            @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                            @isset($menu->icon)
                                <i class="{{ $menu->icon }}"></i>
                            @endisset
                            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                        </a>

                        @isset($menu->submenu)
                            @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
                        @endisset

                    </li>
                @endif
            @endif
        @endforeach
    </ul>

</aside>
