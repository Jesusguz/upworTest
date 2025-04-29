{{-- resources/views/livewire/task-list.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 p-4 sm:p-6 lg:p-8">

    <div class="container mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Panel: Task List and Creation --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Add Task Section --}}
            <div class="bg-white p-6 rounded-lg shadow-md" x-data="{ show: @entangle('showCreateForm').live }">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Añadir una Tarea</h2>
                    {{-- CORRECCIÓN AQUÍ: Cambiado show = true a show = !show para que sea un toggle --}}
                    <button @click="show = !show" class="text-blue-600 hover:text-blue-800 focus:outline-none">
                        {{-- Icono + --}}
                        <svg x-show="!show" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        {{-- Icono X --}}
                        <svg x-show="show" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div x-show="show" x-transition.origin.top class="mt-4">
                    <form wire:submit.prevent="createTask">
                        <div class="mb-4">
                            <label for="newTaskTitle" class="sr-only">Título</label>
                            <input type="text" id="newTaskTitle" wire:model.live="newTaskTitle" placeholder="Título de la tarea" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('newTaskTitle') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="newTaskDescription" class="sr-only">Descripción (Opcional)</label>
                            <textarea id="newTaskDescription" wire:model.live="newTaskDescription" rows="2" placeholder="Descripción (Opcional)" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                            @error('newTaskDescription') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="newTaskCategory" class="sr-only">Categoría (Opcional)</label>
                            <select id="newTaskCategory" wire:model.live="newTaskCategory" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">-- Seleccionar Categoría --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('newTaskCategory') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="text-right">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Guardar Tarea</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Task List Section --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Tareas</h2>

                {{-- Filters --}}
                <div class="flex space-x-2 mb-4 text-sm text-gray-600">
                    <button wire:click="setFilter('all')" class="pb-1 {{ $filter === 'all' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">Todas</button>
                    <button wire:click="setFilter('pending')" class="pb-1 {{ $filter === 'pending' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">Pendientes</button>
                    <button wire:click="setFilter('completed')" class="pb-1 {{ $filter === 'completed' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">Completadas</button>
                    {{-- Placeholder for more filters --}}
                    {{-- <span class="pb-1 text-gray-400 cursor-not-allowed">...</span> --}}
                </div>

                {{-- Task List --}}
                <ul>
                    @forelse ($tasks as $task)
                        <li class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0">
                            <div class="flex items-center flex-grow mr-4">
                                <input type="checkbox" wire:click="toggleCompleted({{ $task->id }})" {{ $task->is_completed ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 mr-3">
                                <span class="text-gray-800 flex-grow {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                                    {{ $task->title }}
                                </span>
                                @if ($task->category)
                                    {{-- Category Badge (adjust colors based on category if you add a color field) --}}
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $task->category->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                {{-- Options icon - Placeholder for future dropdown or actions --}}
                                {{-- <button class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                     <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010 4zm0 6a2 2 0 110 4 2 2 0 010 4z"></path></svg>
                                </button> --}}

                                {{-- Edit Button (Triggers modal) --}}
                                <button @click="$wire.showTaskDetail({{ $task->id }})" class="text-gray-400 hover:text-blue-600 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path></svg>
                                </button>

                                {{-- Delete Button (Triggers confirmation modal) --}}
                                <button @click="$wire.confirmDelete({{ $task->id }})" class="text-gray-400 hover:text-red-600 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m14 0H5m10 4v6m-4-6v6"></path></svg>
                                </button>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500 text-center py-4">No hay tareas para mostrar.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Right Panels: Categories, Export, Task Details --}}
        <div class="lg:col-span-1 flex flex-col gap-6">

            {{-- Add Category Section (Optional, based on image) --}}
            {{-- You would implement this if you add category management --}}
            {{-- <div class="bg-white p-6 rounded-lg shadow-md">
               <h2 class="text-xl font-semibold text-gray-800 mb-4">Añadir una Categoría</h2>
                <input type="text" placeholder="Nombre de la categoría" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <button class="mt-3 w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Guardar Categoría</button>
            </div> --}}

            {{-- Export Buttons Section --}}
            <div class="bg-white p-6 rounded-lg shadow-md grid grid-cols-1 sm:grid-cols-2 gap-4">
                <button class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                    Exportar PDF
                </button>
                <button class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                    Exportar CSV
                </button>
            </div>

            {{-- Task Details Section (Mimicked by Modal below) --}}
            {{-- This panel can act as a placeholder or be used if you prefer a non-modal detail view --}}
            {{-- <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Detalles de la tarea</h2>
                 <p class="text-gray-600">Selecciona una tarea para ver sus detalles aquí.</p>
                 // Content will be loaded here when a task is clicked if not using a modal
            </div> --}}


            {{-- List of Categories Section --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Lista de Categorías</h2>
                {{-- Basic Category List (Mimics the visual style) --}}
                <ul>
                    @forelse ($categories as $category)
                        <li class="mb-2">
                             <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold text-white" style="background-color: {{ $category->color ?? '#007bff' }}"> {{-- Use category color if available --}}
                                 {{ $category->name }}
                             </span>
                        </li>
                    @empty
                        <li class="text-gray-500 text-sm">No hay categorías disponibles.</li>
                    @endforelse
                </ul>
                {{-- Placeholder for "Ver menos" --}}
                {{-- <div class="mt-4 text-center">
                    <button class="text-blue-600 hover:underline text-sm focus:outline-none">Ver menos</button>
                </div> --}}
            </div>
        </div>

    </div>

    {{-- Task Detail/Edit Modal (x-show controlled by Livewire's showTaskDetailModal) --}}
    {{-- Use Alpine.js x-data and x-show linked to Livewire component property --}}
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4"
         x-data="{ open: @entangle('showTaskDetailModal').live }"
         x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         style="display: none;"> {{-- Hide initially --}}

        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md md:max-w-lg lg:max-w-xl p-6"
             @click.away="$wire.closeTaskDetail()"> {{-- Close modal when clicking outside --}}

            <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ $editingTaskId ? 'Editar Tarea' : 'Detalle de Tarea' }}</h3>

            @if($editingTaskId)
                {{-- Edit Form --}}
                <form wire:submit.prevent="updateTask">
                    <div class="mb-4">
                        <label for="editingTaskTitle" class="block text-sm font-medium text-gray-700">Título</label>
                        <input type="text" id="editingTaskTitle" wire:model.live="editingTaskTitle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('editingTaskTitle') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="editingTaskDescription" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="editingTaskDescription" wire:model.live="editingTaskDescription" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                        @error('editingTaskDescription') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="editingTaskCategory" class="block text-sm font-medium text-gray-700">Categoría (Opcional)</label>
                        <select id="editingTaskCategory" wire:model.live="editingTaskCategory" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">-- Seleccionar Categoría --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('editingTaskCategory') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="$wire.closeTaskDetail()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Guardar Cambios</button>
                    </div>
                </form>
            @else
                {{-- Task Details Display (if not in editing mode, although the current logic jumps straight to edit) --}}
                @if ($selectedTask)
                    <p class="text-gray-700 mb-2"><strong>Título:</strong> {{ $selectedTask->title }}</p>
                    <p class="text-gray-700 mb-4"><strong>Descripción:</strong> {{ $selectedTask->description ?? 'Sin descripción' }}</p>
                    <p class="text-gray-700 mb-4"><strong>Estado:</strong> {{ $selectedTask->is_completed ? 'Completada' : 'Pendiente' }}</p>
                    @if ($selectedTask->category)
                        <p class="text-gray-700 mb-4"><strong>Categoría:</strong> {{ $selectedTask->category->name }}</p>
                    @endif
                @else
                    <p class="text-gray-600">Cargando detalles...</p> {{-- Should ideally not happen if modal is shown only with a selected task --}}
                @endif
                <div class="flex justify-end">
                    <button type="button" @click="$wire.closeTaskDetail()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Cerrar</button>
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Modal (x-show controlled by Livewire's showDeleteConfirmationModal) --}}
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4"
         x-data="{ open: @entangle('showDeleteConfirmationModal').live }"
         x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         style="display: none;"> {{-- Hide initially --}}

        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-sm p-6"
             @click.away="$wire.cancelDelete()"> {{-- Close modal when clicking outside --}}

            <h3 class="text-xl font-semibold text-gray-800 mb-4">Confirmar Eliminación</h3>
            <p class="text-gray-700 mb-6">¿Estás seguro de que deseas eliminar esta tarea? Esta acción no se puede deshacer.</p>

            <div class="flex justify-end space-x-3">
                <button type="button" @click="$wire.cancelDelete()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Cancelar</button>
                <button type="button" wire:click="deleteTask()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">Eliminar</button>
            </div>
        </div>
    </div>


    {{-- Toast Notifications Area (Listener added in app.blade.php) --}}
    <div class="fixed bottom-4 right-4 z-50">
        {{-- Toasts will appear here, managed by the JS listener --}}
    </div>


    {{-- Placeholder for Dark Mode Toggle --}}
    {{-- You would typically add a button here and use Alpine.js/localStorage to toggle the 'dark' class on the <html> element --}}
    {{-- <div class="fixed top-4 right-4 z-50">
        <button class="p-2 rounded-full bg-gray-800 text-white dark:bg-gray-200 dark:text-gray-800 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        </button>
    </div> --}}


</div>
