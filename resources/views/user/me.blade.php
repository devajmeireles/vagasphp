<x-app-layout>
    <div class="mb-2">
        <x-back-button :route="route('index')"/>
    </div>
    <div class="mb-2">
        <x-alert yellow center>
            @lang('<b>Atenção!</b> Seu perfil não é editável porque os dados são sincronizados com o provedor de acesso quando você faz login.')
        </x-alert>
    </div>
    <x-tabs selected="Perfil" :options="[__('Perfil'), __('Candidaturas')]">
        <x-tabs.body section="Perfil">
            <div class="flex justify-between">
                <div class="flex-1">
                    <x-badge :label="$user->login()"/>
                </div>
                <livewire:user.resume-component :user="$user"/>
            </div>
            <div class="space-y-2">
                <div class="flex justify-center items-center">
                    <x-profile-image :$user />
                </div>
                @if ($user->provider === \App\Enums\SocialiteProvider::GitHub)
                    @php ($username = $user->username)
                    <div class="flex justify-center">
                        <a href="{{ sprintf('https://github.com/%s', $username) }}" target="_blank">
                            <x-svg.github class="inline-flex h-5 w-5"/>
                            <x-badge :label="$username"/>
                        </a>
                    </div>
                @endif
            </div>
            <div class="grid grid-cols-6 gap-2">
                <div class="col-span-full sm:col-span-2">
                    <x-input name="name" label="Nome" value="{{ $user->name }}" disabled/>
                </div>
                <div class="col-span-full sm:col-span-2">
                    <x-input name="name" label="E-mail" value="{{ $user->email }}" disabled/>
                </div>
                <div class="col-span-full sm:col-span-2">
                    <x-input name="name" label="Telefone" value="{{ $user->phone ?? 'N/A' }}" disabled/>
                    <x-tooltip text="Você pode atualizar o seu telefone no momento em que estiver se candidatando para uma vaga" class="text-red-500" />
                </div>
            </div>
            <livewire:user.destroy-profile-component :user="$user"/>
        </x-tabs.body>
        <x-tabs.body section="Candidaturas">
            <livewire:user.candidacy-component :user="$user" />
        </x-tabs.body>
    </x-tabs>
</x-app-layout>
