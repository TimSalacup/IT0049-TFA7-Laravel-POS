<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PosAccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_validation_and_edit(): void
    {
        $this->post('/customers', ['full_name' => '', 'email' => 'bad'])->assertSessionHasErrors(['full_name', 'email']);
        $this->post('/customers', ['full_name' => 'Ana', 'email' => 'ana@example.com'])->assertRedirect('/customers');
        $customer = Customer::firstOrFail();
        $this->get('/customers/'.$customer->id.'/edit')->assertOk()->assertSee('Ana');
        $this->patch('/customers/'.$customer->id, ['full_name' => 'Ana Cruz', 'email' => 'ana@example.com'])->assertRedirect('/customers');
        $this->assertDatabaseHas('customers', ['full_name' => 'Ana Cruz']);
    }

    public function test_username_validation_and_avatar_replacement(): void
    {
        Storage::fake('public');
        $this->post('/users', ['username' => 'ana', 'full_name' => 'Ana'])->assertRedirect('/users');
        $user = User::where('username', 'ana')->firstOrFail();
        $this->post('/users', ['username' => 'ana', 'full_name' => 'Other'])->assertSessionHasErrors('username');
        $this->patch('/users/'.$user->id, ['username' => 'ana', 'full_name' => 'Ana', 'avatar' => UploadedFile::fake()->create('bad.txt', 10, 'text/plain')])->assertSessionHasErrors('avatar');
        $this->patch('/users/'.$user->id, ['username' => 'ana', 'full_name' => 'Ana', 'avatar' => UploadedFile::fake()->image('test.jpg', 300, 300)])->assertRedirect('/users');
        $old = $user->fresh()->avatar;
        Storage::disk('public')->assertExists($old);
        $this->patch('/users/'.$user->id, ['username' => 'ana', 'full_name' => 'Ana', 'avatar' => UploadedFile::fake()->image('new.jpg', 300, 300)])->assertRedirect('/users');
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($user->fresh()->avatar);
        $this->get('/users')->assertOk()->assertSee('ana');
    }
}
