<?php
namespace KanbanBoard;

use KanbanBoard\Entities\Milestone;
use KanbanBoard\Infrastructure\Board as BoardInterface;
use KanbanBoard\Infrastructure\IssueFactory;
use KanbanBoard\Infrastructure\MilestoneFactory;
use \Michelf\Markdown;

class Board implements BoardInterface
{

    private $github;
    private $repositories;

    private $milestoneFactory;

    private $issueFactory;

    public function __construct(Github $github, array $repositories, array $paused_labels = [])
	{
		$this->github = $github;
		$this->repositories = $repositories;
		$this->issueFactory = new IssueFactory(new Markdown(),$paused_labels);
		$this->milestoneFactory = new MilestoneFactory();
	}

	public function board(): array
	{
		$ms = array();
		foreach ($this->repositories as $repository)
		{
			foreach ($this->github->milestones($repository) as $data)
			{
				$ms[$data['title']] = $this->milestoneFactory->milestone(array_merge($data, ['repository' => $repository]));

			}
		}
		ksort($ms);
        $milestones = [];
        /** @var Milestone $m */
        foreach ($ms as $m)
		{
			$issues = $this->issues($m->repository(),$m->number());
			$m->withIssues(...$issues);
			$milestones[] = $m;
		}
        return array_map(function (Milestone $milestone){
            return $milestone->jsonSerialize();
        },$milestones);
	}

	private function issues($repository, $milestone_id)
	{
		$i = $this->github->issues($repository, $milestone_id);
        $issues = [];
		foreach ($i as $ii)
		{
			if (isset($ii['pull_request']))
				continue;
			$issues[] = $this->issueFactory->issue($ii);
		}

		return $issues;
	}

	private static function _state($issue)
	{
		if ($issue['state'] === 'closed')
			return 'completed';
		else if (Utilities::hasValue($issue, 'assignee') && count($issue['assignee']) > 0)
			return 'active';
		else
			return 'queued';
	}

	private static function labels_match($issue, $needles)
	{
		if(Utilities::hasValue($issue, 'labels')) {
			foreach ($issue['labels'] as $label) {
				if (in_array($label['name'], $needles)) {
					return array($label['name']);
				}
			}
		}
		return array();

	}

	private static function _percent($complete, $remaining)
	{
		$total = $complete + $remaining;
		if($total > 0)
		{
			$percent = ($complete OR $remaining) ? round($complete / $total * 100) : 0;
			return array(
				'total' => $total,
				'complete' => $complete,
				'remaining' => $remaining,
				'percent' => $percent
			);
		}
		return array();
	}
}
