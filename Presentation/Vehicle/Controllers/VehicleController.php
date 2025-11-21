<?php

namespace Presentation\Vehicle\Controllers;

use ApplicationLayer\Vehicle\VehicleHandler;
use Domain\Vehicle\Exceptions\VehicleNotFoundException;
use Domain\Driver\Exceptions\DriverNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Presentation\Base\Controllers\BaseController;
use Presentation\Driver\Requests\ListDriversRequest;
use Presentation\Vehicle\Requests\AssignDriverRequest;
use Presentation\Vehicle\Requests\CreateVehicleRequest;
use Presentation\Vehicle\Requests\ListVehiclesRequest;
use Presentation\Vehicle\Requests\UpdateVehicleRequest;

class VehicleController extends BaseController
{
    public function __construct(
        private readonly VehicleHandler $vehicleHandler
    ) {
    }

    /**
     * Display a paginated listing of vehicles
     */
    public function index(Request $request): JsonResponse
    {
//        try {
            $dto = ListVehiclesRequest::fromArray($request->all());
            $response = $this->vehicleHandler->index($dto);
            return $this->success($response);
//        } catch (\Throwable $e) {
//            return $this->failed($e, ['message' => $e->getMessage()], 500);
//        }
    }

    /**
     * Store a newly created vehicle
     */
    public function store(CreateVehicleRequest $request): JsonResponse
    {
        try {
            $response = $this->vehicleHandler->store($request);
            return $this->success($response, status: 201);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified vehicle
     */
    public function show(string $id): JsonResponse
    {
//        try {
            $response = $this->vehicleHandler->show($id);
            return $this->success($response);
//        } catch (VehicleNotFoundException $e) {
//            return $this->failed($e, ['message' => $e->getMessage()], 404);
//        } catch (\Throwable $e) {
//            return $this->failed($e, ['message' => $e->getMessage()], 500);
//        }
    }

    /**
     * Update the specified vehicle
     */
    public function update(string $id, UpdateVehicleRequest $request): JsonResponse
    {
        try {
            $response = $this->vehicleHandler->update($id, $request);
            return $this->success($response);
        } catch (VehicleNotFoundException $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 404);
        } catch (\InvalidArgumentException $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified vehicle
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->vehicleHandler->destroy($id);
            return $this->success(['message' => 'Vehicle deleted successfully']);
        } catch (VehicleNotFoundException $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Assign a driver to a vehicle
     */
}
