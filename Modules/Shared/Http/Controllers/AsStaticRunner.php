<?php

namespace Modules\Shared\Http\Controllers;

trait AsStaticRunner
{
    public static function run(array $attributes = [])
    {
        request()->merge($attributes);

        return (new self())->startup();
    }
}
