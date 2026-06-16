@extends('layouts.app')

@section('title', __('Universities'))

@section('content')
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
            <h1 class="text-3xl font-extrabold text-brand-900">{{ __('Universities') }}</h1>
            <p class="mt-2 text-slate-500">{{ __('Our universities') }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($universities as $university)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-brand-50 text-2xl">🏛️</span>
                        @if ($university->is_accredited)
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">✔ {{ __('Accredited certificates') }}</span>
                        @endif
                    </div>
                    <h2 class="mt-4 text-lg font-bold text-brand-900">{{ $university->loc('name') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        @if ($university->city)📍 {{ $university->city }}@endif
                        @if ($university->established_year) · {{ $university->established_year }}@endif
                    </p>
                    <div class="mt-4 border-t border-slate-100 pt-4 text-sm font-semibold text-brand-600">
                        {{ $university->programs_count }} {{ __('programs') }}
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
