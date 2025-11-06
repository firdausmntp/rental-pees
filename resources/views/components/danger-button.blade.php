<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-wide hover:from-red-600 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:scale-95 transition ease-in-out duration-150 shadow-lg hover:shadow-red-500/50']) }}>
    {{ $slot }}
</button>
