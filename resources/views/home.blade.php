@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-brand-900 text-white">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, #f2c75e 0, transparent 40%), radial-gradient(circle at 80% 0%, #4683bf 0, transparent 35%);"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:py-28">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-semibold text-gold-300">
                    🇪🇬 {{ __('Study in Egypt') }}
                </span>
                <h1 class="mt-5 text-4xl font-extrabold leading-tight sm:text-5xl">
                    {{ __('Your gateway to accredited Egyptian universities') }}
                </h1>
                <p class="mt-5 text-lg text-slate-200">
                    {{ __("For Arab and Gulf students — Bachelor's, Master's & PhD across Egypt's top universities.") }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('programs.index') }}" class="rounded-xl bg-gold-500 px-6 py-3 font-bold text-brand-900 shadow transition hover:bg-gold-400">{{ __('Browse programs') }}</a>
                    <a href="{{ route('programs.index') }}#apply" class="rounded-xl border border-white/30 px-6 py-3 font-bold text-white transition hover:bg-white/10">{{ __('Talk to an advisor') }}</a>
                </div>
            </div>

            <dl class="mt-14 grid max-w-2xl grid-cols-3 gap-4">
                @foreach (['programs' => 'Programs offered', 'faculties' => 'Faculties offered', 'universities' => 'Partner universities'] as $key => $label)
                    <div class="rounded-2xl bg-white/10 p-5 text-center backdrop-blur">
                        <dd class="text-3xl font-extrabold text-gold-300">{{ $stats[$key] }}+</dd>
                        <dt class="mt-1 text-sm text-slate-200">{{ __($label) }}</dt>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- Featured programs --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
        <div class="flex items-end justify-between">
            <h2 class="text-2xl font-extrabold text-brand-900 sm:text-3xl">{{ __('Featured programs') }}</h2>
            <a href="{{ route('programs.index') }}" class="text-sm font-bold text-brand-600 hover:text-brand-800">{{ __('View all') }} {{ app()->getLocale() === 'ar' ? '←' : '→' }}</a>
        </div>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($featured as $program)
                @include('partials.program-card', ['program' => $program])
            @endforeach
        </div>
    </section>

    {{-- Faculties --}}
    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex items-end justify-between">
                <h2 class="text-2xl font-extrabold text-brand-900 sm:text-3xl">{{ __('Explore faculties') }}</h2>
                <a href="{{ route('faculties.index') }}" class="text-sm font-bold text-brand-600 hover:text-brand-800">{{ __('View all') }} {{ app()->getLocale() === 'ar' ? '←' : '→' }}</a>
            </div>
            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($faculties as $faculty)
                    <a href="{{ route('programs.index', ['faculty' => $faculty->slug]) }}"
                       class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-brand-300 hover:shadow-md">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand-50 text-2xl">{{ $faculty->emoji() }}</span>
                        <span>
                            <span class="block font-bold text-brand-900">{{ $faculty->loc('name') }}</span>
                            <span class="block text-xs text-slate-500">{{ $faculty->programs_count }} {{ __('programs') }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why EduGate --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
        <h2 class="text-center text-2xl font-extrabold text-brand-900 sm:text-3xl">{{ __('Why EduGate?') }}</h2>
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['🏅', 'Accredited certificates', 'Recognized locally and internationally.'],
                ['🔬', 'Practical training', 'With the latest technology and labs.'],
                ['🛡️', 'Safe campus life', 'Diverse, welcoming and student-friendly.'],
                ['💰', 'Affordable tuition', 'Quality education at competitive fees.'],
            ] as $feature)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center">
                    <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-gold-100 text-3xl">{{ $feature[0] }}</div>
                    <h3 class="mt-4 font-bold text-brand-900">{{ __($feature[1]) }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ __($feature[2]) }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Universities --}}
    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="text-2xl font-extrabold text-brand-900 sm:text-3xl">{{ __('Our universities') }}</h2>
            <div class="mt-8 flex flex-wrap gap-3">
                @foreach ($universities as $university)
                    <span class="rounded-full border border-slate-200 bg-slate-50 px-5 py-2.5 font-semibold text-slate-700">
                        🏛️ {{ $university->loc('name') }}
                    </span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
        <div class="overflow-hidden rounded-3xl bg-brand-800 p-10 text-center text-white sm:p-14">
            <h2 class="text-2xl font-extrabold sm:text-3xl">{{ __('Ready to start your journey?') }}</h2>
            <p class="mx-auto mt-3 max-w-2xl text-slate-200">{{ __('Browse hundreds of programs at accredited Egyptian universities and apply in minutes.') }}</p>
            <a href="{{ route('programs.index') }}" class="mt-7 inline-block rounded-xl bg-gold-500 px-7 py-3 font-bold text-brand-900 shadow transition hover:bg-gold-400">{{ __('Browse programs') }}</a>
        </div>
    </section>
@endsection
