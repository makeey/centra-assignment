# Kanban board for Github issues

## About

This is a simple, read-only, Kanban-board for Github issues.

### Concepts and workflow

* `Queued:` are open issues, in a milestone with no one assigned
* `Active:` are any open issue, in a milestone with someone assigned
   * Active issues can, optionally, be paused by adding any of the configured "pause labels" to the issue
* `Completed:` are any issues in a milestone that is closed

#### Required environment variables

* `GH_CLIENT_ID`
* `GH_CLIENT_SECRET`
* `GH_ACCOUNT`
* `GH_REPOSITORIES`

----

_Originally a "fork" of the [Kanban Board](https://github.com/ellislab/kanban-board) plugin to [ExpressionEngine](https://ellislab.com/expressionengine) then more or less completely rewritten._
