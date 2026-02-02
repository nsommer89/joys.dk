<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Overskrift')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                
                TextInput::make('slug')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->unique(ignoreRecord: true),
                
                \Filament\Forms\Components\RichEditor::make('content')
                    ->label('Indhold')
                    ->columnSpanFull()
                    ->extraInputAttributes(['style' => 'min-height: 300px'])
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('news/attachments')
                    ->fileAttachmentsVisibility('public'),
                
                DateTimePicker::make('published_at')
                    ->label('Udgivelsesdato')
                    ->native(false)
                    ->default(now()),
                    
                Toggle::make('is_published')
                    ->label('Udgivet')
                    ->default(true)
                    ->required()
                    ->inline(false),
                    
                FileUpload::make('image_path')
                    ->label('Billede')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('news')
                    ->columnSpanFull(),
            ]);
    }
}
