@extends('layouts.app')

@section('title', __('Faculties'))

@section('content')
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
            <h1 class="text-3xl font-extrabold text-brand-900">{{ __('Faculties') }}</h1>
            <p class="mt-2 text-slate-500">{{ __('Explore faculties') }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($faculties as $faculty)
                <a href="{{ route('programs.index', ['faculty' => $faculty->slug]) }}"
                   class="group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-md">
                    <span class="grid h-16 w-16 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-brand-600 to-brand-900 text-3xl">{{ $faculty->emoji() }}</span>
                    <div>
                        <h2 class="text-lg font-bold text-brand-900 group-hover:text-brand-600">{{ $faculty->loc('name') }}</h2>
                        <p class="text-sm text-slate-500">{{ $faculty->programs_count }} {{ __('programs') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
