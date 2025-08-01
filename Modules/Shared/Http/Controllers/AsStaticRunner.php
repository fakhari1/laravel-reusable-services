<?php

namespace Modules\Modules\Shared\Http\Controllers;

trait AsStaticRunner
{
    public static function run(array $attributes = [])
    {
        return (new self())->execute($attributes);
    }
}
