@extends('layouts.app')

@section('title', __('Programs'))

@section('content')
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
            <h1 class="text-3xl font-extrabold text-brand-900">{{ __('Programs') }}</h1>
            <p class="mt-2 text-slate-500">{{ __("Bachelor's, Master's & PhD") }}</p>

            <form method="GET" action="{{ route('programs.index') }}" class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <div class="lg:col-span-2">
                    <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('Search programs…') }}"
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </div>
                <select name="faculty" class="rounded-xl border border-slate-300 px-3 py-2.5 outline-none focus:border-brand-500">
                    <option value="">{{ __('All faculties') }}</option>
                    @foreach ($faculties as $faculty)
                        <option value="{{ $faculty->slug }}" @selected(($filters['faculty'] ?? '') === $faculty->slug)>{{ $faculty->loc('name') }}</option>
                    @endforeach
                </select>
                <select name="degree_level" class="rounded-xl border border-slate-300 px-3 py-2.5 outline-none focus:border-brand-500">
                    <option value="">{{ __('All levels') }}</option>
                    @foreach ($degreeLevels as $level)
                        <option value="{{ $level->value }}" @selected(($filters['degree_level'] ?? '') === $level->value)>{{ $level->label() }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <select name="sort" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none focus:border-brand-500">
                        <option value="">{{ __('Most relevant') }}</option>
                        <option value="tuition_asc" @selected(($filters['sort'] ?? '') === 'tuition_asc')>{{ __('Tuition: low to high') }}</option>
                        <option value="tuition_desc" @selected(($filters['sort'] ?? '') === 'tuition_desc')>{{ __('Tuition: high to low') }}</option>
                    </select>
                    <button type="submit" class="shrink-0 rounded-xl bg-brand-700 px-5 py-2.5 font-bold text-white transition hover:bg-brand-600">{{ __('Search') }}</button>
                </div>
            </form>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
        @if ($programs->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <div class="text-5xl">🔍</div>
                <p class="mt-4 font-semibold text-slate-600">{{ __('No programs match your filters.') }}</p>
                <a href="{{ route('programs.index') }}" class="mt-4 inline-block rounded-xl bg-brand-700 px-5 py-2.5 font-bold text-white hover:bg-brand-600">{{ __('Reset filters') }}</a>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($programs as $program)
                    @include('partials.program-card', ['program' => $program])
                @endforeach
            </div>
            <div class="mt-10">
                {{ $programs->links() }}
            </div>
        @endif
    </section>
@endsection
