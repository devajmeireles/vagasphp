@component('mail::message')
# Olá, {{ $user->name }}! Tudo bem?

Bem-vindo ao {{ config('app.name') }}! Esperamos conseguir te ajudar a encontrar uma oportunidade de trabalho.

**Obs.:**
- Sugerimos que leia os termos de uso para entendermos a missão da plataforma.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
