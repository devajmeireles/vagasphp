@props(['user'])

@php /** @var \App\Models\User $user */ @endphp

<img src="{{ $user->avatar }}"
     alt="{{ $user->name }}"
     class="h-32 w-32 rounded-full border-4 border-primary dark:border-white border-opacity-25 dark:border-opacity-10 mx-auto">
