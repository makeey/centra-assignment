<?php

namespace KanbanBoard;

use Github\Client;
use Github\HttpClient\CachedHttpClient;
use KanbanBoard\ExternalService\ClientFactory;
use KanbanBoard\Infrastructure\TokenProviderInterface;

class Github
{
    private $account;
    private $clientFactory;

    public function __construct(ClientFactory $clientFactory, string $account)
    {
        $this->account = $account;
        $this->clientFactory = $clientFactory;
    }

    public function milestones($repository)
    {
        return $this->clientFactory->milestoneClient()->all($this->account, $repository);
    }

    public function issues($repository, $milestone_id)
    {
        $issue_parameters = array('milestone' => $milestone_id, 'state' => 'all');
        return $this->clientFactory->issueClient()->all($this->account, $repository, $issue_parameters);
    }
}