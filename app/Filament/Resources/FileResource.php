<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileResource\Pages;
use App\Models\File;
use App\Models\FileCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static ?string $navigationGroup = 'Arquivos';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Arquivo';

    protected static ?string $pluralModelLabel = 'Arquivos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Upload')
                    ->schema([
                        Forms\Components\FileUpload::make('path')
                            ->label('Arquivo')
                            ->required()
                            ->disk('public')
                            ->directory('uploads')
                            ->preserveFilenames()
                            ->maxSize(51200)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->maxLength(255)
                            ->helperText('Leave empty to use the uploaded filename.'),
                        Forms\Components\Select::make('category_id')
                            ->label('Categoria')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->nullable()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(40)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),               
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Deleted at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-circle')
                    ->color('success')
                    ->action(function (File $record) {
                        $fullPath = Storage::disk('public')->path($record->path);
                        if (file_exists($fullPath)) {
                            return Storage::disk('public')->download($record->path, $record->name . '.' . pathinfo($record->path, PATHINFO_EXTENSION));
                        }
                    })
                    ->visible(function (File $record) {
                        return $record->trashed() === false
                            && file_exists(Storage::disk('public')->path($record->path));
                    }),
                Tables\Actions\DeleteAction::make()
                    ->before(function (File $record) {
                        $fullPath = Storage::disk('public')->path($record->path);
                        if (file_exists($fullPath)) {
                            Storage::disk('public')->delete($record->path);
                        }
                    }),
                Tables\Actions\ForceDeleteAction::make()
                    ->before(function (File $record) {
                        $fullPath = Storage::disk('public')->path($record->path);
                        if (file_exists($fullPath)) {
                            Storage::disk('public')->delete($record->path);
                        }
                    }),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                $fullPath = Storage::disk('public')->path($record->path);
                                if (file_exists($fullPath)) {
                                    Storage::disk('public')->delete($record->path);
                                }
                            }
                        }),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                $fullPath = Storage::disk('public')->path($record->path);
                                if (file_exists($fullPath)) {
                                    Storage::disk('public')->delete($record->path);
                                }
                            }
                        }),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with('category', 'user');
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
        ];
    }
}
