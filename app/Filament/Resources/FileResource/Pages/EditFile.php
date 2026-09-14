<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditFile extends EditRecord
{
    protected static string $resource = FileResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (filled($data['path'] ?? null) && blank($data['name'] ?? null)) {
            $fullPath = Storage::disk('public')->path($data['path']);
            if (file_exists($fullPath)) {
                $filename = basename($data['path']);
                $data['name'] = pathinfo($filename, PATHINFO_FILENAME);
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function ($record) {
                    $path = $record->path;
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                    }
                }),
            Actions\ForceDeleteAction::make()
                ->before(function ($record) {
                    $path = $record->path;
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                    }
                }),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
