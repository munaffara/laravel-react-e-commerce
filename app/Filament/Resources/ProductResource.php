<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\ProductStatusEnum;
use App\RolesEnum;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-s-queue-list';

    // Change the navigation position
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->live(onBlur: true)
                            ->required()
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                $set('slug', Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required(),
                        Forms\Components\Select::make('department_id')
                            ->relationship('department', 'name')
                            ->label(__('Department'))
                            ->preload()
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set) {
                                $set('category_id', null);
                            }),
                        Forms\Components\Select::make('category_id')
                            ->relationship(
                                'category',
                                'name',
                                function (Builder $query, callable $get) {
                                    $departmentId = $get('department_id');
                                    if ($departmentId) {
                                        $query->where('department_id', $departmentId);
                                    }
                                }
                                )
                            ->label(__('Category'))
                            ->preload()
                            ->searchable()
                            ->required(),
                        ]),
                Forms\Components\RichEditor::make('description')
                    ->label(__('Description'))
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'link',
                        'bulletedList',
                        'h2',
                        'h3',
                        'orderedList',
                        'redo',
                        'undo',
                        'table',
                        'italic',
                        'underline',
                        'strike',
                        'code',
                        'numberedList',
                        'blockquote',
                        'codeBlock',
                    ])
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('price')
                    ->label(__('Price'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->label(__('Quantity'))
                    ->integer(),
                Forms\Components\Select::make('status')
                    ->options(ProductStatusEnum::label())
                    ->default(ProductStatusEnum::Draft->value)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                     ->collection('images')
                     ->limit(1)
                     ->conversion('thumb')
                     ->label(__('Image')),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->words(10)
                    ->label(__('Product Name')),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('Department')),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('Category')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('Created At')),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors(ProductStatusEnum::colors())
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(ProductStatusEnum::label()),
                SelectFilter::make('department_id')
                    ->relationship('department', 'name')
                    ->label(__('Department')),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'images' => Pages\ProductImages::route('/{record}/images'),
        ];
    }

    // add this method to get the sub navigation for the edit page
    public static function getRecordSubNavigation(Page $page): array
    {
        return
            $page->generateNavigationItems([
                EditProduct::class,
                Pages\ProductImages::class,

            ]);

    }

    // this method is used only admin role, cannot to access the vendor role
    public static function canViewAny(): bool
    {
        $user = Filament::auth()->user();
        return $user && $user->hasRole(RolesEnum::VENDOR);
    }
}
