# TUG Project – Laravel Backend

TUG Project is a Dockerized Laravel backend that provides a REST API for managing drivers and vehicles with a clean architecture approach, featuring real-time admin panels, role-based access control, and comprehensive testing.

---

## Features

- Laravel REST API with authentication and session management.
- Clean Architecture with separation of concerns (Domain, Application, Presentation layers).
- MySQL as the primary relational database with separate test database.
- Redis for caching and session storage.
- Filament Admin Panel for managing drivers, vehicles, and users.
- Driver-Vehicle many-to-many relationship with pivot data tracking.
- Docker-based development environment with Nginx reverse proxy.
- Makefile-driven workflow for building, running, testing, and maintaining the stack.
- Comprehensive test suite (Unit, Integration, and Feature tests).
- Custom request validation with snake_case to camelCase conversion.
- Readonly DTOs for type-safe data transfer.

---

## Architecture

The project follows Clean Architecture principles with three main layers:

### Domain Layer (`Domain/`)
- **Repositories**: Data access abstractions
- **Use Cases**: Business logic and orchestration
- **Contracts**: Interfaces for repositories and use cases
- **Exceptions**: Domain-specific exceptions
- **Entities**: Core business models

### Application Layer (`ApplicationLayer/`)
- **Handlers**: Coordinate between presentation and domain layers
- **Responses**: Formatted response objects
- **Services**: Application-level business logic

### Presentation Layer (`Presentation/`)
- **Controllers**: HTTP request handling
- **Requests**: Request validation and transformation
- **Responses**: API response formatting

### Infrastructure Layer
- **Models**: Eloquent ORM models
- **Database**: Migrations and seeders
- **Factories**: Test data generation

---

## Prerequisites

Make sure the following are installed:

- Git
- Docker
- Docker Compose (v3.8+)
- Make
- PHP 8.2+ (for local development without Docker)

---

## Installation

### 1. Clone the repository

```bash
git clone git@github.com:saeedmzr/drivers-vehicles-assessment.git
cd drivers-vehicles-assessment
```

### 2. Environment configuration

Copy the example env file and update values:

```bash
cp .env.example .env
```

Important values to review:

- `APP_ENV=local`
- `APP_DEBUG=true`
- Database credentials (MySQL)
    - `DB_DATABASE=tug_project`
    - `DB_USERNAME=tug_user`
    - `DB_PASSWORD=your_password`
- Redis configuration
- Session driver and configuration

For testing environment:
- `DB_TEST_DATABASE=tug_testing`
- `DB_TEST_USERNAME=testing_user`
- `DB_TEST_PASSWORD=testing_password`

---

## Running with Docker (recommended)

The project is designed to be run via Docker Compose using a Makefile.

### Build and start all services

```bash
make build
```

This will:
- Build the Laravel app image
- Start MySQL (main and test databases), Redis, Nginx, and the Laravel app
- Install Composer dependencies
- Run migrations
- Start the development server

---

## Make Commands

The Makefile wraps common Docker Compose operations:

**`build`**: Build and start all containers
```bash
docker-compose --env-file .env -f docker-compose.yml up -d --build
```

**`up`**: Start all containers
```bash
docker-compose --env-file .env -f docker-compose.yml up -d
```

**`down`**: Stop and remove all containers
```bash
docker-compose -f docker-compose.yml down
```

**`ps`**: List running containers
```bash
docker-compose -f docker-compose.yml ps
```

**`shell`**: Access app container shell
```bash
docker exec -it app bash
```

**`shell-as-root`**: Access app container as root
```bash
docker exec -u root -it app bash
```

**`test`**: Run all tests
```bash
docker exec -i app php artisan test
```

---

## Service URLs

- **API (via Nginx)**:
    - `http://localhost:8080/api`

- **Filament Admin Panel**:
    - `http://localhost:8080/admin`

- **MySQL (Main)**:
    - `localhost:3307`

- **MySQL (Test)**:
    - `localhost:3308`

- **Redis**:
    - `localhost:6379`

---

## API Endpoints

### Drivers

- `GET /api/drivers` - List drivers with pagination
    - Query params: `per_page`, `page`, `search`, `sort_by`, `sort_direction`, `has_vehicles`
- `POST /api/drivers` - Create a new driver
  ```json
  {
    "name": "John Doe",
    "license_number": "DL123456",
    "phone_number": "+1234567890"
  }
  ```
