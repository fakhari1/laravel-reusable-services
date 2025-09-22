<?php

namespace Modules\Finance\Http\Controllers\Invoice\Invoice;

use Modules\Shared\Http\Controllers\BaseCrudHandler;

class GetInvoice extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {

    }

    public function authorize()
    {
        return true;
    }
}
