<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as BaseMiddleware;

class ValidateSignature extends BaseMiddleware
{
    /**
     * The names of the parameters that should be ignored.
     *
     * @var array<int, string>
     */
    protected $except = [];
}
