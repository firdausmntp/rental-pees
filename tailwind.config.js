import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Enable dark mode with class strategy
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    daisyui: {
        themes: [
            {
                rental: {
                    // Netral Profesional - Light Mode
                    primary: '#0EA5E9',        // Sky blue professional
                    'primary-content': '#FFFFFF',
                    secondary: '#475569',      // Secondary text
                    'secondary-content': '#FFFFFF',
                    accent: '#3B82F6',        // Accent blue
                    'accent-content': '#FFFFFF',
                    neutral: '#1E293B',       // Text color
                    'neutral-content': '#FFFFFF',
                    'base-100': '#F4F4F5',    // Background
                    'base-200': '#FFFFFF',    // Card/Surface
                    'base-300': '#E2E8F0',
                    'base-content': '#1E293B', // Text
                    info: '#38BDF8',
                    success: '#10B981',
                    warning: '#F59E0B',
                    error: '#EF4444',
                },
                dark: {
                    // Netral Profesional - Dark Mode
                    primary: '#38BDF8',        // Light sky blue
                    'primary-content': '#111827',
                    secondary: '#94A3B8',      // Secondary text dark
                    'secondary-content': '#111827',
                    accent: '#60A5FA',        // Accent blue light
                    'accent-content': '#111827',
                    neutral: '#E2E8F0',       // Text color light
                    'neutral-content': '#111827',
                    'base-100': '#111827',    // Background dark
                    'base-200': '#1E293B',    // Card/Surface dark
                    'base-300': '#334155',
                    'base-content': '#E2E8F0', // Text light
                    info: '#38BDF8',
                    success: '#10B981',
                    warning: '#F59E0B',
                    error: '#EF4444',
                },
            },
            'rental',
            'dark',
        ],
    },

    plugins: [forms, daisyui],
};
