<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-3 bg-white text-indigo-700 border border-transparent rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-pink-50 active:bg-pink-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition ease-in-out duration-150 w-full']) }}>
    {{ $slot }}
</button>