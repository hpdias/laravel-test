<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Number;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NumberTest extends TestCase
{
    use RefreshDatabase;
    
    public function testCreateNumber(){

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/number', [
            'number' => '123456789',
            'status' => '1',
            'customer_id' => Customer::factory()->create()->id
        ]);

        $this->assertCount(1, Number::all());

    }

    public function testCreateNumberWithoutNumber() {        

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/number', [
            'number' => '',
            'status' => '1',
            'customer_id' => Customer::factory()->create()->id
        ]);

        $response->assertSessionHasErrors('number');
        $this->assertCount(0, Number::all());

    }

    public function testCreateNumberWithNumberMinEight() {        

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/number', [
            'number' => '123',
            'status' => '1',
            'customer_id' => Customer::factory()->create()->id
        ]);

        $response->assertSessionHasErrors('number');
        $this->assertCount(0, Number::all());

    }

    public function testCreateNumberWithNumberMaxFourteen() {        

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/number', [
            'number' => '123456789101112131415',
            'status' => '1',
            'customer_id' => Customer::factory()->create()->id
        ]);

        $response->assertSessionHasErrors('number');
        $this->assertCount(0, Number::all());

    }

    public function testCreateNumberWithoutStatus() {        

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/number', [
            'number' => '123456789',
            'status' => '',
            'customer_id' => Customer::factory()->create()->id
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertCount(0, Number::all());

    }


}
