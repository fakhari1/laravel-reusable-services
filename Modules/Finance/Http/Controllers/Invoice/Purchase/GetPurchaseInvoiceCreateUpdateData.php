<?php

namespace Modules\Finance\Http\Controllers\Invoice\Purchase;

use Modules\Shared\Http\Controllers\BaseCrudHandler;

class GetPurchaseInvoiceCreateUpdateData extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {

    }

    public function authorize()
    {
        return true;
    }
}
