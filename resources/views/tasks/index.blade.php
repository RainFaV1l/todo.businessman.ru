<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Todo List') }}
            </h2>
            <p>Планируй задачи вместе с нами!</p>
        </div>
    </x-slot>

    <div class="relative font-inter antialiased">
        <main class="relative flex flex-col justify-center bg-slate-50 overflow-hidden">
            <div class="w-full max-w-7xl px-4 sm:px-6 lg:px-8 mx-auto py-24">
                <div x-data="{ isAnnual: 1, id: null, show: false, name: '' }">
                    <div class="flex justify-center max-w-[14rem] m-auto mb-8 lg:mb-16">
                        <div class="relative flex w-full p-1 bg-white rounded-full">
                            <span class="absolute inset-0 m-1 pointer-events-none" aria-hidden="true">
                                <span class="absolute inset-0 w-1/2 bg-indigo-500 rounded-full shadow-sm shadow-indigo-950/10 transform transition-transform duration-150 ease-in-out" :class="{'translate-x-[-30px]' : isAnnual === 1, 'translate-x-[140px]' : isAnnual === 2, 'translate-x-[315px]' : isAnnual === 3}"></span>
                            </span>
                            <div class="flex items-center gap-[100px]">
                                <button class="relative flex-1 text-sm font-medium h-8 rounded-full focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 dark:focus-visible:ring-slate-600 transition-colors duration-150 ease-in-out" :class="isAnnual === 1 ? 'text-white' : 'text-slate-500'" @click="isAnnual = 1" :aria-pressed="isAnnual === 1">Задачи</button>
                                <button class="relative flex-1 text-sm font-medium h-8 rounded-full focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 dark:focus-visible:ring-slate-600 transition-colors duration-150 ease-in-out" :class="isAnnual === 2 ? 'text-white' : 'text-slate-500'" @click="isAnnual = 2" :aria-pressed="isAnnual === 2">Редактировать</button>
                                <button class="relative flex-1 text-sm font-medium h-8 rounded-full focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 dark:focus-visible:ring-slate-600 transition-colors duration-150 ease-in-out" :class="isAnnual === 3 ? 'text-white' : 'text-slate-500'" @click="isAnnual = 3" :aria-pressed="isAnnual === 3">Создать</button>
                            </div>
                        </div>
                    </div>
                    <div x-show="isAnnual === 1" class="w-full mx-auto items-start">
                        <form action="/" method="get" class="mb-8 flex flex-col gap-2">
                            <label class="font-medium" for="date">Сортировка по дате</label>
                            <input onchange="this.form.submit()" id="date" name="date" value="{{ request()->get('date') }}" placeholder="Введите дату в формате (01.11.20)" class="w-full inline-flex justify-center whitespace-nowrap rounded-lg px-3.5 py-2.5 text-sm font-medium text-black shadow-sm shadow-indigo-950/10 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150"/>
                        </form>
                        <div class="w-full h-full">
                            <div class="w-full relative grid grid-cols-3 gap-[30px] h-full">
                                @forelse($tasks as $task)
                                    <div class="relative mb-5 {{ $task->status === 'Выполнено' ? 'bg-red-500 text-white' : 'bg-white text-slate-500' }} p-6 border border-slate-200 shadow shadow-slate-950/5 rounded-2xl">
                                        <div class="font-semibold mb-1 {{ $task->status === 'Выполнено' ? 'text-white' : 'text-slate-900' }} {{ $task->status === 'Просрочено' ? 'line-through' : '' }}">Дедлайн: {{ date('d.m.y', strtotime($task->expired_at)) }}</div>
                                        <div class="text-sm mb-3 {{ $task->status === 'Просрочено' ? 'line-through' : '' }}">Дата создания: {{ date('d.m.y', strtotime($task->created_at)) }}</div>
                                        <div class="text-sm mb-5 {{ $task->status === 'Просрочено' ? 'line-through' : '' }}">{{ $task->name }}</div>
                                        <div class="flex flex-col gap-3">
                                            <p class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-indigo-500 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150" href="#0">
                                                {{ $task->status }}
                                            </p>
                                            <div class="flex items-center gap-[30px]">
                                                <form class="w-full" action="{{ route('accept', $task->id) }}" method="post">
                                                    @csrf
                                                    <button class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-green-500 hover:bg-green-600 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150" type="submit">Выполнено</button>
                                                </form>
                                                <form action="{{ route('reject', $task->id) }}" method="post">
                                                    @csrf
                                                    <button class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-red-500 hover:bg-red-600 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150" type="submit">Просрочено</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="font-medium bg-gray-100 p-4 rounded border border-gray-300">У вас нет заметок. Добавьте заметку в разделе создать.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div x-show="isAnnual === 2" class="w-full mx-auto items-start">
                        <form action="/" method="get" class="mb-8 flex flex-col gap-2">
                            <label class="font-medium" for="date">Сортировка по дате</label>
                            <input onchange="this.form.submit()" id="date" name="date" value="{{ request()->get('date') }}" placeholder="Введите дату" class="w-full inline-flex justify-center whitespace-nowrap rounded-lg px-3.5 py-2.5 text-sm font-medium text-black shadow-sm shadow-indigo-950/10 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150"/>
                        </form>
                        <div x-show="show" class="mb-8 flex flex-col items-start gap-2">
                            <label class="font-medium" for="date">Редактирование даты</label>
                            <input x-model="name" id="date" name="date" value="name" placeholder="Редактирование даты" class="w-full inline-flex justify-center whitespace-nowrap rounded-lg px-3.5 py-2.5 text-sm font-medium text-black shadow-sm shadow-indigo-950/10 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150"/>
                            <div class="w-full flex justify-end">
                                <button @click="await axios.post(`/${id}/update`, {id, name}); location.reload()" class="whitespace-nowrap rounded-lg bg-green-500 hover:bg-green-600 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150" type="submit">Сохранить</button>
                            </div>
                        </div>
                        <div class="w-full h-full">
                            <div class="w-full relative grid grid-cols-3 gap-[30px] h-full">
                                @forelse($tasks as $task)
                                    <div class="mb-5 bg-white p-6 border border-slate-200 shadow shadow-slate-950/5 rounded-2xl">
                                        <div class="text-slate-900 font-semibold mb-1">Дедлайн: {{ date('d.m.y', strtotime($task->expired_at)) }}</div>
                                        <div class="text-sm text-slate-500 mb-3">Дата создания: {{ date('d.m.y', strtotime($task->created_at)) }}</div>
                                        <div class="text-sm text-slate-500 mb-5">{{ $task->name }}</div>
                                        <div class="flex flex-col gap-[15px]">
                                            <p class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-indigo-500 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150">
                                                {{ $task->status }}
                                            </p>
                                            <div class="flex items-center gap-[30px]">
                                                <button @click="show=true; id='{{ $task->id }}'; name='{{ $task->name }}'" class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-green-500 hover:bg-green-600 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150">
                                                    Редактировать
                                                </button>
                                                <form action="{{ route('destroy', $task->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-red-500 hover:bg-red-600 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150" type="submit">Удалить</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="font-medium bg-gray-100 p-4 rounded border border-gray-300">У вас нет заметок. Добавьте заметку в разделе создать.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div x-show="isAnnual === 3" class="">
                        <x-slot name="logo">
                            <x-authentication-card-logo />
                        </x-slot>

                        <x-validation-errors class="mb-4" />

                        @session('status')
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ $value }}
                        </div>
                        @endsession

                        <form method="POST" action="{{ route('store') }}">
                            @csrf

                            <div>
                                <x-label for="name" value="{{ __('Название') }}" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <x-button class="ms-4">
                                    {{ __('Добавить задачу') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
