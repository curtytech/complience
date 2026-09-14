<?php

use App\Http\Controllers\HomeController;
use App\Models\File;
use App\Models\FileCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categorias/{fileCategory}', [HomeController::class, 'show'])->name('categories.show');

Route::get('/api/categories', function (Request $request) {
    $data = FileCategory::query()
        ->withCount('files')
        ->orderBy('id')
        ->get(['id', 'name', 'description', 'color', 'icon', 'icon_bg'])
        ->map(function (FileCategory $c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'description' => $c->description,
                'color' => filled($c->color) ? $c->color : 'from-slate-500 to-slate-600',
                'icon' => filled($c->icon) ? $c->icon : 'fa-folder',
                'icon_bg' => filled($c->icon_bg) ? $c->icon_bg : 'bg-slate-100 text-slate-600',
                'files_count' => $c->files_count ?? 0,
                'show_url' => route('categories.show', $c),
            ];
        })
        ->values();

    return response()->json($data);
});

Route::get('/api/categories/{categoryId}/files', function (Request $request, int $categoryId) {
    $category = FileCategory::query()->find($categoryId);

    if (! $category) {
        return response()->json(['message' => 'Categoria não encontrada'], 404);
    }

    $files = $category->files()
        ->with('user:id,name')
        ->latest()
        ->get()
        ->map(function (File $file) {
            $ext = pathinfo($file->path, PATHINFO_EXTENSION);
            $displayName = $ext ? $file->name . '.' . $ext : $file->name;

            return [
                'id' => $file->id,
                'category_id' => $file->category_id,
                'name' => $file->name,
                'display_name' => $displayName,
                'description' => $file->description,
                'extension' => $ext,
                'url' => asset('storage/' . $file->path),
                'user_name' => $file->user?->name,
                'created_at' => $file->created_at?->format('d/m/Y H:i'),
                'updated_at' => $file->updated_at?->format('d/m/Y H:i'),
            ];
        })
        ->values();

    return response()->json($files);
});
