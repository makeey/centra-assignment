<?php

namespace KanbanBoard\ExternalService\Github;


use KanbanBoard\Infrastructure\Interfaces\IssueFactory;
use KanbanBoard\Infrastructure\Interfaces\MilestoneFactory;
use KanbanBoard\Infrastructure\Interfaces\Service;

class Github implements Service
{
    private $clientFactory;
    private $milestoneFactory;
    private $issueFactory;

    public function __construct(
        ClientFactoryInterface $clientFactory,
        IssueFactory $issueFactory,
        MilestoneFactory $milestoneFactory
    )
    {
        $this->clientFactory = $clientFactory;
        $this->milestoneFactory = $milestoneFactory;
        $this->issueFactory = $issueFactory;
    }

    public function milestones(string $account, string $repository): array
    {
        return array_map(function ($datum){
            return $this->milestoneFactory->milestone($datum);
        }, $this->clientFactory->milestoneClient()->all($account, $repository));
    }

    public function issues(string $account,string $repository,  int $milestoneId): array
    {
        $issue_parameters = array('milestone' => $milestoneId, 'state' => 'all');
        $issues = array_filter($this->clientFactory->issueClient()->all($account, $repository, $issue_parameters),
            function ($issues) {
                return empty($issues['pull_request']);
            }
        );
        return array_map(function ($issue) {
            return $this->issueFactory->issue($issue);
        }, $issues);
    }
}