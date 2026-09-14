<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileCategoryResource\Pages;
use App\Models\FileCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FileCategoryResource extends Resource
{
    protected static ?string $model = FileCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Categorias';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Categoria';

    protected static ?string $pluralModelLabel = 'Categorias';

    public static function form(Form $form): Form
    {
        $colorOptions = [
            'from-indigo-500 to-blue-600' => 'Indigo → Azul',
            'from-emerald-500 to-teal-600' => 'Esmeralda → Verde-água',
            'from-rose-500 to-pink-600' => 'Rosa → Pink',
            'from-amber-500 to-orange-600' => 'Âmbar → Laranja',
            'from-violet-500 to-purple-600' => 'Violeta → Roxo',
            'from-lime-500 to-green-600' => 'Lima → Verde',
            'from-green-500 to-emerald-600' => 'Verde → Esmeralda',
            'from-sky-500 to-cyan-600' => 'Céu → Ciano',
            'from-slate-500 to-slate-600' => 'Cinza (padrão)',
        ];

        $iconBgOptions = [
            'bg-indigo-100 text-indigo-600' => 'Índigo',
            'bg-emerald-100 text-emerald-600' => 'Esmeralda',
            'bg-rose-100 text-rose-600' => 'Rosa',
            'bg-amber-100 text-amber-600' => 'Âmbar',
            'bg-violet-100 text-violet-600' => 'Violeta',
            'bg-lime-100 text-lime-600' => 'Lima',
            'bg-green-100 text-green-600' => 'Verde',
            'bg-sky-100 text-sky-600' => 'Céu',
            'bg-slate-100 text-slate-600' => 'Cinza (padrão)',
        ];

        $iconHints = collect([
            'fa-landmark', 'fa-scale-balanced', 'fa-bullhorn', 'fa-users',
            'fa-server', 'fa-sack-dollar', 'fa-leaf', 'fa-chart-line',
            'fa-folder', 'fa-file', 'fa-shield-halved', 'fa-book-open',
        ])->join(', ');

        return $form
            ->schema([
                Forms\Components\Section::make('Básico')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->nullable()
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Aparência')
                    ->description('Configurações visuais usadas na página inicial do site.')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Ícone (FontAwesome 6)')
                            ->nullable()
                            ->maxLength(191)
                            ->placeholder('fa-folder')
                            ->hint("Ex.: $iconHints")
                            ->hintColor('slate')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('color')
                            ->label('Cor do gradiente')
                            ->nullable()
                            ->options($colorOptions)
                            ->searchable(),
                        Forms\Components\Select::make('icon_bg')
                            ->label('Fundo do ícone (badges)')
                            ->nullable()
                            ->options($iconBgOptions)
                            ->searchable(),
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
                    ->wrap(),
                Tables\Columns\TextColumn::make('icon')
                    ->label('Ícone')
                    ->formatStateUsing(fn (?string $state): string => "<i class=\"fa-solid " . (filled($state) ? e($state) : 'fa-folder') . " text-xl\"></i>")
                    ->html()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('Cor')
                    ->formatStateUsing(fn (?string $state): string => "<div class=\"w-16 h-6 rounded bg-gradient-to-r " . (filled($state) ? e($state) : 'from-slate-500 to-slate-600') . " border border-slate-200 shadow-sm\"></div>")
                    ->html()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('icon_bg')
                    ->label('Fundo Ícone')
                    ->formatStateUsing(function (?string $state): string {
                        $bg = filled($state) ? $state : 'bg-slate-100 text-slate-600';
                        return "<span class=\"inline-flex items-center justify-center w-8 h-8 rounded {$bg}\"><i class=\"fa-solid fa-folder\"></i></span>";
                    })
                    ->html()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(60)
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('files_count')
                    ->label('Arquivos')
                    ->counts('files')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFileCategories::route('/'),
            'create' => Pages\CreateFileCategory::route('/create'),
            'edit' => Pages\EditFileCategory::route('/{record}/edit'),
        ];
    }
}
