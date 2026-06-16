<footer class="mt-16 border-t border-slate-200 bg-brand-900 text-slate-300">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 md:grid-cols-3">
        <div>
            <div class="flex items-center gap-2 text-xl font-extrabold text-white">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-brand-700">🎓</span>
                <span>Edu<span class="text-gold-400">Gate</span></span>
            </div>
            <p class="mt-3 max-w-xs text-sm leading-relaxed text-slate-400">
                {{ __('Helping Arab and Gulf students study at accredited Egyptian universities.') }}
            </p>
        </div>

        <div>
            <h3 class="text-sm font-bold uppercase tracking-wide text-slate-200">{{ __('Quick links') }}</h3>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="{{ route('programs.index') }}" class="hover:text-gold-400">{{ __('Programs') }}</a></li>
                <li><a href="{{ route('faculties.index') }}" class="hover:text-gold-400">{{ __('Faculties') }}</a></li>
                <li><a href="{{ route('universities.index') }}" class="hover:text-gold-400">{{ __('Universities') }}</a></li>
                <li><a href="{{ url('/docs') }}" class="hover:text-gold-400">{{ __('API Docs') }}</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-bold uppercase tracking-wide text-slate-200">{{ __('Contact') }}</h3>
            <ul class="mt-4 space-y-2 text-sm text-slate-400">
                <li>📧 info@edugate.example</li>
                <li>📞 +20 100 000 0000</li>
                <li>📍 Cairo, Egypt</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10">
        <p class="mx-auto max-w-7xl px-4 py-4 text-center text-xs text-slate-400 sm:px-6">
            © {{ date('Y') }} EduGate. {{ __('All rights reserved.') }}
        </p>
    </div>
</footer>
