<?php

namespace AppModules\Authorization\Services\Interfaces;

interface AuthorizationServiceInterface
{
    public function authorize(): bool;
}
