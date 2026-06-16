@php
    $tuition = $program->tuition_min !== null
        ? '$'.number_format((float) $program->tuition_min, 0).' – $'.number_format((float) $program->tuition_max, 0)
        : '—';
@endphp

<article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
    <div class="relative flex h-28 items-center justify-center bg-gradient-to-br from-brand-600 to-brand-900">
        <span class="text-5xl drop-shadow-sm">{{ $program->faculty?->emoji() }}</span>
        <span class="absolute top-3 {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }} rounded-full bg-gold-400 px-2.5 py-1 text-xs font-bold text-brand-900">
            {{ $program->degree_level?->label() }}
        </span>
    </div>

    <div class="flex flex-1 flex-col p-5">
        <p class="text-xs font-bold uppercase tracking-wide text-gold-600">{{ $program->faculty?->loc('name') }}</p>
        <h3 class="mt-1 text-lg font-bold leading-snug text-brand-900">
            <a href="{{ route('programs.show', $program->slug) }}" class="hover:text-brand-600">{{ $program->loc('title') }}</a>
        </h3>

        <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
            <div class="rounded-xl bg-slate-50 p-3">
                <dt class="text-xs text-slate-500">{{ __('Annual tuition') }}</dt>
                <dd class="mt-0.5 font-bold text-slate-800">{{ $tuition }}</dd>
            </div>
            <div class="rounded-xl bg-slate-50 p-3">
                <dt class="text-xs text-slate-500">{{ __('Min. admission') }}</dt>
                <dd class="mt-0.5 font-bold text-slate-800">{{ $program->min_admission_rate !== null ? (int) $program->min_admission_rate.'%' : '—' }}</dd>
            </div>
        </dl>

        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
            <span class="text-xs text-slate-500">
                🏛️ {{ __('Available at :count universities', ['count' => $program->universities_count ?? $program->universities->count()]) }}
            </span>
            <a href="{{ route('programs.show', $program->slug) }}" class="text-sm font-bold text-brand-600 hover:text-brand-800">
                {{ __('View details') }} <span aria-hidden="true">{{ app()->getLocale() === 'ar' ? '←' : '→' }}</span>
            </a>
        </div>
    </div>
</article>
