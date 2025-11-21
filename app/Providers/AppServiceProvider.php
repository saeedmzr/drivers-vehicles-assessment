<?php

namespace App\Providers;

use Domain\Core\Contracts\BaseRepositoryInterface;
use Domain\Core\Contracts\BaseUsecaseInterface;
use Domain\Core\Repositories\BaseRepository;
use Domain\Core\Usecases\BaseUsecase;
use Domain\Driver\Contracts\DriverRepositoryInterface;
use Domain\Driver\Contracts\DriverUseCaseInterface;
use Domain\Driver\Repositories\DriverRepository;
use Domain\Driver\Usecases\DriverUseCase;
use Domain\User\Contracts\UserRepositoryInterface;
use Domain\User\Contracts\UserUseCaseInterface;
use Domain\User\Repositories\UserRepository;
use Domain\User\Usecases\UserUseCase;
use Domain\Vehicle\Contracts\VehicleRepositoryInterface;
use Domain\Vehicle\Contracts\VehicleUseCaseInterface;
use Domain\Vehicle\Repositories\VehicleRepository;
use Domain\Vehicle\Usecases\VehicleUseCase;
use Helper\ArrayHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Presentation\Base\Requests\BaseRequest;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Base
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(BaseUseCaseInterface::class, BaseUseCase::class);

        $this->app->beforeResolving(BaseRequest::class, function ($class, $parameters, $app) {
            if ($app->has($class)) {
                return;
            }

            $app->bind(
                $class,
                function ($container) use ($parameters, $class) {
                    $allData = [];
                    $allData = array_merge($allData, $container['request']->query());
                    $allData = array_merge($allData, $container['request']->all());
                    $validator = Validator::make(ArrayHelper::convertToCamelCase($allData), $class::rules());
                    $validator->validate();

                    return $class::fromArray($validator->validated());
                },
            );
        });

        //Driver
        $this->app->bind(DriverRepositoryInterface::class, DriverRepository::class);
        $this->app->bind(DriverUsecaseInterface::class, DriverUsecase::class);

        //Vehicle
        $this->app->bind(VehicleRepositoryInterface::class, VehicleRepository::class);
        $this->app->bind(VehicleUsecaseInterface::class, VehicleUsecase::class);

        //User
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserUsecaseInterface::class, UserUsecase::class);

    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
