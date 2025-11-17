<?php

namespace App\Filament\Resources\Drivers\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-user')
                    ->description(fn ($record) => $record->license_number)
                    ->wrap(),

                TextColumn::make('license_number')
                    ->label('License #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('License number copied!')
                    ->badge()
                    ->color('primary')
                    ->toggleable(),

                TextColumn::make('phone_number')
                    ->label('Phone')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-phone')
                    ->url(fn ($record) => "tel:{$record->phone_number}")
                    ->color('success')
                    ->toggleable(),

                TextColumn::make('vehicles_count')
                    ->label('Vehicles')
                    ->counts('vehicles')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-truck')
                    ->sortable(),

                TextColumn::make('activeVehicles')
                    ->label('Active Vehicles')
                    ->formatStateUsing(fn ($record) => $record->activeVehicles()->count())
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->sortable(query: function ($query, string $direction) {
                        return $query->withCount([
                            'vehicles as active_vehicles_count' => function ($query) {
                                $query->wherePivot('is_active', true);
                            }
                        ])->orderBy('active_vehicles_count', $direction);
                    })
                    ->toggleable(),

                IconColumn::make('has_active_vehicles')
                    ->label('Status')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->activeVehicles()->exists())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn ($record) => $record->activeVehicles()->exists() ? 'Has active vehicles' : 'No active vehicles')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->tooltip(fn ($state) => $state?->format('Y-m-d H:i:s'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->tooltip(fn ($state) => $state?->format('Y-m-d H:i:s'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->color('danger')
                    ->tooltip(fn ($state) => $state?->format('Y-m-d H:i:s'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('has_vehicles')
                    ->label('Vehicle Assignment')
                    ->options([
                        'with' => 'Has Vehicles',
                        'without' => 'No Vehicles',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'with') {
                            return $query->has('vehicles');
                        }
                        if ($data['value'] === 'without') {
                            return $query->doesntHave('vehicles');
                        }
                        return $query;
                    }),

                SelectFilter::make('has_active_vehicles')
                    ->label('Active Status')
                    ->options([
                        'active' => 'Has Active Vehicles',
                        'inactive' => 'No Active Vehicles',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'active') {
                            return $query->whereHas('vehicles', function ($query) {
                                $query->wherePivot('is_active', true);
                            });
                        }
                        if ($data['value'] === 'inactive') {
                            return $query->whereDoesntHave('vehicles', function ($query) {
                                $query->wherePivot('is_active', true);
                            });
                        }
                        return $query;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info'),
                    EditAction::make()
                        ->color('warning'),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ])
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->tooltip('Actions'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No drivers found')
            ->emptyStateDescription('Create your first driver to get started.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
