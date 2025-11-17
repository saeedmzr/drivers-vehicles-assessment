<?php

namespace Presentation\Driver\Controllers;

use Domain\Driver\Exceptions\DriverNotFoundException;
use Domain\Vehicle\Exceptions\VehicleNotFoundException;
use ApplicationLayer\Driver\DriverHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
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
     * Display a paginated listing of drivers.
     */
    public function index(ListDriversRequest $request): JsonResponse
    {
        try {
            $response = $this->driverHandler->index($request);
            return $this->success($response);
        } catch (ValidationException $e) {
            return $this->failed($e, ['errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created driver.
     */
    public function store(CreateDriverRequest $request): JsonResponse
    {
        try {
            $response = $this->driverHandler->store($request);
            return $this->success($response, status: 201);
        } catch (ValidationException $e) {
            return $this->failed($e, ['errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified driver.
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
     * Update the specified driver.
     */
    public function update(string $id, UpdateDriverRequest $request): JsonResponse
    {
        try {
            $response = $this->driverHandler->update($id, $request);
            return $this->success($response);
        } catch (ValidationException $e) {
            return $this->failed($e, ['errors' => $e->errors()], 422);
        } catch (DriverNotFoundException $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified driver.
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
     * Assign a vehicle to a driver.
     */
    public function assignVehicle(string $id, AssignVehicleRequest $request): JsonResponse
    {
        try {
            $response = $this->driverHandler->assignVehicle($id, $request);
            return $this->success($response);
        } catch (ValidationException $e) {
            return $this->failed($e, ['errors' => $e->errors()], 422);
        } catch (DriverNotFoundException | VehicleNotFoundException $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return $this->failed($e, ['message' => $e->getMessage()], 500);
        }
    }
}
