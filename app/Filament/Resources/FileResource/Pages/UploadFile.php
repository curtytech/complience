<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Models\FileCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UploadFile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static string $view = 'filament.pages.upload-file';

    protected static ?string $navigationGroup = 'Files';

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('files')
                    ->label('Select Files')
                    ->required()
                    ->multiple()
                    ->disk('public')
                    ->directory('uploads')
                    ->preserveFilenames()
                    ->maxSize(51200)
                    ->storeFileNamesIn('original_names')
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label('Category')
                    ->options(fn () => FileCategory::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name_prefix')
                    ->label('Name Prefix (optional)')
                    ->maxLength(100),
                Textarea::make('description')
                    ->label('Default Description')
                    ->nullable()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        if (empty($data['files'])) {
            Notification::make()
                ->title('No files selected')
                ->danger()
                ->send();

            return;
        }

        $count = 0;

        DB::beginTransaction();

        try {
            foreach ($data['files'] as $index => $filePath) {
                $originalName = $data['original_names'][$index] ?? null;

                if (blank($originalName)) {
                    $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($filePath);
                    if (file_exists($fullPath)) {
                        $originalName = basename($filePath);
                    } else {
                        $originalName = basename($filePath);
                    }
                }

                $name = pathinfo($originalName, PATHINFO_FILENAME);

                if (filled($data['name_prefix'] ?? null)) {
                    $name = $data['name_prefix'] . ' - ' . $name;
                }

                \App\Models\File::create([
                    'user_id' => Auth::id(),
                    'category_id' => $data['category_id'],
                    'name' => $name,
                    'path' => $filePath,
                    'description' => $data['description'] ?? null,
                ]);

                $count++;
            }

            DB::commit();

            Notification::make()
                ->title('Upload complete')
                ->body("{$count} file(s) uploaded successfully.")
                ->success()
                ->send();

            $this->form->fill();

            $this->redirect(FileResource::getUrl('index'));
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Upload failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
