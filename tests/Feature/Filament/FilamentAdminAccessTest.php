<?php

namespace Tests\Feature\Filament;

use App\Filament\Admin;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\FeatureTestCase;

class FilamentAdminAccessTest extends FeatureTestCase
{
    #[Test]
    public function admin_user_can_access_filament_dashboard(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin');

        $response->assertStatus(200);
    }

    #[Test]
    public function guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin');

        // Filament returns 401 Unauthorized for guests
        $response->assertStatus(401);
    }

    #[Test]
    public function admin_can_access_driver_resource(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/drivers');

        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_access_vehicle_resource(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/vehicles');

        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_view_login_page(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Sign in');
    }

    #[Test]
    public function authenticated_admin_can_access_resources(): void
    {
        $admin = Admin::factory()->create();

        // Act as admin
        $this->actingAs($admin, 'admin');

        // Test dashboard access
        $dashboardResponse = $this->get('/admin');
        $dashboardResponse->assertStatus(200);

        // Test drivers resource
        $driversResponse = $this->get('/admin/drivers');
        $driversResponse->assertStatus(200);

        // Test vehicles resource
        $vehiclesResponse = $this->get('/admin/vehicles');
        $vehiclesResponse->assertStatus(200);

        // Verify authenticated
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    #[Test]
    public function admin_can_be_created_via_factory(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'factory-test@example.com',
        ]);

        $this->assertDatabaseHas('admins', [
            'email' => 'factory-test@example.com',
        ]);

        $this->assertNotNull($admin->password);
    }

    #[Test]
    public function multiple_admins_can_be_created_with_unique_emails(): void
    {
        $admin1 = Admin::factory()->create();
        $admin2 = Admin::factory()->create();
        $admin3 = Admin::factory()->create();

        $this->assertNotEquals($admin1->email, $admin2->email);
        $this->assertNotEquals($admin2->email, $admin3->email);
        $this->assertNotEquals($admin1->email, $admin3->email);

    }
}
