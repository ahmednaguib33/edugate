@php
    $links = [
        ['route' => 'home', 'active' => 'home', 'label' => __('Home')],
        ['route' => 'programs.index', 'active' => 'programs.*', 'label' => __('Programs')],
        ['route' => 'faculties.index', 'active' => 'faculties.*', 'label' => __('Faculties')],
        ['route' => 'universities.index', 'active' => 'universities.*', 'label' => __('Universities')],
    ];
    $otherLocale = app()->getLocale() === 'ar' ? 'en' : 'ar';
    $otherLabel = app()->getLocale() === 'ar' ? 'English' : 'العربية';
@endphp

<header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
    <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-extrabold tracking-tight">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-brand-700 text-white">🎓</span>
            <span class="text-brand-800">Edu<span class="text-gold-500">Gate</span></span>
        </a>

        <div class="hidden items-center gap-1 md:flex">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   class="rounded-lg px-3 py-2 text-sm font-semibold transition {{ request()->routeIs($link['active']) ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100 hover:text-brand-700' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ url('/docs') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 hover:text-brand-700">{{ __('API Docs') }}</a>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('locale.switch', $otherLocale) }}"
               class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:border-brand-300 hover:text-brand-700">
                {{ $otherLabel }}
            </a>
            <a href="{{ route('programs.index') }}"
               class="hidden rounded-lg bg-gold-500 px-4 py-2 text-sm font-bold text-brand-900 shadow-sm transition hover:bg-gold-400 sm:inline-block">
                {{ __('Browse programs') }}
            </a>
            <button @click="open = !open" class="grid h-10 w-10 place-items-center rounded-lg text-slate-700 hover:bg-slate-100 md:hidden" aria-label="Menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </nav>

    <div x-show="open" x-transition class="border-t border-slate-200 bg-white md:hidden" style="display:none">
        <div class="space-y-1 px-4 py-3">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}" class="block rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs($link['active']) ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-100' }}">{{ $link['label'] }}</a>
            @endforeach
            <a href="{{ url('/docs') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">{{ __('API Docs') }}</a>
        </div>
    </div>
</header>
