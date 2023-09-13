<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Blog Create')
                    ->description('Are you looking for writing amazing blog?')
                    ->schema([
                        Grid::make('')->schema([
                            TextInput::make('title')->autocomplete(false)->required()->columns(1),
                            ColorPicker::make('color')->columns(1),
                            MarkdownEditor::make('content')->required()->columnSpanFull(),
                            Select::make('category_id')
                                ->label('Categories')
                                ->relationship('categories', 'name')
                                ->multiple()
                                ->searchable()
                                ->columnSpanFull()->required(),
                            Checkbox::make('publish')->columnSpanFull(),
                        ])->columns(2)
                    ])->columnSpan(2),

                Grid::make('')->schema([
                    Section::make('Meta')
                        ->description('Upload your thumbnail.')
                        ->schema([
                            FileUpload::make('thumbnail')->disk('public')->directory('blogs')->required(),
                        ])->collapsible(),

                    Section::make('Author Info')
                        ->description('Detail Information')
                        ->schema([


                            Select::make('author_id')
                                ->label('Author')
                                ->relationship('authors', 'name')
                                ->multiple()
                                ->searchable()
                        ])
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('thumbnail'),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('authors.name')->sortable()->searchable(),
                TextColumn::make('categories.name')->sortable()->searchable(),
                ToggleColumn::make('publish')->onColor('success')
                    ->offColor('danger'),
                TextColumn::make('created_at')->date('d-m-Y'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
