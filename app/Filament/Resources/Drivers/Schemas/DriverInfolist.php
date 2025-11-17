<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DriverInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Driver Information')
                    ->description('Basic information about the driver')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Full Name')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->icon('heroicon-o-user')
                                    ->copyable()
                                    ->copyMessage('Name copied!')
                                    ->copyMessageDuration(1500)
                                    ->columnSpan(2),

                                TextEntry::make('license_number')
                                    ->label('License Number')
                                    ->icon('heroicon-o-identification')
                                    ->copyable()
                                    ->badge()
                                    ->color('primary'),

                                TextEntry::make('phone_number')
                                    ->label('Phone Number')
                                    ->icon('heroicon-o-phone')
                                    ->copyable()
                                    ->url(fn ($state) => "tel:{$state}")
                                    ->color('success'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Assigned Vehicles')
                    ->description('List of vehicles currently assigned to this driver')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        RepeatableEntry::make('vehicles')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('plate_number')
                                            ->label('Plate Number')
                                            ->badge()
                                            ->color('warning')
                                            ->icon('heroicon-o-hashtag'),

                                        TextEntry::make('brand')
                                            ->label('Brand'),

                                        TextEntry::make('model')
                                            ->label('Model'),

                                        TextEntry::make('year')
                                            ->label('Year')
                                            ->badge()
                                            ->color('info'),

                                        TextEntry::make('pivot.assigned_at')
                                            ->label('Assigned At')
                                            ->dateTime()
                                            ->icon('heroicon-o-calendar')
                                            ->placeholder('Not assigned'),

                                        IconEntry::make('pivot.is_active')
                                            ->label('Active')
                                            ->boolean()
                                            ->trueIcon('heroicon-o-check-circle')
                                            ->falseIcon('heroicon-o-x-circle')
                                            ->trueColor('success')
                                            ->falseColor('danger'),

                                        TextEntry::make('pivot.notes')
                                            ->label('Notes')
                                            ->placeholder('No notes')
                                            ->columnSpanFull()
                                            ->markdown()
                                            ->limit(50),
                                    ]),
                            ])
                            ->contained(false),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($record) => $record->vehicles->isEmpty())
                    ->visible(fn ($record) => $record->vehicles->isNotEmpty()),

                Section::make('Metadata')
                    ->description('Record timestamps')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime()
                                    ->icon('heroicon-o-plus-circle')
                                    ->since()
                                    ->tooltip(fn ($state) => $state?->format('Y-m-d H:i:s')),

                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->dateTime()
                                    ->icon('heroicon-o-pencil-square')
                                    ->since()
                                    ->tooltip(fn ($state) => $state?->format('Y-m-d H:i:s')),

                                TextEntry::make('deleted_at')
                                    ->label('Deleted At')
                                    ->dateTime()
                                    ->icon('heroicon-o-trash')
                                    ->color('danger')
                                    ->since()
                                    ->placeholder('Not deleted')
                                    ->tooltip(fn ($state) => $state?->format('Y-m-d H:i:s'))
                                    ->visible(fn ($record) => $record->deleted_at !== null),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
