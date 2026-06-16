@extends('layouts.app')

@section('title', $program->loc('title'))

@section('content')
    {{-- Header --}}
    <section class="bg-brand-900 text-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
            <a href="{{ route('programs.index') }}" class="text-sm text-slate-300 hover:text-gold-300">{{ app()->getLocale() === 'ar' ? '→' : '←' }} {{ __('Programs') }}</a>
            <div class="mt-4 flex items-start gap-5">
                <span class="hidden h-20 w-20 shrink-0 place-items-center rounded-2xl bg-white/10 text-5xl sm:grid">{{ $program->faculty?->emoji() }}</span>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-gold-400 px-3 py-1 text-xs font-bold text-brand-900">{{ $program->degree_level?->label() }}</span>
                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-200">{{ $program->faculty?->loc('name') }}</span>
                    </div>
                    <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">{{ $program->loc('title') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                {{-- Key facts --}}
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    @php
                        $facts = [
                            ['💵', __('Annual tuition'), $program->tuition_min !== null ? '$'.number_format((float) $program->tuition_min, 0).'–'.number_format((float) $program->tuition_max, 0) : '—'],
                            ['🎯', __('Min. admission'), $program->min_admission_rate !== null ? (int) $program->min_admission_rate.'%' : '—'],
                            ['⏳', __('Duration'), $program->duration_years !== null ? (float) $program->duration_years.' '.__('years') : '—'],
                            ['🗣️', __('Language of study'), $program->language?->label()],
                        ];
                    @endphp
                    @foreach ($facts as $fact)
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-2xl">{{ $fact[0] }}</div>
                            <div class="mt-2 text-xs text-slate-500">{{ $fact[1] }}</div>
                            <div class="font-bold text-brand-900">{{ $fact[2] }}</div>
                        </div>
                    @endforeach
                </div>

                {{-- About --}}
                @if ($program->loc('description'))
                    <div class="mt-8">
                        <h2 class="text-xl font-bold text-brand-900">{{ __('About this program') }}</h2>
                        <p class="mt-3 leading-relaxed text-slate-600">{{ $program->loc('description') }}</p>
                    </div>
                @endif

                {{-- Highlights --}}
                @if (!empty($program->highlights))
                    <div class="mt-8">
                        <h2 class="text-xl font-bold text-brand-900">{{ __('Program highlights') }}</h2>
                        <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($program->highlights as $highlight)
                                <li class="flex items-start gap-2 rounded-xl bg-white p-3 ring-1 ring-slate-200">
                                    <span class="text-gold-500">✔</span>
                                    <span class="text-sm text-slate-700">{{ $highlight }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Universities --}}
                @if ($program->universities->isNotEmpty())
                    <div class="mt-8">
                        <h2 class="text-xl font-bold text-brand-900">{{ __('Offered at these universities') }}</h2>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($program->universities as $university)
                                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4">
                                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-xl">🏛️</span>
                                    <div>
                                        <div class="font-bold text-brand-900">{{ $university->loc('name') }}</div>
                                        @if ($university->city)
                                            <div class="text-xs text-slate-500">📍 {{ $university->city }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar: lead form --}}
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-24">
                    @include('partials.lead-form', ['program' => $program])
                </div>
            </div>
        </div>

        {{-- Related --}}
        @if ($related->isNotEmpty())
            <div class="mt-14">
                <h2 class="text-2xl font-extrabold text-brand-900">{{ __('Related programs') }}</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $program)
                        @include('partials.program-card', ['program' => $program])
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
