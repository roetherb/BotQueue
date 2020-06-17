<?php

namespace Tests\Helpers;

use App;
use Illuminate\Support\Arr;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\PersonalAccessClient;

trait PassportHelper
{
    private $userClientSetUp = false;

    private function setUpPersonalClient()
    {
        if ($this->userClientSetUp) {
            return;
        }

        $clients = app(ClientRepository::class);

        $client = $clients->createPersonalAccessClient(
            null,
            'TestPersonalClient',
            'http://localhost'
        );

        $accessClient = new PersonalAccessClient();
        $accessClient->client_id = $client->id;
        $accessClient->save();

        $this->userClientSetUp = true;
    }

    /**
     * @param $user App\User
     * @param array $scopes
     * @return $this
     */
    public function withTokenFromUser($user, $scopes = '*')
    {
        $this->setUpPersonalClient();

        $token = $user->createToken('Test Token', Arr::wrap($scopes));

        $this->withAccessToken($token->accessToken);

        return $this;
    }

    /**
     * @param $host App\Host
     * @return $this
     */
    public function withTokenFromHost($host)
    {
        $this->withAccessToken($host->getJWT());

        return $this;
    }

    /**
     * @param $accessToken
     * @return $this
     */
    public function withAccessToken($accessToken)
    {
        $this->withHeader('Authorization', 'Bearer '.$accessToken);

        return $this;
    }
}