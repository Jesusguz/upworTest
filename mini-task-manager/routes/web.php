<?php

use Illuminate\Support\Facades\Route;

use App\Http\Livewire\TaskList;

Route::get('/', TaskList::class);
