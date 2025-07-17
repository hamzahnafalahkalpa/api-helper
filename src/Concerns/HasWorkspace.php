<?php

namespace Hanafalah\ApiHelper\Concerns;

trait HasWorkspace
{
  /** @var string */
  protected static $__workspace_id;

  protected function hasModel()
  {
    $validation = $this->hasHeader('WorkspaceId');
    if ($validation) {
      $this->setWorkspaceId();
      switch ($this->getAppCode()) {
        default:
          $this->setModel($this->WorkspaceModel()->find(static::$__workspace_id));
          break;
      }
    }
    return $validation;
  }

  /**
   * Get the workspace ID that is currently in use in the request.
   *
   * @return string|null The workspace ID that is currently in use in the request.
   */
  public function getWorkspaceId()
  {
    return static::$__workspace_id;
  }

  /**
   * Set the workspace ID to use in the current request.
   * 
   * @param string|null $workspaceId Workspace ID to use. If null, it will be read from the `WorkspaceId` header.
   * @return self
   */
  public function setWorkspaceId(?string $workspaceId = null): self
  {
    self::$__workspace_id = $workspaceId ?? $this->getHeader('WorkspaceId');
    return $this;
  }
}