- `GET /api/drivers/{id}` - Show driver details
- `PUT /api/drivers/{id}` - Update a driver
  ```json
  {
    "name": "Updated Name",
    "phone_number": "+0987654321"
  }
  ```
- `DELETE /api/drivers/{id}` - Delete a driver (soft delete)
- `POST /api/drivers/{id}/assign-vehicle` - Assign a vehicle to a driver
  ```json
  {
    "vehicle_id": "uuid",
    "notes": "Primary vehicle"
  }
  ```

### Vehicles

- `GET /api/vehicles` - List vehicles with pagination
    - Query params: `per_page`, `page`, `search`, `sort_by`, `sort_direction`, `has_drivers`
- `POST /api/vehicles` - Create a new vehicle
  ```json
  {
    "plate_number": "ABC-1234",
    "brand": "Toyota",
    "model": "Camry",
    "year": 2023,
    "color": "Blue",
    "vin": "1HGBH41JXMN109186"
  }
  ```
- `GET /api/vehicles/{id}` - Show vehicle details
- `PUT /api/vehicles/{id}` - Update a vehicle
  ```json
  {
    "brand": "Honda",
    "color": "Red"
  }
  ```
- `DELETE /api/vehicles/{id}` - Delete a vehicle (soft delete)
- `POST /api/vehicles/{id}/assign-driver` - Assign a driver to a vehicle
  ```json
  {
    "driver_id": "uuid",
    "notes": "Primary driver"
  }
  ```

---

## Filament Admin Panel

The project includes a Filament admin panel for managing the application:

### Features:
- **User Management**: Manage admin users
- **Driver Management**: CRUD operations for drivers with relationship tracking
- **Vehicle Management**: CRUD operations for vehicles with relationship tracking
- **Role-based Access Control**: Only authenticated admins can access the panel

### Access:
1. Navigate to `http://localhost:8080/admin`
2. Login with admin credentials
3. Manage drivers, vehicles, and users through the intuitive interface

### Resources:
- **DriverResource**: Manage driver records, view assigned vehicles
- **VehicleResource**: Manage vehicle records, view assigned drivers
- **UserResource**: Manage admin users and permissions

---

## Testing

Run the complete test suite with:

```bash
make test
```

### Test Coverage (54 tests, 180 assertions)

#### Unit Tests (`Tests\Unit\`)
- **BaseControllerTest** (6 tests): Controller response formatting
- **BaseRequestTest** (3 tests): Request validation and transformation
- **BaseResponseTest** (4 tests): Response conversion and serialization
- **PaginatedResponseTest** (3 tests): Pagination logic and metadata

#### Feature Tests (`Tests\Feature\`)

**Driver API Tests** (9 tests):
- ✓ List drivers with pagination
- ✓ Create, read, update, delete operations
- ✓ Search drivers by name
- ✓ Filter drivers with/without vehicles
- ✓ Assign vehicles to drivers
- ✓ Validation on create

**Vehicle API Tests** (8 tests):
- ✓ List vehicles with pagination
- ✓ Create, read, update, delete operations
- ✓ Search vehicles by plate number
- ✓ Filter vehicles with/without drivers
- ✓ Assign drivers to vehicles
- ✓ Validation on create

**Filament Admin Tests** (8 tests):
- ✓ Dashboard access control
- ✓ Resource access (drivers, vehicles)
- ✓ Login functionality
- ✓ Guest access restrictions
- ✓ Factory-based admin creation

#### Integration Tests (`Tests\Integration\`)

**Container Tests** (4 tests):
- ✓ MySQL connection and queries
- ✓ Redis connection and operations

**Database Tests** (5 tests):
- ✓ Table structure verification
- ✓ Migration integrity

**Model Relation Tests** (4 tests):
- ✓ Driver-Vehicle relationships
- ✓ Pivot data storage
- ✓ Active vehicle/driver filtering

---

## Request/Response Flow

### Request Handling

1. **Request arrives** at Nginx → routed to Laravel app
2. **Route matching** → Controller method invoked
3. **Request Object** created (extends `BaseRequest`)
    - Automatic validation
    - Snake_case → camelCase conversion
    - Type-safe readonly properties
4. **Handler invoked** with validated request
5. **Use Case executed** via repository pattern
6. **Response formatted** and returned

### Example: Creating a Driver

