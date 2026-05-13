import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // ===== BRAND COLORS =====
                navy: {
                    DEFAULT: '#0A2540',
                    50: '#E8EDF3',
                    100: '#D1DAE7',
                    200: '#A3B5CF',
                    300: '#7590B7',
                    400: '#476A9F',
                    500: '#1A4587',
                    600: '#13325F',
                    700: '#0A2540',
                    800: '#061931',
                    900: '#030E1C',
                },
                gold: {
                    DEFAULT: '#C9A961',
                    50: '#FAF6EC',
                    100: '#F5EDD9',
                    200: '#EBDBB3',
                    300: '#E0C580',
                    400: '#D5B470',
                    500: '#C9A961',
                    600: '#A88A47',
                    700: '#7F6834',
                    800: '#564621',
                    900: '#2D240E',
                },
                cream: {
                    DEFAULT: '#FAF7F0',
                    50: '#FEFDFB',
                    100: '#FAF7F0',
                    200: '#F5EFE2',
                    300: '#E5DCC4',
                    400: '#D9CFB8',
                    500: '#A89880',
                    600: '#7A6A4F',
                },
                paper: '#F5EFE2',
            },

            fontFamily: {
                // ===== BRAND FONTS =====
                display: ['"Bricolage Grotesque"', ...defaultTheme.fontFamily.sans],
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },

            letterSpacing: {
                'eyebrow': '0.3em',
                'wider-2': '0.25em',
            },

            // ===== ANIMATIONS =====
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.4s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    },

    plugins: [forms],
};
