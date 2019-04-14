<?php
namespace KanbanBoard;

use KanbanBoard\Entities\Issue;
use KanbanBoard\Entities\Milestone;
use KanbanBoard\Infrastructure\Board as BoardInterface;

class Board implements BoardInterface
{
    private $github;
    private $repositories;
    private $account;

    public function __construct(Github $github, array $repositories, $account)
	{
	    $this->account = $account;
		$this->github = $github;
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
        return array_map(
            function (Milestone $milestone) use($repository) {
                $milestone->withIssues(
                    ...$this->issueForMilestoneWithoutPullRequest($repository,$milestone->number())
                );
                return $milestone;
            },
            $this->github->milestones($this->account, $repository)
        );
    }

    private function issueForMilestoneWithoutPullRequest(string $repository, int $milestoneNumber){
        return array_filter(
            $this->github->issues($this->account,$repository, $milestoneNumber), static function(Issue $issue){
            return !$issue->isHasPullRequest();
        });
    }

}
