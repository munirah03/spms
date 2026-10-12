<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    private function makeOrder(): Order
    {
        return Order::create([
            'order_id'           => 'PDT001',
            'customer_name'      => 'Test Customer',
            'contact_info'       => '012-3456789',
            'file_original_name' => 'a.pdf',
            'file_stored_name'   => 'unit-test-file.pdf',
            'file_type'          => 'pdf',
            'file_size'          => 10,
            'page_count'         => 1,
            'copies'             => 1,
            'color_mode'         => 'black_white',
            'paper_size'         => 'A4',
            'orientation'        => 'portrait',
            'payment_method'     => 'counter',
            'estimated_price'    => 0.5,
            'status'             => 'pending',
        ]);
    }

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'Intruder',
            'email' => 'intruder@example.com',
            'password' => 'Str0ng!Passw0rd',
            'password_confirmation' => 'Str0ng!Passw0rd',
        ])->assertStatus(405);

        $this->assertDatabaseMissing('users', ['email' => 'intruder@example.com']);
    }

    public function test_guests_cannot_open_staff_pages(): void
    {
        $this->get('/staff/dashboard')->assertRedirect('/login');
    }

    public function test_staff_cannot_open_supervisor_pages(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        foreach (['/staff/reports', '/staff/accounts', '/staff/audit-log'] as $url) {
            $this->actingAs($staff)->get($url)->assertForbidden();
        }
    }

    public function test_supervisor_can_open_audit_log(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);

        $this->actingAs($supervisor)->get('/staff/audit-log')->assertOk();
    }

    public function test_new_account_must_change_password_before_using_the_system(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);

        $this->actingAs($supervisor)->post('/staff/accounts', [
            'name' => 'New Staff',
            'email' => 'newstaff@example.com',
            'role' => 'staff',
            'password' => 'Temp-Passw0rd!',
            'password_confirmation' => 'Temp-Passw0rd!',
        ])->assertRedirect(route('staff.accounts.index'));

        $newStaff = User::where('email', 'newstaff@example.com')->firstOrFail();
        $this->assertTrue($newStaff->must_change_password);

        $this->actingAs($newStaff)->get('/staff/dashboard')->assertRedirect(route('profile.edit'));

        $this->actingAs($newStaff)->put('/password', [
            'current_password' => 'Temp-Passw0rd!',
            'password' => 'Brand-New-Passw0rd!',
            'password_confirmation' => 'Brand-New-Passw0rd!',
        ])->assertSessionHasNoErrors();

        $this->assertFalse($newStaff->fresh()->must_change_password);
        $this->actingAs($newStaff->fresh())->get('/staff/dashboard')->assertOk();
    }

    public function test_supervisor_can_reset_a_staff_password(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);
        $staff = User::factory()->create(['role' => 'staff', 'must_change_password' => false]);

        $this->actingAs($supervisor)->patch(route('staff.accounts.reset-password', $staff), [
            'password' => 'New-Temp-Passw0rd!',
            'password_confirmation' => 'New-Temp-Passw0rd!',
        ])->assertRedirect(route('staff.accounts.index'));

        $staff->refresh();
        $this->assertTrue($staff->must_change_password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('New-Temp-Passw0rd!', $staff->password));
        $this->assertDatabaseHas('audit_logs', ['action' => 'password_reset_by_supervisor']);
    }

    public function test_staff_cannot_reset_another_accounts_password(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $other = User::factory()->create(['role' => 'staff']);

        $this->actingAs($staff)->patch(route('staff.accounts.reset-password', $other), [
            'password' => 'New-Temp-Passw0rd!',
            'password_confirmation' => 'New-Temp-Passw0rd!',
        ])->assertForbidden();
    }

    public function test_forgot_password_routes_no_longer_exist(): void
    {
        $this->get('/forgot-password')->assertNotFound();
    }

    public function test_file_link_needs_a_valid_signature(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $order = $this->makeOrder();

        Storage::put('private_uploads/unit-test-file.pdf', "%PDF-1.4\n%%EOF");

        $this->actingAs($staff)->get(route('staff.file', $order))->assertForbidden();

        $signed = URL::temporarySignedRoute('staff.file', now()->addMinutes(5), ['order' => $order->id]);
        $this->actingAs($staff)->get($signed)->assertOk();

        Storage::delete('private_uploads/unit-test-file.pdf');
    }

    public function test_fake_documents_are_rejected(): void
    {
        $file = UploadedFile::fake()->createWithContent('evil.pdf', 'this is not a pdf');

        $this->post('/order', [
            'customer_name' => 'A',
            'contact_info' => '012',
            'file' => $file,
            'copies' => 1,
            'color_mode' => 'black_white',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'payment_method' => 'counter',
        ])->assertSessionHasErrors('file');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_valid_upload_gets_a_hard_to_guess_order_id(): void
    {
        $file = UploadedFile::fake()->createWithContent('doc.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF");

        $this->post('/order', [
            'customer_name' => 'A',
            'contact_info' => '012',
            'file' => $file,
            'copies' => 1,
            'color_mode' => 'black_white',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'payment_method' => 'counter',
        ])->assertRedirect(route('orders.success'));

        $order = Order::firstOrFail();
        $this->assertMatchesRegularExpression('/^[A-Z2-9]{6}$/', $order->order_id);

        $order->deleteStoredFiles();
    }

    public function test_status_change_is_audited_and_collected_removes_every_copy(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $order = $this->makeOrder();
        $order->update(['file_stored_name' => 'unit-test-file.pdf']);

        Storage::put('private_uploads/unit-test-file.pdf', 'converted');
        Storage::put('private_uploads/unit-test-file.docx', 'original');

        $this->actingAs($staff)
            ->post(route('staff.update', $order), ['status' => 'collected'])
            ->assertRedirect(route('staff.dashboard'));

        $this->assertFalse(Storage::exists('private_uploads/unit-test-file.pdf'));
        $this->assertFalse(Storage::exists('private_uploads/unit-test-file.docx'));
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'status_changed',
            'order_id' => 'PDT001',
            'user_id' => $staff->id,
        ]);
    }

    public function test_failed_and_successful_logins_are_recorded(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password']);
        $this->post('/login', ['email' => $user->email, 'password' => 'password']);

        $this->assertTrue(AuditLog::where('action', 'login_failed')->exists());
        $this->assertTrue(AuditLog::where('action', 'login')->where('user_id', $user->id)->exists());
    }

    public function test_security_headers_are_sent(): void
    {
        $this->get('/')->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }
}
