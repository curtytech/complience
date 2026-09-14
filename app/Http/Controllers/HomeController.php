<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = FileCategory::query()
            ->withCount('files')
            ->orderBy('id')
            ->get()
            ->map(function (FileCategory $cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'description' => $cat->description,
                    'color' => filled($cat->color) ? $cat->color : 'from-slate-500 to-slate-600',
                    'icon' => filled($cat->icon) ? $cat->icon : 'fa-folder',
                    'icon_bg' => filled($cat->icon_bg) ? $cat->icon_bg : 'bg-slate-100 text-slate-600',
                    'files_count' => $cat->files_count ?? 0,
                    'show_url' => route('categories.show', $cat),
                ];
            })
            ->values();

        $categoriesById = [];
        foreach ($categories as $c) {
            $categoriesById[$c['id']] = $c;
        }

        $categoriesByIdJson = json_encode($categoriesById, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR);

        return view('welcome', compact('categories', 'categoriesByIdJson'));
    }

    public function show(Request $request, FileCategory $fileCategory): View
    {
        if (! $fileCategory->exists) {
            throw new NotFoundHttpException();
        }

        $files = $fileCategory->files()
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

        $allCategories = FileCategory::query()
            ->orderBy('id')
            ->get(['id', 'name', 'color', 'icon', 'icon_bg'])
            ->map(function (FileCategory $cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'color' => filled($cat->color) ? $cat->color : 'from-slate-500 to-slate-600',
                    'icon' => filled($cat->icon) ? $cat->icon : 'fa-folder',
                    'icon_bg' => filled($cat->icon_bg) ? $cat->icon_bg : 'bg-slate-100 text-slate-600',
                    'show_url' => route('categories.show', $cat),
                ];
            })
            ->values();

        $currentCategory = [
            'id' => $fileCategory->id,
            'name' => $fileCategory->name,
            'description' => $fileCategory->description,
            'color' => filled($fileCategory->color) ? $fileCategory->color : 'from-slate-500 to-slate-600',
            'icon' => filled($fileCategory->icon) ? $fileCategory->icon : 'fa-folder',
            'icon_bg' => filled($fileCategory->icon_bg) ? $fileCategory->icon_bg : 'bg-slate-100 text-slate-600',
            'files_count' => $files->count(),
        ];

        return view('category.show', compact('files', 'allCategories', 'currentCategory'));
    }
}
