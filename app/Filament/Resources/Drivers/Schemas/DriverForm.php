<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Driver Information')
                    ->description('Basic information about the driver')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->placeholder('Enter driver full name')
                            ->columnSpan('full'),

                        TextInput::make('license_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('e.g., DL123456789')
                            ->label('License Number'),

                        TextInput::make('phone_number')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('+1 (555) 123-4567')
                            ->label('Phone Number')
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Vehicle Assignments')
                    ->description('Manage vehicle assignments for this driver')
                    ->schema([
                        Select::make('vehicles')
                            ->relationship(
                                name: 'vehicles',
                                titleAttribute: 'plate_number',  // Changed from 'name'
                                modifyQueryUsing: fn($query) => $query->orderBy('plate_number')  // Changed from 'name'
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Assigned Vehicles')
                            ->helperText('Select one or more vehicles to assign to this driver')
                            ->columnSpanFull()
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->plate_number} - {$record->brand} {$record->model} ({$record->year})")
                            ->pivotData([
                                'assigned_at' => now(),
                                'is_active' => true,
                            ])
                            ->createOptionForm([
                                TextInput::make('plate_number')
                                    ->required()
                                    ->unique()
                                    ->label('Plate Number')
                                    ->placeholder('e.g., ABC-1234'),

                                TextInput::make('brand')
                                    ->required()
                                    ->label('Brand')
                                    ->placeholder('e.g., Toyota'),

                                TextInput::make('model')
                                    ->required()
                                    ->label('Model')
                                    ->placeholder('e.g., Camry'),

                                TextInput::make('year')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(date('Y') + 1)
                                    ->label('Year')
                                    ->placeholder('e.g., 2024'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Fieldset::make('Timestamps')
                    ->schema([
                        Placeholder::make('created_at')
                            ->label('Created At')
                            ->content(fn($record): string => $record?->created_at?->diffForHumans() ?? '-'),

                        Placeholder::make('updated_at')
                            ->label('Last Modified At')
                            ->content(fn($record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                    ])
                    ->columns(2)
                    ->hidden(fn(?string $operation): bool => $operation === 'create'),
            ]);
    }
}
