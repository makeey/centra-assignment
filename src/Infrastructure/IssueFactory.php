<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\Infrastructure;

use KanbanBoard\Entities\Issue;
use KanbanBoard\Entities\IssueState;
use KanbanBoard\Entities\Progress;
use KanbanBoard\Infrastructure\Interfaces\IssueFactory as IssueFactoryInterface;
use Michelf\MarkdownInterface;

class IssueFactory implements IssueFactoryInterface
{
    /** @var MarkdownInterface  */
    private $markdown;

    /** @var array  */
    private $pauseLabels;

    public function __construct(MarkdownInterface $markdown, array $pauseLabels)
    {
        $this->pauseLabels = $pauseLabels;
        $this->markdown = $markdown;
    }

    public function issue(array $data): Issue
    {
        return new Issue(
            $data['id'],
            $data['number'],
            $data['title'],
            $this->transformToMarkdown($data['body']),
            $data['html_url'],
            $this->resolveState($data),
            $this->calculateProgress(
                \substr_count(\strtolower($data['body']), '[x]'),
                \substr_count(\strtolower($data['body']), '[ ]')
            ),
            $this->resolvePause($data),
            $data['closed_at'],
            $this->resolveAssignee($data),
            \array_key_exists('pull_request', $data) ? $data['pull_request'] : []
            );
    }

    private function transformToMarkdown(string $body): string
    {
        return $this->markdown->transform($body);
    }

    private function resolveState(array $data): string
    {
        return $data['state'] === 'closed' ? IssueState::COMPLETED :
            ((\array_key_exists('assignee', $data) && null !== $data['assignee']) ? IssueState::ACTIVE :
                IssueState::QUEUED);
    }

    private function calculateProgress(int $completed, int $remaining): Progress
    {
        return new Progress($completed, $remaining);
    }

    private function resolvePause(array $data): array
    {
        foreach ($data['labels'] as $label) {
            if (\in_array($label['name'], $this->pauseLabels, true)) {
                return [$label['name']];
            }
        }

        return [];
    }

    private function resolveAssignee(array $data): ?string
    {
        return (\array_key_exists('assignee', $data) && null !== $data['assignee']) ? $data['assignee']['avatar_url'] . '?s=16' : null;
    }
}
