<?php

namespace App\Oauth;

use Laravel\Passport\ClientRepository;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JWTParser;
use League\OAuth2\Server\AuthorizationServer;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class HostTokenFactory
{
    /**
     * The authorization server instance.
     *
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    protected $server;

    /**
     * The client repository instance.
     *
     * @var \Laravel\Passport\ClientRepository
     */
    protected $clients;

    /**
     * The token repository instance.
     *
     * @var \Laravel\Passport\TokenRepository
     */
    protected $tokens;

    /**
     * The JWT token parser instance.
     *
     * @var \Lcobucci\JWT\Parser
     */
    protected $jwt;

    /**
     * Create a new personal access token factory instance.
     *
     * @param  \League\OAuth2\Server\AuthorizationServer  $server
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @param  \Lcobucci\JWT\Parser  $jwt
     * @return void
     */
    public function __construct(
        AuthorizationServer $server,
        ClientRepository $clients,
        TokenRepository $tokens,
        JwtParser $jwt
    ) {
        $this->jwt = $jwt;
        $this->tokens = $tokens;
        $this->server = $server;
        $this->clients = $clients;
    }

    /**
     * Create a new personal access token.
     *
     * @param  mixed $userId
     * @param  string $name
     * @param  array $scopes
     * @return HostTokenResult
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function make($userId, $name, array $scopes = [])
    {
        $response = $this->dispatchRequestToAuthorizationServer(
            $this->createRequest($this->clients->personalAccessClient(), $userId, $scopes)
        );

        $token = tap($this->findAccessToken($response), function ($token) use ($userId, $name) {
            $this->tokens->save($token->forceFill([
                'user_id' => $userId,
                'name' => $name,
            ]));
        });

        return new HostTokenResult(
            $response['access_token'],
            $token
        );
    }

    /**
     * Create a request instance for the given client.
     *
     * @param  \Laravel\Passport\Client  $client
     * @param  mixed  $userId
     * @param  array  $scopes
     * @return \Zend\Diactoros\ServerRequest
     */
    protected function createRequest($client, $userId, array $scopes)
    {
        return (new ServerRequest)->withParsedBody([
            'grant_type' => 'host',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'user_id' => $userId,
            'scope' => implode(' ', $scopes),
        ]);
    }

    /**
     * Dispatch the given request to the authorization server.
     *
     * @param  \Zend\Diactoros\ServerRequest $request
     * @return array
     */
    protected function dispatchRequestToAuthorizationServer(ServerRequest $request)
    {
        return json_decode($this->server->respondToAccessTokenRequest(
            $request,
            new Response
        )->getBody()->__toString(), true);
    }

    /**
     * Get the access token instance for the parsed response.
     *
     * @param  array  $response
     * @return \Laravel\Passport\Token
     */
    protected function findAccessToken(array $response)
    {
        return $this->tokens->find(
            $this->jwt->parse($response['access_token'])->getClaim('jti')
        );
    }
}