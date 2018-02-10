<?php

namespace Tests\Feature\Api\V2;

use App\Enums\HostRequestStatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class HostRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @var $faker \Faker\Generator */
    private $faker;
    private $localIpv4;
    private $ipv4;
    private $hostname;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
        $this->localIpv4 = $this->faker->localIpv4;
        $this->ipv4 = $this->faker->ipv4;
        $this->hostname = $this->faker->domainWord;

        parent::setUp();
    }

    public function testClientRequestHasStatusOfRequested() {
        $response = $this->json('POST', '/api/v2/hosts/request', [
            'local_ip' => $this->localIpv4,
            'remote_ip' => $this->ipv4,
            'hostname' => $this->hostname,
        ]);

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
            'data' => [
                'status' => HostRequestStatusEnum::Requested
            ]
        ]);
    }

    public function testNoInformationIsNeededForRequest()
    {
        $response = $this->json('POST', '/api/v2/hosts/request');

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
            'data' => [
                'status' => HostRequestStatusEnum::Requested
            ]
        ]);
    }
}