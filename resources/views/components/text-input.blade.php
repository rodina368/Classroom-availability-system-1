@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 dark:border-gray-600 focus:border-limu-blue dark:focus:border-limu-blue-light focus:ring-limu-blue/20 dark:focus:ring-limu-blue/40 rounded-xl shadow-sm px-4 py-3 bg-gray-50/50 dark:bg-gray-700/50 dark:text-white dark:placeholder-gray-400 transition-all duration-200']) }}>
