import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['DM Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                body: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                primary: {
                    50:  '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#1d6dbf',
                    700: '#1a56a0',
                    800: '#1e3f7a',
                    900: '#1e3a5f',
                },
                bg:      '#f8fafc',
                surface: '#ffffff',
                border:  '#e2e8f0',
                text:    '#0f172a',
                muted:   '#64748b',
                status: {
                    tersedia:    '#16a34a',
                    dipinjam:    '#ca8a04',
                    maintenance: '#ea580c',
                    rusak:       '#dc2626',
                },
                pengajuan: {
                    diajukan:     '#2563eb',
                    disetujui:    '#16a34a',
                    ditolak:      '#dc2626',
                    dipinjam:     '#ca8a04',
                    selesai:      '#6b7280',
                }
            },
            spacing: {
                'sidebar-expanded':  '256px',
                'sidebar-collapsed': '64px',
                'topbar':            '56px',
                'bottomnav':         '60px',
            },
        },
    },

    plugins: [forms],
};
