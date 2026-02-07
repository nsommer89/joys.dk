<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->unique(ignoreRecord: true),

                \Filament\Forms\Components\RichEditor::make('description')
                    ->columnSpanFull()
                    ->extraInputAttributes(['style' => 'min-height: 300px'])
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('events/attachments')
                    ->fileAttachmentsVisibility('public'),

                DateTimePicker::make('start_time')
                    ->required()
                    ->native(false),

                Toggle::make('is_published')
                    ->default(true)
                    ->required()
                    ->inline(false),

                FileUpload::make('image_path')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('events')
                    ->columnSpanFull(),
            ]);
    }
}
