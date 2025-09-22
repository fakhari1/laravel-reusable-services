<?php

namespace Modules\Warehouse\Http\Controllers\Api\Rack;

use Modules\Shared\Http\Controllers\BaseCrudHandler;

class GetRackCreateUpdateData extends BaseCrudHandler
{
    public function authorize()
    {
        return true;
    }
}
