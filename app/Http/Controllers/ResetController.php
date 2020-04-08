<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Foreach_;

class ResetController extends Controller
{
    public function reset()
    {
        Artisan::call('migrate:fresh --seed');

//        $folder = 'categories';
//        Storage::deleteDirectory($folder);
//        Storage::makeDirectory($folder);
//
//        $files = Storage::disk('reset')->files($folder);
//        foreach ($files as $file){
//         Storage::put($file, Storage::disk('reset')->get($file)); //название и содержимое
//        }

        foreach (['categories', 'products'] as $folder) {
        Storage::deleteDirectory($folder);
        Storage::makeDirectory($folder);

        $files = Storage::disk('reset')->files($folder);
        foreach ($files as $file){
         Storage::put($file, Storage::disk('reset')->get($file)); //название и содержимое
        }
        }

        session()->flash('success', 'Проэкт был сброшен в начальное состояние');
        return redirect()->route('index');
    }
}
