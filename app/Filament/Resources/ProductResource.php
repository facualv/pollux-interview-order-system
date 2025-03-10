<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Models\Product;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([
                        TextInput::make('name')
                            ->required()
                            ->unique()
                            ->live(),
                        TextInput::make('price')
                            ->required()
                            ->numeric(),
                        MarkdownEditor::make('description')->columnSpan('full'),
                    ])->columns(2),
                ])->columns(2),
                
                Group::make()->schema([
                    Section::make('Status')->schema([
                        TextInput::make('quantity')
                            ->required()
                            ->numeric(),
                        Toggle::make('is_available')
                            ->label('Available')
                            ->offColor('danger')
                            ->onColor('success')
                            ->helperText('Shows if the product is available for sale')
                    ])->columns(2),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('description')
                    ->toggleable(true),
                TextColumn::make('price')
                    ->sortable()->money(),
                TextColumn::make('quantity')
                    ->sortable(),
                IconColumn::make('is_available')
                    ->label('Availability')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_available')
                    ->boolean()
                    ->label('Availability')
                    ->trueLabel('Available products')
                    ->falseLabel('Unavailable products'),

            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
