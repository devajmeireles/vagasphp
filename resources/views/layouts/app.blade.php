<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          dark : false,
          init() {
              let dark = localStorage.getItem('dark-theme');

              if (dark !== null) {
                  this.dark = dark === 'true';
              }

              this.$watch('dark', (dark) => localStorage.setItem('dark-theme', dark));
          }
      }" x-cloak :class="{ 'dark bg-gray-800' : dark, 'bg-gray-100' : !dark }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased" x-data @redirect="setTimeout(() => window.location.href = $event.detail.route, $event.detail.time || 3000)">

        <div class="min-h-screen">
            <x-layout.header />
            <main class="max-w-7xl mx-auto pb-24">
                {{ $slot }}
            </main>
        </div>

    @livewireScripts
    @stack('scripts')

    </body>
</html>
