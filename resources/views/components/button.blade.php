<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-greencustom border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-greencustomshadow focus:bg-greencustomshadow active:bg-greencustomshadow focus:outline-none focus:ring-2 focus:ring-browncustom focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
