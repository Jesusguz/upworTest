{{-- resources/views/livewire/task-pdf.blade.php --}}
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Tareas</title>
    {{-- Puedes incluir CSS interno aquí si es necesario para el PDF --}}
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .completed { text-decoration: line-through; color: gray; }
        .category { font-size: 0.8em; color: #007bff; }
    </style>
</head>
<body>
<h1>Reporte de Tareas (Filtro: {{ $filter }})</h1>

<table>
    <thead>
    <tr>
        <th>Título</th>
        <th>Descripción</th>
        <th>Estado</th>
        <th>Categoría</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($tasks as $task)
        <tr>
            <td class="{{ $task->is_completed ? 'completed' : '' }}">{{ $task->title }}</td>
            <td>{{ $task->description ?? 'N/A' }}</td>
            <td>{{ $task->is_completed ? 'Completada' : 'Pendiente' }}</td>
            <td>
                @if ($task->category)
                    <span class="category">{{ $task->category->name }}</span>
                @else
                    Sin categoría
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" style="text-align: center;">No hay tareas para mostrar con este filtro.</td>
        </tr>
    @endforelse
    </tbody>
</table>

<p>Reporte generado el: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
