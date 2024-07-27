<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <h1 class="text-2xl font-bold">Todo List</h1>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-label for="password" value="{{ __('Пароль') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Подтвердить') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
