<?php

namespace App\Filament\Resources;
use Filament\Resources\Concerns\Translatable;
use Filament\SpatieLaravelTranslatablePlugin;
use App\Filament\Forms\Components\FileUploadMy;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Blog\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Filament\Resources\Actions\ChangeLanguageAction;

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
                                Forms\Components\TextInput::make('title')
                                    ->autofocus()
                                    ->required()
                                    ->debounce(),

//                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
//                                    ->readOnly()
                                    ->required(),
                                Forms\Components\Textarea::make('excerpt')
                                    ->columnSpan(2),
//                                ChangeLanguageAction::make('content'),

                                Forms\Components\RichEditor::make('content')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('posts')
                                    ->columnSpan(2)
                                ,
                            ]),
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Metadata')
                            ->collapsible()
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('user', 'name')
                                    ->default(fn() => auth()->id())
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->searchable()
                                    ->relationship('category', 'name')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->debounce()
                                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                            ->required(),
                                        Forms\Components\TextInput::make('slug')
                                            ->readOnly()
                                            ->unique()
                                            ->required(),
                                    ]),
                                Forms\Components\Select::make('tags')
                                    ->multiple()
                                    ->relationship('tags', 'name'),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Published At'),
                                Forms\Components\TextInput::make('reading_time')
                                    ->numeric()
                                    ->label('Reading Time'),
                                Forms\Components\FileUpload::make('cover')
                                    ->label('Cover Image')
                                    ->directory('posts')
                                    ->image('https://w0.peakpx.com/wallpaper/102/801/HD-wallpaper-olymus-kinda-adsf-asdfsfa-asdfasdfa-sfgha.jpg'),
//                                FileUploadMy::make('cover')
//                                    ->image(''),

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
