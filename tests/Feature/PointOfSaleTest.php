<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\PointOfSale;

class PointOfSaleTest extends TestCase
{
    public function test_point_of_sale_component_renders_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(PointOfSale::class)
            ->assertSee('Scan or search products');
    }
}
