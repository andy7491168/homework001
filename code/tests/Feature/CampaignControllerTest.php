<?php

namespace Tests\Feature;

use App\Models\Campaign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Services\CampaignService;
use Mockery;

class CampaignControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_add_campaign_success()
    {
        $response = $this->postJson('/api/v1/campaign', [
            'name' => 'UnitTestingFromAndy'
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'msg' => 'success'
                 ]);

        $this->assertDatabaseHas('campaigns', [
            'name' => 'UnitTestingFromAndy'
        ]);

        Campaign::where('name', 'UnitTestingFromAndy')->delete();

        $this->assertDatabaseMissing('campaigns', [
            'name' => 'UnitTestingFromAndy'
        ]);
    }

    public function test_add_campaign_validation_failure()
    {
        $response = $this->postJson('/api/v1/campaign', [
            'name' => '' 
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'msg' => 'The given data was invalid'
                 ]);
    }


}
