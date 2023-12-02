<?php

namespace App\Filament\Resources;



use App\Filament\Resources\PostResource\Pages;

use App\Models\Blog\Post;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Support\Str;

use Filament\Resources\Resource;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\Layout\Panel;

use Filament\Resources\Concerns\Translatable;
use Filament\SpatieLaravelTranslatablePlugin;

class PostResource extends Resource
{
    use Translatable;

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Posts';


    public static function form(Form $form): Form
    {

        return $form
            ->columns(3)
            ->schema([

                Forms\Components\Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Forms\Components\Section::make('Information')
                            ->collapsible()
                            ->columns()
                            ->schema([
                                TextInput::make('title')
                                    ->autofocus()
                                    ->required()
                                    ->debounce(),
//                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                TextInput::make('slug')
//                                    ->readOnly()
                                    ->required(),
                                Textarea::make('excerpt')
                                    ->columnSpan(2),

                                RichEditor::make('content')
                                    ->toolbarButtons([
                                        'h1',
                                        'h2',
                                        'h3',
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'strike',
                                        'underline',
                                        'redo',
                                        'undo',
                                    ])
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('posts')
                                    ->columnSpan(2)
                            ]),
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Metadata')
                            ->collapsible()
                            ->schema([
                                Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('user', 'name')
                                    ->default(fn() => auth()->id())
                                    ->required(),
                                Select::make('category_id')
                                    ->searchable()
                                    ->relationship('category', 'name')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->debounce()
                                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                            ->required(),
                                       TextInput::make('slug')
                                            ->readOnly()
                                            ->unique()
                                            ->required(),
                                    ]),
//                                Forms\Components\Select::make('tags')
//                                    ->multiple()
//                                    ->relationship('tags', 'name'),
                                DateTimePicker::make('published_at')
                                    ->label('Published At'),
                                TextInput::make('reading_time')
                                    ->numeric()
                                    ->label('Reading Time'),
                                FileUpload::make('cover')
                                    ->label('Cover Image')
                                    ->directory('posts')
                                    ->disk('public')
                                    ->image('https://posts.peakpx.com/wallpaper/102/801/HD-wallpaper-olymus-kinda-adsf-asdfsfa-asdfasdfa-sfgha.jpg'),
                                TextInput::make('alt_image')
                                    ->label('Alt'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
//                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('category.name'),
//                Tables\Columns\TextColumn::make('tags.name')
//                    ->badge()
//                    ->color(Color::Teal)
//                    ->alignCenter(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->alignCenter(),
//                Tables\Columns\TextColumn::make('content'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugin(SpatieLaravelTranslatablePlugin::make()
                ->defaultLocales(['en', 'es']));
    }
}
