<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateFile extends CreateRecord
{
    protected static string $resource = FileResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        if (filled($data['path'] ?? null) && blank($data['name'] ?? null)) {
            $fullPath = Storage::disk('public')->path($data['path']);
            if (file_exists($fullPath)) {
                $filename = basename($data['path']);
                $data['name'] = pathinfo($filename, PATHINFO_FILENAME);
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
