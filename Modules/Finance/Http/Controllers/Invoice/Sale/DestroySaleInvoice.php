<?php

namespace Modules\Finance\Http\Controllers\Invoice\Sale;

use Modules\Shared\Http\Controllers\BaseCrudHandler;

class DestroySaleInvoice extends BaseCrudHandler
{
    public function execute(array $attributes = [])
    {

    }

    public function authorize()
    {
        return true;
    }

    public function validate()
    {

    }

    public function afterValidator(array $attributes)
    {

    }
}
