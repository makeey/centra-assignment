<?php


namespace KanbanBoard\ExternalService\Github;


use Github\Api\Issue;
use Github\Client;
use Github\HttpClient\CachedHttpClient;
use KanbanBoard\Infrastructure\Interfaces\TokenProvider;

class ClientFactory implements ClientFactoryInterface
{

    /** @var Client  */
    private $client;
    /** @var Issue\Milestones */
    private $milestoneClient;
    /** @var Issue */
    private $issueClient;

    public function __construct(TokenProvider $token)
    {
        $this->client = new Client(new CachedHttpClient(array('cache_dir' => '/tmp/github-api-cache')));
        $this->client->authenticate($token->tokenStrictly(), Client::AUTH_HTTP_TOKEN);
        $this->issueClient = $this->client->issue();
        $this->milestoneClient = $this->issueClient->milestones();
    }

    public function issueClient(): Issue
    {
        return $this->issueClient;
    }

    public function milestoneClient(): Issue\Milestones
    {
        return $this->milestoneClient;
    }
}