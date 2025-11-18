<?php

namespace Presentation\Driver\Controllers;

use ApplicationLayer\Driver\DriverHandler;
use Domain\Driver\Exceptions\DriverNotFoundException;
use Domain\Vehicle\Exceptions\VehicleNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Presentation\Base\Controllers\BaseController;
use Presentation\Driver\Requests\AssignVehicleRequest;
use Presentation\Driver\Requests\CreateDriverRequest;
use Presentation\Driver\Requests\ListDriversRequest;
use Presentation\Driver\Requests\UpdateDriverRequest;

class DriverController extends BaseController
{
    public function __construct(
        private readonly DriverHandler $driverHandler
    ) {
    }

    /**
     * Display a paginated listing of drivers
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $dto = ListDriversRequest::fromArray($request->all());
            $response = $this->driverHandler->index($dto);
            return $this->success($response);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created driver
     */
    public function store(CreateDriverRequest $request): JsonResponse
    {
        try {
            $response = $this->driverHandler->store($request);
            return $this->success($response, status: 201);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified driver
     */
    public function show(string $id): JsonResponse
    {
        try {
            $response = $this->driverHandler->show($id);
            return $this->success($response);
        } catch (DriverNotFoundException $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified driver
     */
    public function update(string $id, UpdateDriverRequest $request): JsonResponse
    {
//        try {
            $response = $this->driverHandler->update($id, $request);
            return $this->success($response);
//        } catch (DriverNotFoundException $e) {
//            return $this->failed($e, ['message' => $e->getMessage()], 404);
//        } catch (\InvalidArgumentException $e) {
//            return $this->failed($e, ['message' => $e->getMessage()], 422);
//        } catch (\Throwable $e) {
//            return $this->failed($e, ['message' => $e->getMessage()], 500);
//        }
    }

    /**
     * Remove the specified driver
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->driverHandler->destroy($id);
            return $this->success(['message' => 'Driver deleted successfully']);
        } catch (DriverNotFoundException $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Assign a vehicle to a driver
     */
    public function assignVehicle(string $id, AssignVehicleRequest $request): JsonResponse
    {
        try {
            $response = $this->driverHandler->assignVehicle($id, $request);
            return $this->success($response);
        } catch (DriverNotFoundException | VehicleNotFoundException $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }
}
