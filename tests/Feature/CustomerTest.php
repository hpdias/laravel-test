<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;
    
    public function testCreateCustomer(){

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/customer', [
            'name' => 'Test Name',
            'document' => '123456789',
            'status' => '1',
            'user_id' => $user->id
        ]);

        $this->assertCount(1, Customer::all());

    }

    public function testCreateCustomerWithoutName() {        

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/customer', [
            'name' => '',
            'document' => '123456789',
            'status' => '1',
            'user_id' => $user->id
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertCount(0, Customer::all());

    }

    public function testCreateCustomerWithoutDocument() {        

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/customer', [
            'name' => 'Teste Name',
            'document' => '',
            'status' => '1',
            'user_id' => $user->id
        ]);

        $response->assertSessionHasErrors('document');
        $this->assertCount(0, Customer::all());

    }

    public function testCreateCustomerWithDocumentMinSix() {        

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/customer', [
            'name' => 'Teste Name',
            'document' => '12',
            'status' => '1',
            'user_id' => $user->id
        ]);

        $response->assertSessionHasErrors('document');
        $this->assertCount(0, Customer::all());

    }

    public function testCreateCustomerWithDocumentMaxTwelve() {        

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/customer', [
            'name' => 'Teste Name',
            'document' => '123456789101112',
            'status' => '1',
            'user_id' => $user->id
        ]);

        $response->assertSessionHasErrors('document');
        $this->assertCount(0, Customer::all());

    }

    public function testCreateCustomerWithoutStatus() {        

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/customer', [
            'name' => 'Teste Name',
            'document' => '123456789',
            'status' => '',
            'user_id' => $user->id
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertCount(0, Customer::all());

    }

}
