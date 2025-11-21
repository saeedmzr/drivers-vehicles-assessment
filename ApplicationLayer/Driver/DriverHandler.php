<?php

namespace ApplicationLayer\Driver;

use Domain\Driver\Contracts\DriverUseCaseInterface;
use Domain\Driver\Exceptions\DriverNotFoundException;
use Domain\Vehicle\Exceptions\VehicleNotFoundException;
use Illuminate\Support\Facades\Log;
use Presentation\Driver\Requests\AssignVehicleRequest;
use Presentation\Driver\Requests\CreateDriverRequest;
use Presentation\Driver\Requests\ListDriversRequest;
use Presentation\Driver\Requests\UpdateDriverRequest;
use Presentation\Driver\Responses\DriverListResponse;
use Presentation\Driver\Responses\DriverShowResponse;
use Presentation\Driver\Responses\SingleDriverResponse;

class DriverHandler
{
    public function __construct(
        private readonly DriverUseCaseInterface $driverUseCase
    ) {
    }

    /**
     * Get paginated drivers with search and filters
     */
    public function index(ListDriversRequest $request): DriverListResponse
    {
        $paginatedResult = $this->driverUseCase->searchAndPaginate(
            search: $request->getSearch(),
            searchColumns: ['name', 'license_number', 'phone_number'],
            sortBy: $request->getSortBy(),
            sortDirection: $request->getSortDirection(),
            hasRelation: $request->hasVehiclesFilter(),
            relationName: 'vehicles',
            perPage: $request->getPerPage(),
            page: $request->getPage()
        );

        return new DriverListResponse(
            items: collect($paginatedResult->items())->map(fn($driver) => $driver->toEntity())->toArray(),
            total: $paginatedResult->total(),
            perPage: $paginatedResult->perPage(),
            page: $paginatedResult->currentPage()
        );
    }

    /**
     * Create a new driver
     */
    public function store(CreateDriverRequest $request): SingleDriverResponse
    {
        $data = [
            'name' => $request->getName(),
            'license_number' => $request->getLicenseNumber(),
            'phone_number' => $request->getPhoneNumber(),
        ];

        $driver = $this->driverUseCase->store($data);

        return new SingleDriverResponse(
            id: $driver->id,
            name: $driver->name,
            licenseNumber: $driver->license_number,
            phoneNumber: $driver->phone_number,
            vehicles: $driver->vehicles ? $driver->vehicles->map(fn($vehicle) => $vehicle->toEntity())->toArray() : [],
            createdAt: $driver->created_at->toIso8601String(),
            updatedAt: $driver->updated_at->toIso8601String()
        );
    }

    /**
     * Show a specific driver
     */
    public function show(string $id): DriverShowResponse
    {
        $driver = $this->driverUseCase->find($id);

        if (!$driver) {
            throw new DriverNotFoundException($id);
        }

        return new DriverShowResponse(
            id: $driver->id,
            name: $driver->name,
            licenseNumber: $driver->licenseNumber,
            phoneNumber: $driver->phoneNumber,
            vehicles: $driver->vehicles ?? [],
            createdAt: $driver->createdAt?->toIso8601String(),
            updatedAt: $driver->updatedAt?->toIso8601String()
        );
    }

    /**
     * Update a driver
     */
    public function update(string $id, UpdateDriverRequest $request): SingleDriverResponse
    {
        $data = $request->getUpdateData();
        $driver = $this->driverUseCase->update($id, $data);

        if (!$driver) {
            throw new DriverNotFoundException($id);
        }

        return new SingleDriverResponse(
            id: $driver->id,
            name: $driver->name,
            licenseNumber: $driver->license_number,
            phoneNumber: $driver->phone_number,
            vehicles: $driver->vehicles ? $driver->vehicles->map(fn($vehicle) => $vehicle->toEntity())->toArray() : [],
            createdAt: $driver->created_at->toIso8601String(),
            updatedAt: $driver->updated_at->toIso8601String()
        );
    }

    /**
     * Delete a driver
     */
    public function destroy(string $id): bool
    {
        $result = $this->driverUseCase->destroy($id);

        if (!$result) {
            throw new DriverNotFoundException($id);
        }

        return true;
    }

    /**
     * Assign a vehicle to a driver
     */
    public function assignVehicle(string $driverId, AssignVehicleRequest $request): SingleDriverResponse
    {
        $driver = $this->driverUseCase->find($driverId);

        if (!$driver) {
            throw new DriverNotFoundException($driverId);
        }

        // Check if vehicle exists via repository
        $vehicle = $this->driverUseCase->findVehicle($request->getVehicleId());
        if (!$vehicle) {
            throw new VehicleNotFoundException($request->getVehicleId());
        }

        $this->driverUseCase->assignVehicle($driverId, $request->getVehicleId(), [
            'assigned_at' => now(),
            'is_active' => true,
            'notes' => $request->getNotes(),
        ]);

        $updatedDriver = $this->driverUseCase->find($driverId);

        return new SingleDriverResponse(
            id: $updatedDriver->id,
            name: $updatedDriver->name,
            licenseNumber: $updatedDriver->licenseNumber,
            phoneNumber: $updatedDriver->phoneNumber,
            vehicles: $updatedDriver->vehicles ?? [],
            createdAt: $updatedDriver->createdAt?->toIso8601String(),
            updatedAt: $updatedDriver->updatedAt?->toIso8601String()
        );
    }
}
