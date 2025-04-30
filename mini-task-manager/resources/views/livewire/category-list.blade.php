{{-- resources/views/livewire/category-list.blade.php --}}
<div class="flex flex-col gap-6">
    {{-- Add Category Section --}}
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Añadir una Categoría</h2>
        <form wire:submit.prevent="createCategory" class="flex items-center space-x-3">
            <div class="flex-grow">
                <label for="newCategoryName" class="sr-only">Nombre de la Categoría</label>
                <input type="text" id="newCategoryName" wire:model.live="newCategoryName" placeholder="Nombre de la categoría" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                @error('newCategoryName') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">Agregar</button>
            </div>
        </form>
    </div>

    {{-- List of Categories Section --}}
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Lista de Categorías</h2>
        <ul>
            @forelse ($categories as $category)
                <li class="mb-2">
                     <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold text-white" style="background-color: {{ $category->color ?? '#007bff' }}">
                         {{ $category->name }}
                     </span>
                </li>
            @empty
                <li class="text-gray-500 text-sm">No hay categorías disponibles.</li>
            @endforelse
        </ul>
        {{-- Placeholder opcional para "Ver menos" --}}
        {{-- <div class="mt-4 text-center">
            <button class="text-blue-600 hover:underline text-sm focus:outline-none">Ver menos</button>
        </div> --}}
    </div>
</div>
