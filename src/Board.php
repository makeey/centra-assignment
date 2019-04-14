<?php

namespace KanbanBoard;

use KanbanBoard\Entities\Issue;
use KanbanBoard\Entities\Milestone;
use KanbanBoard\ExternalService\Service;
use KanbanBoard\Infrastructure\Board as BoardInterface;


class Board implements BoardInterface
{
    private $github;
    private $repositories;
    private $account;

    public function __construct(Service $service, array $repositories, $account)
    {
        $this->account = $account;
        $this->github = $service;
        $this->repositories = $repositories;
    }

    public function board(): array
    {
        $milestones = [];
        foreach ($this->repositories as $repository) {
            $milestones[] = $this->milestoneForRepositoryWithIssues($repository);
        }
        $milestones = array_map(function (Milestone $milestone) {
            return $milestone->jsonSerialize();
        }, ...$milestones);
        array_column($milestones, 'milestone');
        ksort($milestones);
        return $milestones;
    }

    private function milestoneForRepositoryWithIssues($repository)
    {
        $milestones = $this->github->milestones($this->account, $repository);
        $milestones = array_filter($milestones, static function (Milestone $milestone): bool {
            return null !== $milestone->progress()->percent();
        });
        return array_map(
            function (Milestone $milestone) use ($repository) {
                $milestone->withIssues(
                    ...$this->issueForMilestoneWithoutPullRequest($repository, $milestone->number())
                );
                return $milestone;
            },
            $milestones
        );
    }

    private function issueForMilestoneWithoutPullRequest(string $repository, int $milestoneNumber)
    {
        return array_filter(
            $this->github->issues($this->account, $repository, $milestoneNumber), static function (Issue $issue) {
            return !$issue->isHasPullRequest();
        });
    }

}
