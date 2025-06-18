import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#f9eaea',
                    100: '#f4d6d6',
                    200: '#e6aaaa',
                    300: '#d67575',
                    400: '#c14545',
                    500: '#a92d2d',
                    600: '#922323',
                    700: '#7a1d1d',
                    800: '#671b1b',
                    900: '#551a1a',
                    950: '#2e0a0a',
                },
            },
        },
    },
}
