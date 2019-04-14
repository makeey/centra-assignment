<?php

namespace KanbanBoard;

use Github\Client;
use Github\HttpClient\CachedHttpClient;
use KanbanBoard\Infrastructure\TokenProviderInterface;

class Github
{
    private $client;
    private $account;

    public function __construct(TokenProviderInterface $token, string $account)
    {
        $this->account = $account;
        $this->client = new Client(new CachedHttpClient(array('cache_dir' => '/tmp/github-api-cache')));
        $this->client->authenticate($token->tokenStrictly(), Client::AUTH_HTTP_TOKEN);
        $this->milestone_api = $this->client->api('issues')->milestones();
    }

    public function milestones($repository)
    {
        return $this->milestone_api->all($this->account, $repository);
    }

    public function issues($repository, $milestone_id)
    {
        $issue_parameters = array('milestone' => $milestone_id, 'state' => 'all');
        return $this->client->api('issue')->all($this->account, $repository, $issue_parameters);
    }
}