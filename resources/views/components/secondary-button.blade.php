<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-white/10 border border-white/20 rounded-xl font-semibold text-white uppercase tracking-wide hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 active:scale-95 disabled:opacity-50 transition ease-in-out duration-150 backdrop-blur-sm']) }}>
    {{ $slot }}
</button>
