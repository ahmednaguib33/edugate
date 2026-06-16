<div id="apply" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <h3 class="text-xl font-bold text-brand-900">{{ __('Interested in this program?') }}</h3>
    <p class="mt-1 text-sm text-slate-500">{{ __('Fill in the form and our advisors will contact you shortly.') }}</p>

    @if ($errors->any())
        <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
            <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-4' : 'pl-4' }} space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('lead.store') }}" class="mt-5 space-y-4">
        @csrf
        <input type="hidden" name="program_id" value="{{ $program->id }}">

        <div class="grid gap-4 sm:grid-cols-2">
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">{{ __('Full name') }} *</span>
                <input name="full_name" value="{{ old('full_name') }}" required
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
            </label>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">{{ __('Email') }} *</span>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
            </label>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">{{ __('Phone') }}</span>
                <input name="phone" value="{{ old('phone') }}" dir="ltr"
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
            </label>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">{{ __('Nationality') }}</span>
                <input name="nationality" value="{{ old('nationality') }}"
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
            </label>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">{{ __('Current education') }}</span>
                <input name="current_education" value="{{ old('current_education') }}"
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
            </label>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">{{ __('Preferred intake') }}</span>
                <input name="preferred_intake" value="{{ old('preferred_intake') }}"
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
            </label>
        </div>

        <label class="block">
            <span class="text-sm font-semibold text-slate-700">{{ __('Notes') }}</span>
            <textarea name="notes" rows="3"
                      class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('notes') }}</textarea>
        </label>

        <button type="submit" class="w-full rounded-xl bg-gold-500 px-5 py-3 font-bold text-brand-900 shadow-sm transition hover:bg-gold-400">
            {{ __('Send request') }}
        </button>
    </form>
</div>