```php
// 1. Request validation (Presentation/Driver/Requests/CreateDriverRequest.php)
POST /api/drivers
{
    "name": "John Doe",
    "license_number": "DL123456",
    "phone_number": "+1234567890"
}

// 2. Handler processes (ApplicationLayer/Driver/DriverHandler.php)
public function store(CreateDriverRequest $request): SingleDriverResponse

// 3. Use Case executes (Domain/Driver/Usecases/DriverUseCase.php)
public function store(array $data): Driver

// 4. Response formatted (ApplicationLayer/Driver/Responses/SingleDriverResponse.php)
{
    "success": true,
    "data": {
        "id": "uuid",
        "name": "John Doe",
        "license_number": "DL123456",
        "phone_number": "+1234567890",
        "created_at": "2025-11-18T...",
        "updated_at": "2025-11-18T..."
    }
}
```

---

## Database Schema

### Tables

**drivers**:
- `id` (UUID, primary key)
- `name` (string)
- `license_number` (string, unique)
- `phone_number` (string, unique)
- `created_at`, `updated_at`, `deleted_at`

**vehicles**:
- `id` (UUID, primary key)
- `plate_number` (string, unique)
- `brand` (string)
- `model` (string)
- `year` (integer)
- `color` (string, nullable)
- `vin` (string, unique, nullable)
- `created_at`, `updated_at`, `deleted_at`

**driver_vehicle** (pivot):
- `driver_id` (UUID, foreign key)
- `vehicle_id` (UUID, foreign key)
- `assigned_at` (timestamp)
- `is_active` (boolean)
- `notes` (text, nullable)
- `created_at`, `updated_at`

**users**:
- `id` (UUID, primary key)
- `name` (string)
- `email` (string, unique)
- `password` (hashed)
- `created_at`, `updated_at`

---

## Project Structure

```
tug_project/
├── app/
│   └── Providers/
│       └── AppServiceProvider.php     # Custom request binding
├── ApplicationLayer/
│   ├── Driver/
│   │   ├── DriverHandler.php
│   │   └── Responses/
│   │       ├── SingleDriverResponse.php
│   │       ├── DriverListResponse.php
│   │       └── DriverShowResponse.php
│   └── Vehicle/
│       ├── VehicleHandler.php
│       └── Responses/
│           ├── SingleVehicleResponse.php
│           └── VehicleListResponse.php
├── Domain/
│   ├── Core/
│   │   ├── Contracts/
│   │   │   ├── BaseRepositoryInterface.php
│   │   │   └── BaseUsecaseInterface.php
│   │   ├── Repositories/
│   │   │   └── BaseRepository.php
│   │   └── Usecases/
│   │       └── BaseUsecase.php
│   ├── Driver/
│   │   ├── Contracts/
│   │   │   ├── DriverRepositoryInterface.php
│   │   │   └── DriverUseCaseInterface.php
│   │   ├── Repositories/
│   │   │   └── DriverRepository.php
│   │   ├── Usecases/
│   │   │   └── DriverUseCase.php
│   │   └── Exceptions/
│   │       └── DriverNotFoundException.php
│   └── Vehicle/
│       ├── Contracts/
│       │   ├── VehicleRepositoryInterface.php
│       │   └── VehicleUseCaseInterface.php
│       ├── Repositories/
│       │   └── VehicleRepository.php
│       ├── Usecases/
│       │   └── VehicleUseCase.php
│       └── Exceptions/
│           └── VehicleNotFoundException.php
├── Presentation/
│   ├── Base/
│   │   ├── Controllers/
│   │   │   └── BaseController.php
│   │   ├── Requests/
│   │   │   ├── BaseRequest.php
│   │   │   └── PaginatedRequest.php
│   │   └── Responses/
│   │       ├── BaseResponse.php
│   │       └── PaginatedResponse.php
│   ├── Driver/
│   │   ├── Controllers/
│   │   │   └── DriverController.php
│   │   └── Requests/
│   │       ├── CreateDriverRequest.php
│   │       ├── UpdateDriverRequest.php
│   │       ├── ListDriversRequest.php
│   │       └── AssignVehicleRequest.php
│   └── Vehicle/
│       ├── Controllers/
│       │   └── VehicleController.php
│       └── Requests/
│           ├── CreateVehicleRequest.php
│           ├── UpdateVehicleRequest.php
│           ├── ListVehiclesRequest.php
│           └── AssignDriverRequest.php
├── Models/                             # Eloquent models
│   ├── Driver.php
│   ├── Vehicle.php
│   └── User.php
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_drivers_table.php
│   │   ├── create_vehicles_table.php
│   │   └── create_driver_vehicle_table.php
│   └── factories/
│       ├── DriverFactory.php
│       ├── VehicleFactory.php
│       └── UserFactory.php
├── tests/
│   ├── Feature/
│   │   ├── Driver/
│   │   │   └── DriverApiTest.php
│   │   ├── Vehicle/
│   │   │   └── VehicleApiTest.php
│   │   └── Filament/
│   │       └── FilamentAdminAccessTest.php
│   ├── Integration/
│   │   ├── Container/
│   │   │   └── DockerConnectionTest.php
│   │   ├── Database/
│   │   │   ├── MigrationTest.php
│   │   │   └── ModelRelationTest.php
│   └── Unit/
│       └── Presentation/
│           └── Base/
│               ├── BaseControllerTest.php
│               ├── BaseRequestTest.php
│               ├── BaseResponseTest.php
│               └── PaginatedResponseTest.php
├── docker/
│   └── nginx/
│       └── conf.d/
│           └── default.conf
├── docker-compose.yml
├── Dockerfile
├── Makefile
└── README.md
```

