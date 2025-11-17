<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('plate_number')
                    ->required(),
                TextInput::make('brand')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('year')
                    ->required(),
            ]);
    }
}
