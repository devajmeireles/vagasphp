@php /** @var \App\Models\Job $job */ @endphp

@component('mail::message')
# Olá, {{ $job->user->name }}! Tudo bem?

Recebemos o seu anúncio: "{{ $job->title }}". A partir de agora iremos realizar uma breve analise e em breve retornaremos com uma atualização sobre.

**Enquanto isso você pode visualizar o anúncio:**
@component('mail::button', ['url' => route('job.view', $job)])
    Visualizar Agora
@endcomponent

**Obs.:**
- Tenha em mente que o anúncio só estará visível e acessível a demais usuário após a conclusão da analise.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
