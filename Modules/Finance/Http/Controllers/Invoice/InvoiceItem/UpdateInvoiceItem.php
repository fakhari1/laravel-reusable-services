<?php

namespace Modules\Finance\Http\Controllers\Invoice\InvoiceItem;

use Modules\Shared\Http\Controllers\BaseCrudHandler;

class UpdateInvoiceItem extends BaseCrudHandler
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
