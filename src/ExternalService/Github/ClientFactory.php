<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\ExternalService\Github;

use Github\Api\Issue;
use Github\Client;
use KanbanBoard\Infrastructure\Interfaces\TokenProvider;
use Psr\Cache\CacheItemPoolInterface;

class ClientFactory implements ClientFactoryInterface
{
    /** @var Client  */
    private $client;
    /** @var Issue\Milestones */
    private $milestoneClient;
    /** @var Issue */
    private $issueClient;

    public function __construct(TokenProvider $token, CacheItemPoolInterface $pool)
    {
        $this->client = new Client();
        $this->client->addCache($pool);
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
