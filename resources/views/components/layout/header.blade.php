@php($user = user())

<div class="relative overflow-hidden"
     x-data="{
        login : false,
        profile : false,
        welcome : false,
        onboarding : @js(session()->has('onboarding')),
        init() {
            if (this.onboarding === false) {
                return;
            }

            setTimeout(() => this.welcome = true, 500)
        }
    }">
    <div class="relative pt-2 pb-16 sm:pb-12">
        <div class="mx-auto max-w-7xl">
            <nav class="relative flex items-center justify-between sm:h-10 md:justify-center" aria-label="Global">
                <div class="hidden md:absolute md:inset-y-0 md:right-0 md:flex md:items-center md:justify-end">
                    @guest
                        <x-button-outline xs @click="login = !login">
                            @lang('Acessar')
                        </x-button-outline>
                    @endguest
                    @auth
                        <div @click="profile = !profile" class="inline-flex items-center space-x-2 cursor-pointer">
                            <p class="font-semibold text-primary dark:text-white">@lang('Olá, :name!', ['name' => $user->name])</p>
                            <img class="h-8 w-8 rounded-full" src="{{ $user->avatar }}" alt="">
                        </div>
                    @endauth
                    <div class="p-4 text-center text-gray-700 dark:text-white">
                        <div class="inline-flex items-center">
                            <x-svg.sun class="mt-3 mr-3 h-6 w-6 text-primary dark:text-white" />
                            <x-toggle trigger="dark" />
                            <x-svg.moon class="mt-3 ml-3 h-6 w-6 text-primary dark:text-white" />
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <div class="absolute inset-x-0 top-0 z-10 origin-top-right transform p-2 transition md:hidden">
            <div class="overflow-hidden rounded-lg bg-white shadow-md ring-1 ring-black ring-opacity-5">
                <div class="flex items-center justify-between px-5 pt-4">
                    <div>
                        <img class="h-8 w-auto"
                             src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="">
                    </div>
                    <div class="-mr-2">
                        <button type="button"
                                class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <span class="sr-only">Close menu</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <x-button-outline white xs @click="open = !open">
                    @lang('Acessar')
                </x-button-outline>
            </div>
        </div>

        <main class="mx-auto max-w-7xl">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block text-primary dark:text-white xl:inline">@lang('VagasPhp!')</span>
                    <span class="text-white xl:inline bg-gradient-to-tl from-blue-500 via-green-500 to-yellow-500">@lang('É do Brasil.')</span>
                </h1>
                <p class="mx-auto max-w-md text-base text-gray-500 dark:text-white sm:text-lg md:mt-5 md:max-w-3xl md:text-xl">
                    @lang('O maior portal online de vagas PHP do Brasil.')
                </p>
                <a href="https://tallstack.dev/" target="_blank" class="text-xs text-gray-700 dark:text-white font-semibold">@lang('TALL Stack') <x-svg.heart class="inline-flex w-4 h-4 text-red-500" /></a>
            </div>
        </main>

    </div>

    @guest
        <x-modal trigger="login" title="Acesso">
            <p class="my-4 text-md text-center text-gray-500 dark:text-white">
                @lang('Faça login ou registre-se para anunciar ou candidatar-se a vagas.')
            </p>
            <div class="mt-4 grid grid-cols-2 gap-2">
                <div class="col-span-1">
                    <a href="{{ route('socialite.redirect', \App\Enums\SocialiteProvider::Google) }}"
                       class="flex justify-center text-white bg-[#4285F4] hover:bg-[#4285F4]/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#4285F4]/55 mr-2 mb-2 w-full">
                        <svg class="mr-2 -ml-1 w-4 h-4" aria-hidden="true" focusable="false" data-prefix="fab"
                             data-icon="google" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512">
                            <path fill="currentColor"
                                  d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path>
                        </svg>
                        @lang('Google')
                    </a>
                </div>

                <div class="col-span-1">
                    <a href="{{ route('socialite.redirect', \App\Enums\SocialiteProvider::GitHub) }}"
                       class="flex justify-center text-white bg-[#24292F] hover:bg-[#24292F]/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 mr-2 w-full">
                        <svg class="mr-2 -ml-1 w-4 h-4" aria-hidden="true" focusable="false" data-prefix="fab"
                             data-icon="github" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512">
                            <path fill="currentColor"
                                  d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3 .3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5 .3-6.2 2.3zm44.2-1.7c-2.9 .7-4.9 2.6-4.6 4.9 .3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3 .7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3 .3 2.9 2.3 3.9 1.6 1 3.6 .7 4.3-.7 .7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3 .7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3 .7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"></path>
                        </svg>
                        @lang('Github')
                    </a>
                </div>
            </div>
        </x-modal>
    @endguest

    @auth
        <x-modal trigger="profile" :title="__('Olá, :name!', ['name' => $user->name])">
            <div class="mt-5 grid grid-cols-6 gap-2">
                <div class="col-span-full mb-4">
                    <x-profile-image :$user />
                </div>
                <div class="col-span-full">
                    <x-button block xs :href="route('me')">
                        @lang('Perfil')
                    </x-button>
                </div>
                @if (user()->is_admin)
                    <div class="col-span-full">
                        <x-button block xs :href="route('officine.index')" class="bg-red-500">
                            @lang('Painel Administrativo')
                        </x-button>
                    </div>
                @endif
            </div>
            <x-slot:footer>
                <x-button red :href="route('bye')">
                    @lang('Sair')
                </x-button>
            </x-slot:footer>
        </x-modal>
    @endauth

    @if (session()->has('onboarding'))
        <x-modal trigger="welcome"
                 :title="__('Bem-vindo ao :app, :name!', ['app' => config('app.name'), 'name' => $user->name])">
            Grill bitter blueberries in a jar with champaign for about an hour to chamfer their flavor.
        </x-modal>
    @endif
</div>