---

## Key Design Patterns

### 1. Repository Pattern
All data access goes through repository interfaces, enabling easy testing and database abstraction.

```php
interface DriverRepositoryInterface extends BaseRepositoryInterface
{
    public function searchAndPaginate(...);
    public function assignVehicle(...);
}
```

### 2. Use Case Pattern
Business logic is encapsulated in use cases, separating it from HTTP concerns.

```php
class DriverUseCase extends BaseUsecase implements DriverUseCaseInterface
{
    public function store(array $data): Driver;
    public function update(string $id, array $data): ?Driver;
}
```

### 3. DTO (Data Transfer Objects)
Readonly request and response objects ensure type safety and immutability.

```php
readonly class CreateDriverRequest extends BaseRequest
{
    public function __construct(
        public string $name,
        public string $licenseNumber,
        public string $phoneNumber,
    ) {
        parent::__construct();
    }
}
```

### 4. Service Layer (Handlers)
Handlers coordinate between layers without containing business logic.

```php
class DriverHandler
{
    public function store(CreateDriverRequest $request): SingleDriverResponse
    {
        $driver = $this->driverUseCase->store([...]);
        return new SingleDriverResponse(...);
    }
}
```

### 5. Dependency Injection
All dependencies are injected via Laravel's service container.

```php
public function __construct(
    private readonly DriverUseCaseInterface $driverUseCase
) {}
```

---

## Development Workflow

### Running Tests
```bash
make test                                    # All tests
docker exec -i app php artisan test --filter=DriverApiTest  # Specific test
docker exec -i app php artisan test --testsuite=Feature     # Feature tests only
docker exec -i app php artisan test --testsuite=Unit        # Unit tests only
```

### Database Operations
```bash
# Run migrations
docker exec -i app php artisan migrate

# Fresh migration with seeding
docker exec -i app php artisan migrate:fresh --seed

# Rollback last migration
docker exec -i app php artisan migrate:rollback

# Create new migration
docker exec -i app php artisan make:migration create_example_table
```

### Accessing Logs
```bash
# Application logs
docker logs app

# Nginx logs
docker logs laravel_nginx

# MySQL logs
docker logs laravel_mysql

# Follow logs in real-time
docker logs -f app
```

### Artisan Commands
```bash
# Create new controller
docker exec -i app php artisan make:controller ExampleController

# Create new model
docker exec -i app php artisan make:model Example

# Create factory
docker exec -i app php artisan make:factory ExampleFactory

# Clear cache
docker exec -i app php artisan cache:clear
docker exec -i app php artisan config:clear
docker exec -i app php artisan route:clear
```

### Composer Operations
```bash
# Install dependencies
docker exec -i app composer install

# Update dependencies
docker exec -i app composer update

# Dump autoload
docker exec -i app composer dump-autoload
```

---

## API Response Format

### Success Response
```json
{
  "success": true,
  "data": {
    "id": "019a97cd-1841-71ba-81df-3b304f8fd0b9",
    "name": "John Doe",
    "license_number": "DL123456",
    "phone_number": "+1234567890",
    "created_at": "2025-11-18T12:00:00Z",
    "updated_at": "2025-11-18T12:00:00Z"
  }
}
```

