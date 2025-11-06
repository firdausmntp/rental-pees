<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-wide hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95 transition ease-in-out duration-150 shadow-lg hover:shadow-blue-500/50']) }}>
    {{ $slot }}
</button>
