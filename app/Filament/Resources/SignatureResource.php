<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SignatureResource\Pages;
use App\Models\File;
use App\Models\Signature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SignatureResource extends Resource
{
    protected static ?string $model = Signature::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Arquivos';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Assinatura';

    protected static ?string $pluralModelLabel = 'Assinaturas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Documento')
                    ->schema([
                        Forms\Components\Select::make('file_id')
                            ->label('Documento')
                            ->relationship('file', 'name', modifyQueryUsing: fn (Builder $query) => $query->withoutTrashed())
                            ->getOptionLabelFromRecordUsing(fn (File $record) => "{$record->name} (ID: {$record->id})")
                            ->searchable(['name', 'id'])
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Assinante')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome Completo')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->maxLength(65535),
                        Forms\Components\TextInput::make('cpf')
                            ->label('CPF')
                            ->required()
                            ->maxLength(65535)
                            ->mask('999.999.999-99')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('file.name')
                    ->label('Documento')
                    ->sortable()
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('file.id')
                    ->label('ID Doc.')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->sortable()
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->sortable()
                    ->searchable()
                    ->limit(14),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Assinado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('file_id')
                    ->label('Documento')
                    ->relationship('file', 'name', modifyQueryUsing: fn (Builder $query) => $query->withoutTrashed())
                    ->getOptionLabelFromRecordUsing(fn (File $record) => "{$record->name} (ID: {$record->id})")
                    ->searchable()
                    ->preload()
                    ->multiple(),
                Tables\Filters\Filter::make('name')
                    ->label('Nome')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome contém')
                            ->placeholder('Parte do nome...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['name']),
                            fn (Builder $q) => $q->where('name', 'like', '%' . $data['name'] . '%'),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        return filled($data['name']) ? "Nome contém: {$data['name']}" : null;
                    }),
                Tables\Filters\Filter::make('email')
                    ->label('E-mail')
                    ->form([
                        Forms\Components\TextInput::make('email')
                            ->label('E-mail contém')
                            ->email()
                            ->placeholder('seu@email.com'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['email']),
                            fn (Builder $q) => $q->where('email', 'like', '%' . $data['email'] . '%'),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        return filled($data['email']) ? "E-mail contém: {$data['email']}" : null;
                    }),
                Tables\Filters\Filter::make('cpf')
                    ->label('CPF')
                    ->form([
                        Forms\Components\TextInput::make('cpf')
                            ->label('CPF contém')
                            ->mask('999.999.999-99')
                            ->placeholder('000.000.000-00'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['cpf']),
                            function (Builder $q) use ($data) {
                                $raw = preg_replace('/\D/', '', $data['cpf']);
                                $formatted = $data['cpf'];

                                return $q->where(function (Builder $subQ) use ($formatted, $raw) {
                                    $subQ
                                        ->where('cpf', 'like', '%' . $formatted . '%')
                                        ->orWhere('cpf', 'like', '%' . $raw . '%');
                                });
                            },
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        return filled($data['cpf']) ? "CPF contém: {$data['cpf']}" : null;
                    }),
                Tables\Filters\Filter::make('id')
                    ->label('ID Assinatura')
                    ->form([
                        Forms\Components\TextInput::make('id')
                            ->label('ID igual a')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['id']),
                            fn (Builder $q) => $q->where('id', '=', $data['id']),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        return filled($data['id']) ? "ID Assinatura = {$data['id']}" : null;
                    }),
                Tables\Filters\Filter::make('file_id_exact')
                    ->label('ID Documento')
                    ->form([
                        Forms\Components\TextInput::make('file_id')
                            ->label('ID Doc. igual a')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            filled($data['file_id']),
                            fn (Builder $q) => $q->where('file_id', '=', $data['file_id']),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        return filled($data['file_id']) ? "ID Documento = {$data['file_id']}" : null;
                    }),
                Tables\Filters\Filter::make('date_range')
                    ->label('Período de Assinatura')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('De'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                filled($data['from']),
                                fn (Builder $q) => $q->whereDate('created_at', '>=', $data['from']),
                            )
                            ->when(
                                filled($data['until']),
                                fn (Builder $q) => $q->whereDate('created_at', '<=', $data['until']),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $parts = [];
                        if (filled($data['from'] ?? null)) $parts[] = "De: {$data['from']}";
                        if (filled($data['until'] ?? null)) $parts[] = "Até: {$data['until']}";
                        return $parts ? implode(' | ', $parts) : null;
                    }),
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
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('60s');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('file');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignatures::route('/'),
            'create' => Pages\CreateSignature::route('/create'),
            'edit' => Pages\EditSignature::route('/{record}/edit'),
        ];
    }
}