### Paginated Response
```json
{
  "success": true,
  "data": {
    "drivers": [
      {
        "id": "uuid",
        "name": "John Doe",
        "license_number": "DL123456",
        "phone_number": "+1234567890",
        "vehicles_count": 2,
        "has_active_vehicles": true,
        "created_at": "2025-11-18T12:00:00Z",
        "updated_at": "2025-11-18T12:00:00Z"
      }
    ],
    "meta": {
      "total": 100,
      "per_page": 15,
      "current_page": 1,
      "last_page": 7,
      "from": 1,
      "to": 15
    }
  }
}
```

### Error Response
```json
{
  "success": false,
  "error": {
    "message": "Driver not found with id: uuid",
    "code": 404
  }
}
```

### Validation Error Response
```json
{
  "message": "The name field is required. (and 2 more errors)",
  "errors": {
    "name": ["The name field is required."],
    "license_number": ["The license number field is required."],
    "phone_number": ["The phone number field is required."]
  }
}
```

---

## Troubleshooting

### Container Issues

**Containers won't start:**
```bash
make down
docker system prune -a
make build
```

**Permission issues:**
```bash
make shell-as-root
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache
```

### Database Issues

**Connection refused:**
```bash
# Check if MySQL is running
docker ps | grep mysql

# Check MySQL logs
docker logs laravel_mysql

# Verify .env credentials match docker-compose.yml
```

**Migration errors:**
```bash
# Drop all tables and re-migrate
docker exec -i app php artisan migrate:fresh

# Check database connection
docker exec -i app php artisan tinker
>>> DB::connection()->getPdo();
```

### Test Failures

**Database-related test failures:**
```bash
# Ensure test database is running
docker ps | grep mysql_test

# Clear test database
docker exec -i app php artisan config:clear
make test
```

**Factory issues:**
```bash
# Regenerate autoload
docker exec -i app composer dump-autoload
```

---

## Performance Optimization

### Redis Caching
```php
// Cache driver queries
Cache::remember('drivers-list', 60, function () {
    return Driver::with('vehicles')->get();
});
```

### Query Optimization
```php
// Eager load relationships to avoid N+1 queries
Driver::with('vehicles')->paginate(15);

// Use select to limit columns
Driver::select(['id', 'name', 'license_number'])->get();
```

### Database Indexing
```php
// Already indexed in migrations:
// - license_number (unique)
// - phone_number (unique)
// - plate_number (unique)
// - vin (unique)
```

---

## Security Considerations

### Authentication
- Session-based authentication via Laravel Sanctum
- Admin panel protected by Filament authentication
- CSRF protection on all POST/PUT/DELETE requests

### Input Validation
- All requests validated via custom Request classes
- Snake_case to camelCase conversion for consistency
- Type-safe readonly DTOs prevent mutation

### Database
- Soft deletes for data recovery
- UUID primary keys for security
- Prepared statements (Eloquent) prevent SQL injection

---

## Contributing

### Branch Naming Convention
- `feature/driver-module`
- `bugfix/validation-error`
- `hotfix/security-patch`

### Commit Message Format
```
type(scope): subject

body

footer
```

Example:
```
feat(driver): add vehicle assignment endpoint

- Implement assign-vehicle endpoint
- Add validation for vehicle_id
- Update driver response with vehicles

Closes #123
```

### Pull Request Process
1. Create a feature branch from `main`
2. Write tests for new functionality
3. Ensure all tests pass (`make test`)
4. Update documentation if needed
5. Submit PR with clear description
6. Wait for code review

---

## Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Configure proper database credentials
- [ ] Set up SSL certificates for Nginx
- [ ] Configure Redis for production
- [ ] Set up backup strategy
- [ ] Configure monitoring and logging
- [ ] Run `php artisan optimize`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_HOST=production-db-host
DB_DATABASE=production_db
DB_USERNAME=production_user
DB_PASSWORD=secure_password

REDIS_HOST=production-redis-host
REDIS_PASSWORD=redis_password
```

---

## License

This project is proprietary and confidential.

---

## Support

For issues or questions, please contact the development team.

### Issue Reporting
When reporting issues, please include:
- Environment details (OS, Docker version)
- Steps to reproduce
- Expected vs actual behavior
- Relevant logs
- Screenshots if applicable

### Contact
- Email: support@example.com
- Slack: #tug-project
- Documentation: https://docs.example.com
