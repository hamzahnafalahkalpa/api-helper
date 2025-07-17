<?php

namespace Hanafalah\ApiHelper\Contracts\Supports;

interface BaseApiAccess
{
    public function __construct();
    public function getToken(): ?string;
    public function encrypting(mixed $data): bool|string;
    public function getApiAccess(): mixed;
    public function getWorkspaceId();
    public function setWorkspaceId(?string $workspaceId = null): self;
    public function setAppCode(?string $app_code = null): self;
    public function setApiAccessByAppCode(): self;
    public function forToken(): self;
    public function forAuthenticate(): self;
    public function isForToken(): bool;
    public function isForAuthenticate(): bool;
    public function getReason(): string;
    public function getJti(): ?string;
    public function setExpirationToken(mixed $expiration): self;
    public function encryption();
    public function authorizing(): object;
    public function authorizationConfig(): array;
    public function setAuthorizing(?string $class = null): self;
    public function getUser(): ?object;
    public function setUser(mixed $model = null): self;
    public function user(mixed $conditionals): ?object;
}
