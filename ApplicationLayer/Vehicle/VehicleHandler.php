<?php

namespace ApplicationLayer\Vehicle;

use Domain\Driver\Exceptions\DriverNotFoundException;
use Domain\Vehicle\Contracts\VehicleUseCaseInterface;
use Domain\Vehicle\Exceptions\VehicleNotFoundException;
use Presentation\Vehicle\Requests\AssignDriverRequest;
use Presentation\Vehicle\Requests\CreateVehicleRequest;
use Presentation\Vehicle\Requests\ListVehiclesRequest;
use Presentation\Vehicle\Requests\UpdateVehicleRequest;
use Presentation\Vehicle\Responses\SingleVehicleResponse;
use Presentation\Vehicle\Responses\VehicleListResponse;

class VehicleHandler
{
    public function __construct(
        private readonly VehicleUseCaseInterface $vehicleUseCase
    ) {
    }

    public function index(ListVehiclesRequest $request): VehicleListResponse
    {
        $paginatedResult = $this->vehicleUseCase->searchAndPaginate(
            search: $request->getSearch(),
            searchColumns: ['plate_number', 'brand', 'model'],
            sortBy: $request->getSortBy(),
            sortDirection: $request->getSortDirection(),
            hasRelation: $request->hasDriversFilter(),
            relationName: 'drivers',
            perPage: $request->getPerPage(),
            page: $request->getPage()
        );

        return new VehicleListResponse(
            items: collect($paginatedResult->items())->map(fn($vehicle) => $vehicle->toEntity())->toArray(),
            total: $paginatedResult->total(),
            perPage: $paginatedResult->perPage(),
            page: $paginatedResult->currentPage()
        );
    }

    public function store(CreateVehicleRequest $request): SingleVehicleResponse
    {
        $data = [
            'plate_number' => $request->getPlateNumber(),
            'brand' => $request->getBrand(),
            'model' => $request->getModel(),
            'year' => $request->getYear(),
        ];

        $vehicle = $this->vehicleUseCase->store($data);

        return new SingleVehicleResponse(
            id: $vehicle->id,
            plateNumber: $vehicle->plate_number,
            brand: $vehicle->brand,
            model: $vehicle->model,
            year: $vehicle->year,
            createdAt: $vehicle->created_at->toIso8601String(),
            updatedAt: $vehicle->updated_at->toIso8601String()
        );
    }

    public function show(string $id): SingleVehicleResponse
    {
        $vehicle = $this->vehicleUseCase->find($id);

        if (!$vehicle) {
            throw new VehicleNotFoundException($id);
        }

        return new SingleVehicleResponse(
            id: $vehicle->id,
            plateNumber: $vehicle->plateNumber,
            brand: $vehicle->brand,
            model: $vehicle->model,
            year: $vehicle->year,
            createdAt: $vehicle->createdAt->toIso8601String(),
            updatedAt: $vehicle->updatedAt->toIso8601String()
        );
    }

    public function update(string $id, UpdateVehicleRequest $request): SingleVehicleResponse
    {
        $data = $request->getUpdateData();
        $vehicle = $this->vehicleUseCase->update($id, $data);

        if (!$vehicle) {
            throw new VehicleNotFoundException($id);
        }

        return new SingleVehicleResponse(
            id: $vehicle->id,
            plateNumber: $vehicle->plate_number,
            brand: $vehicle->brand,
            model: $vehicle->model,
            year: $vehicle->year,
            createdAt: $vehicle->created_at->toIso8601String(),
            updatedAt: $vehicle->updated_at->toIso8601String()
        );
    }

    public function destroy(string $id): bool
    {
        $result = $this->vehicleUseCase->destroy($id);

        if (!$result) {
            throw new VehicleNotFoundException($id);
        }

        return true;
    }
}
