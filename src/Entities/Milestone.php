<?php

namespace KanbanBoard\Entities;


class Milestone implements \JsonSerializable
{

    /** @var string */
    private $title;

    /** @var string */
    private $url;

    /** @var Progress */
    private $progress;

    /** @var int */
    private $number;

    /** @var Issue[] */
    private $issues = [];

    /** @var Issue[] */
    private $queued = null;

    /** @var Issue[] */
    private $active = null;

    /** @var Issue[] */
    private $completed = null;

    public function __construct(
        int $number,
        string $title,
        string $url,
        Progress $progress,
        Issue ...$issues
    )
    {
        $this->title = $title;
        $this->url = $url;
        $this->progress = $progress;
        $this->number = $number;
        $this->issues = $issues;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function progress(): Progress
    {
        return $this->progress;
    }

    public function number(): int
    {
        return $this->number;
    }

    public function jsonSerialize(): array
    {
        return [
            'milestone' => $this->title,
            'url' => $this->url,
            'progress' => $this->progress->jsonSerialize(),
            'queued' => $this->prepareIssuesToSerialize(...$this->queuedIssues()),
            'active' => $this->prepareIssuesToSerialize(...$this->activeIssues()),
            'completed' => $this->prepareIssuesToSerialize(...$this->completedIssues())
        ];
    }

    private function prepareIssuesToSerialize(Issue ...$issues): array
    {
        return array_map(static function (Issue $issue) {
            return $issue->jsonSerialize();
        }, $issues);
    }

    public function queuedIssues(): array
    {
        if (null === $this->queued) {
            $this->queued = array_filter($this->issues, static function (Issue $issue): bool {
                return $issue->state() === IssueState::QUEUED;
            });
        }
        return $this->queued;
    }

    public function activeIssues(): array
    {
        if (null === $this->active) {
            $this->active = array_filter($this->issues, static function (Issue $issue): bool {
                return $issue->state() === IssueState::ACTIVE;
            });
            usort($this->active, function (Issue $a, Issue $b): int {
                return count($a->paused()) <=> count($b->paused()) ?: $a->title() <=> $b->title();
            });
        }
        return $this->active;
    }

    public function completedIssues(): array
    {
        if (null === $this->completed) {
            $this->completed = array_filter($this->issues, static function (Issue $issue): bool {
                return $issue->state() === IssueState::COMPLETED;
            });
        }
        return $this->completed;
    }

    public function withIssues(Issue ...$issues): self
    {
        $this->issues = $issues;
        return $this;
    }
}