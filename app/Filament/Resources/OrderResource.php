<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Filament\Exports\OrderExporter;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use App\Models\Product;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Details')->schema([
                        TextInput::make('number')
                            ->default('FCA-' . random_int(10000, 999999))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255),

                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

                        Select::make('type')->options([
                            'delivery' => OrderTypeEnum::DELIVERY->value,
                            'pickup' => OrderTypeEnum::PICKUP->value,
                        ])->required(),

                        Select::make('status')
                            ->options([
                                'received' => OrderStatusEnum::RECEIVED->value,
                                'canceled' => OrderStatusEnum::CANCELED->value,
                                'delivered' => OrderStatusEnum::DELIVERED->value,
                            ])
                            ->required()
                            ->default(OrderStatusEnum::RECEIVED->value)
                            ->selectablePlaceholder(false),

                    ]),

                    Step::make('Items')->schema([
                        Repeater::make('items')->relationship()->schema([
                            Select::make('product_id')
                                ->label('Product')
                                ->options(
                                    Product::query()->where('quantity', '>', 0)->pluck('name', 'id')
                                )
                                ->required()
                                ->reactive()
                                ->selectablePlaceholder()
                                ->afterStateUpdated(
                                    fn ($state, Forms\Set $set) => $set(
                                        'unit_price',
                                        Product::query()->find($state)?->price ?? 0
                                    )
                                ),

                            TextInput::make('quantity')
                                ->dehydrated()
                                ->numeric()
                                ->extraInputAttributes([
                                    'min' => 0,
                                ])
                                ->default(1)
                                ->required()
                                ->live()
                                ->rules([
                                    fn (Forms\Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                        $currentProductQuantity = Product::query()->find($get('product_id'))?->quantity;
                                        if ($currentProductQuantity < $value) {
                                            $fail("The quantity selected is greater than quantity available.");
                                        }
                                    },
                                ]),

                            TextInput::make('unit_price')
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            Placeholder::make('total_price')
                                ->label('Total Price')
                                ->content(function ($get) {
                                    return $get('quantity') * $get('unit_price');
                                })

                        ])->columns(4)
                    ])

                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type'),
                TextColumn::make('status'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'received' => OrderStatusEnum::RECEIVED->value,
                        'canceled' => OrderStatusEnum::CANCELED->value,
                        'delivered' => OrderStatusEnum::DELIVERED->value,
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                Action::make('Download Invoice')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Order $order): string => route('order.pdf.download', ['order' => $order]))
                    ->openUrlInNewTab(),
                ])
            ->bulkActions([
            ])
            ->headerActions([
                ExportAction::make()->exporter(OrderExporter::class),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Order Info')->schema([
                    TextEntry::make('number')->label('Order Number'),
                    TextEntry::make('user.name')->label('Created By'),
                    TextEntry::make('type')->label('Order Type'),
                    TextEntry::make('status'),
                    TextEntry::make('created_at')->dateTime(),
                ])->columns(5)
        ]);
    }
    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}/view'),
        ];
    }
}
