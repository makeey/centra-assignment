<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\Entities;

class Issue implements \JsonSerializable
{
    /** @var int */
    private $id;

    /** @var int */
    private $number;

    /** @var string */
    private $title;

    /** @var string */
    private $body;

    /** @var string */
    private $url;

    /** @var string|null */
    private $assignee = null;

    /** @var array */
    private $paused = null;

    /** @var string|null */
    private $closed;

    /** @var string */
    private $state;

    /** @var Progress */
    private $progress;

    /** @var array */
    private $pullRequest = [];

    public function __construct(
        int $id,
        int $number,
        string $title,
        string $body,
        string $url,
        string $state,
        Progress $progress,
        array $paused,
        ?string $closed = null,
        ?string $assignee = null,
        ?array $pullRequest = null
    ) {
        $this->id = $id;
        $this->number = $number;
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
        $this->assignee = $assignee;
        $this->paused = $paused;
        $this->progress = $progress;
        $this->closed = $closed;
        $this->state = $state;
        $this->pullRequest = null !== $pullRequest ? $pullRequest : [];
    }

    public function id(): int
    {
        return $this->id;
    }

    public function number(): int
    {
        return $this->number;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function assignee(): ?string
    {
        return $this->assignee;
    }

    public function progress(): Progress
    {
        return $this->progress;
    }

    public function closed(): ?string
    {
        return $this->closed;
    }

    public function paused(): array
    {
        return $this->paused;
    }

    public function isPaused(): bool
    {
        return (bool) \count($this->paused);
    }

    public function state(): string
    {
        return $this->state;
    }

    public function isHasPullRequest(): bool
    {
        return (bool) \count($this->pullRequest);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
            'assignee' => $this->assignee,
            'paused' => $this->paused,
            'progress' => $this->progress->jsonSerialize(),
            'closed' => $this->closed,
            'state' => $this->state,
        ];
    }
}
