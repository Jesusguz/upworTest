<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mini Task Manager</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite('resources/css/app.css')

    @livewireStyles
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    {{-- Aquí es donde el contenido de tu componente Livewire será insertado --}}
    {{ $slot }}
</div>

@livewireScripts

{{-- Script para las notificaciones Toast (si usas SweetAlert2 u otra librería) --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('toast', (event) => {
            const { message, type = 'success' } = event[0];
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: type, // success, error, warning, info, question
                title: message
            });
        });
    });
</script>


@vite('resources/js/app.js') {{-- Incluye tu JS principal (con Alpine.js) --}}
</body>
</html>
