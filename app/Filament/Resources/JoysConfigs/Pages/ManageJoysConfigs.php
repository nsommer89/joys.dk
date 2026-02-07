<?php

namespace App\Filament\Resources\JoysConfigs\Pages;

use App\Filament\Resources\JoysConfigs\JoysConfigResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJoysConfigs extends ManageRecords
{
    protected static string $resource = JoysConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
