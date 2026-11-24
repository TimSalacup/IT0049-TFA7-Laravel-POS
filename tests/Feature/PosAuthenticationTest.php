<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class PosAuthenticationTest extends TestCase {
 use RefreshDatabase;
 public function test_guests_are_redirected(): void { foreach(['/customers','/users','/customers/create','/users/create'] as $url) $this->get($url)->assertRedirect('/login'); }
 public function test_registration_allows_access_and_logout_blocks_it(): void {
  $this->post('/register',['name'=>'Test Person','username'=>'testperson','email'=>'test@example.com','password'=>'password123','password_confirmation'=>'password123'])->assertRedirect('/dashboard');
  $this->assertAuthenticated();
  $this->get('/customers')->assertOk(); $this->get('/users')->assertOk();
  $this->post('/logout')->assertRedirect('/'); $this->assertGuest(); $this->get('/users')->assertRedirect('/login');
 }
}