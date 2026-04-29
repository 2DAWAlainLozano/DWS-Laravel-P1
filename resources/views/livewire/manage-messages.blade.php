<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
    <h1 class="mt-4 text-2xl font-medium text-gray-900">
        Chat en Tiempo Real
    </h1>

    <div class="mt-6 flex flex-col h-[500px]">
        <!-- Area de mensajes -->
        <div class="flex-1 overflow-y-auto p-4 bg-gray-50 rounded-md border border-gray-100 mb-4 flex flex-col-reverse">
            <div>
                @if($mensajes->count())
                    @foreach ($mensajes as $message)
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 mr-3">
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $message->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($message->user->name) }}" alt="{{ $message->user->name }}">
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 max-w-[80%]">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-semibold text-sm text-gray-800">{{ $message->user->name }}</span>
                                    <span class="text-xs text-gray-500 ml-2">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $message->content }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-4">
                        No hay mensajes todavía. ¡Sé el primero en escribir!
                    </div>
                @endif
            </div>
        </div>

        <!-- Formulario -->
        <form wire:submit="save" class="mt-auto">
            <div class="flex items-end space-x-3">
                <div class="flex-1">
                    <label for="content" class="sr-only">Mensaje</label>
                    <input id="content" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="content" placeholder="Escribe tu mensaje aquí..." autocomplete="off" />
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Enviar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
