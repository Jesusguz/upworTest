<div class="min-h-screen bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 p-4 sm:p-6 lg:p-8">

    <div class="container mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Panel: Task List and Creation --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Añadir una Tarea</h2>
                <form wire:submit.prevent="createTask" class="flex items-center space-x-3">
                    <div class="flex-grow">
                        <label for="newTaskTitle" class="sr-only">Título</label>
                        <input type="text" id="newTaskTitle" wire:model.live="newTaskTitle" placeholder="Título de la tarea" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('newTaskTitle') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Agregar</button>
                    </div>
                </form>
            </div>

            {{-- Task List Section --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Tareas</h2>

                {{-- Filters --}}
                <div class="flex space-x-2 mb-4 text-sm text-gray-600">
                    <button wire:click="setFilter('all')" class="pb-1 {{ $filter === 'all' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">Todas</button>
                    <button wire:click="setFilter('pending')" class="pb-1 {{ $filter === 'pending' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">Pendientes</button>
                    <button wire:click="setFilter('completed')" class="pb-1 {{ $filter === 'completed' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">Completadas</button>
                </div>
                <ul x-data="{ }"
                    x-init="
        new Sortablejs(
        $el,{
            animation: 150,
            handle: '.drag-handle',
            onEnd: function(evt) {
            const newOrder = Array.from(evt.to.children).map(item => {
                return parseInt(item.getAttribute('data-task-id'));
            });
            @this.dispatch('task-reordered', { order: newOrder });
                // --- Fin CORRECCIÓN FINAL ---
            }
        }
    )
    ">
                    @forelse ($tasks as $task)
                        <li wire:key="{{ $task->id }}"
                            data-task-id="{{ $task->id }}"
                            class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0 hover:bg-gray-50"> {{-- Clase base para el estilo del elemento de lista. cursor: pointer se elimina si solo el handle/icono es interactivo --}}

                            <div class="flex items-center flex-grow mr-4">

                                <span class="drag-handle cursor-grab text-gray-400 hover:text-gray-600 mr-3">
                     <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010 4zm0 6a2 2 0 110 4 2 2 0 010 4z"></path></svg>
                </span>
                                <input type="checkbox" wire:click.stop="toggleCompleted({{ $task->id }})" {{ $task->is_completed ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 mr-3">
                                <span class="text-gray-800 flex-grow {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                    {{ $task->title }}
                </span>

                                {{-- Mostrar categoría si existe --}}
                                @if ($task->category)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                          style="background-color: {{ $task->category->color }}; color: #ffffff;"> {{-- background-color usa el color de la categoría, color del texto es blanco --}}
                        {{ $task->category->name }}
                    </span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                {{-- --- ICONO PARA VER DETALLES / EDITAR TAREA --- --}}
                                <span @click.stop="$wire.showTaskDetail({{ $task->id }})" class="cursor-pointer text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                </span>
                                <span wire:click.stop="confirmDelete({{ $task->id }})" class="cursor-pointer text-red-600 hover:text-red-800 ml-2"> {{-- ml-2 añade un pequeño margen a la izquierda --}}
                                    {{-- Icono de papelera --}}
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.725-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                </span>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500 text-center py-4">No hay tareas para mostrar.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Right Panels: Categories, Export --}}
        <div class="lg:col-span-1 flex flex-col gap-6">
            <livewire:category-list />

            {{-- Export Buttons Section --}}
            <div class="bg-white p-6 rounded-lg shadow-md grid grid-cols-1 sm:grid-cols-2 gap-4">
                <button wire:click="exportPdf" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                    Exportar PDF
                </button>
                <button wire:click="exportCsv" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                    Exportar CSV
                </button>
            </div>
        </div>

    </div>

    {{-- Task Detail/Edit Modal --}}
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4"
         x-data="{ open: @entangle('showTaskDetailModal').live }"

         x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         style="display: none;">

        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md md:max-w-lg lg:max-w-xl p-6"
             @click.away="$wire.closeTaskDetail()">

            <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ $editingTaskId ? 'Detalle de Tarea' : 'Detalle de Tarea' }}</h3>

            {{-- Formulario de edición, solo se muestra si editingTaskId tiene valor --}}
            @if($editingTaskId) {{-- Muestra este bloque si editingTaskId tiene un valor (estamos editando) --}}
            <form wire:submit.prevent="updateTask"> {{-- Al enviar el formulario, llama al método updateTask --}}
                <div class="mb-4">
                    <label for="editingTaskTitle" class="block text-sm font-medium text-gray-700">Título</label>
                    {{-- wire:model.live enlaza el input a la propiedad editingTaskTitle  --}}
                    <input type="text" id="editingTaskTitle" wire:model.live="editingTaskTitle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('editingTaskTitle') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="editingTaskDescription" class="block text-sm font-medium text-gray-700">Descripción</label>
                    {{-- wire:model.live enlaza esta textarea a la propiedad editingTaskDescription  --}}
                    <textarea id="editingTaskDescription" wire:model.live="editingTaskDescription" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                    @error('editingTaskDescription') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="editingTaskCategory" class="block text-sm font-medium text-gray-700">Categoría (Opcional)</label>
                    {{-- wire:model.live enlaza este select a la propiedad editingTaskCategory --}}
                    <select id="editingTaskCategory" wire:model.live="editingTaskCategory" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">-- Seleccionar Categoría --</option>
                        @foreach($categoriesForSelectors as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('editingTaskCategory') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end space-x-3">

                    <button type="button" @click="$wire.closeTaskDetail()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Cancelar</button>
                    {{-- Botón Guardar Cambios: type="submit" para enviar el formulario --}}
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Guardar Cambios</button>
                </div>
            </form>
            @else
            @if ($selectedTask)
                <p class="text-gray-700 mb-2"><strong>Título:</strong> {{ $selectedTask->title }}</p>
                <p class="text-gray-700 mb-4"><strong>Descripción:</strong> {{ $selectedTask->description ?? 'Sin descripción' }}</p>
                <p class="text-gray-700 mb-4"><strong>Estado:</strong> {{ $selectedTask->is_completed ? 'Completada' : 'Pendiente' }}</p>
                @if ($selectedTask->category)
                    <p class="text-gray-700 mb-4"><strong>Categoría:</strong> {{ $selectedTask->category->name }}</p>
                @endif
                <div class="flex justify-end">
                    <button type="button" @click="$wire.closeTaskDetail()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Cerrar</button>
                </div>
            @else
                <p class="text-gray-600">Cargando detalles...</p>
            @endif
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}

    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4"
         x-data="{ open: @entangle('showDeleteConfirmationModal').live }"

         x-show="open" {{-- Muestra/oculta el modal basado en la propiedad 'open'  --}}
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         style="display: none;">

        {{-- Contenedor interno del modal de confirmación --}}
        {{-- @click.away cierra el modal llamando a cancelDelete --}}
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-sm p-6"
             @click.away="$wire.cancelDelete()">

            <h3 class="text-xl font-semibold text-gray-800 mb-4">Confirmar Eliminación</h3>
            <p class="text-gray-700 mb-6">¿Estás seguro de que deseas eliminar esta tarea? Esta acción no se puede deshacer.</p>

            <div class="flex justify-end space-x-3">
                {{-- Botón Cancelar, llama a cancelDelete --}}
                <button type="button" @click="$wire.cancelDelete()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Cancelar</button>
                {{-- --- BOTÓN ELIMINAR en el modal de confirmación --- --}}
                {{-- wire:click llama al método deleteTask para ejecutar la eliminación real --}}
                <button type="button" wire:click="deleteTask()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">Eliminar</button>
            </div>
        </div>
    </div>
    {{-- Toast Notifications Area (SweetAlert2) --}}
    <div class="fixed bottom-4 right-4 z-50">
    </div>

    {{-- Placeholder opcional para Dark Mode Toggle --}}
{{-- <div class="fixed top-4 right-4 z-50">
    <button class="p-2 rounded-full bg-gray-800 text-white dark:bg-gray-200 dark:text-gray-800 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
    </button>
</div>--}}


</div>
