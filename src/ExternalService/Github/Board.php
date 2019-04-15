<?php

namespace KanbanBoard\ExternalService\Github;

use KanbanBoard\Entities\Issue;
use KanbanBoard\Entities\Milestone;
use KanbanBoard\Infrastructure\Interfaces\Board as BoardInterface;
use KanbanBoard\Infrastructure\Interfaces\Service;


class Board implements BoardInterface
{
    /** @var Service  */
    private $github;
    /** @var array  */
    private $repositories;
    /** @var string */
    private $account;

    public function __construct(Service $service, array $repositories, string $account)
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
        }, array_merge(...$milestones));
        array_column($milestones, 'milestone');
        ksort($milestones);
        return $milestones;
    }

    private function milestoneForRepositoryWithIssues(string $repository): array
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

    private function issueForMilestoneWithoutPullRequest(string $repository, int $milestoneNumber): array
    {
        return array_filter(
            $this->github->issues($this->account, $repository, $milestoneNumber), static function (Issue $issue): bool {
            return !$issue->isHasPullRequest();
        });
    }

}
