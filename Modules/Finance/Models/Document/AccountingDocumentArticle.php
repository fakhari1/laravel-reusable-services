<?php

namespace Modules\Finance\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Central\Models\Traits\HasTenant;
use Modules\Finance\Models\Document\Traits\Article\ArticleConstants;
use Modules\Finance\Models\Document\Traits\Article\ArticleGetters;
use Modules\Finance\Models\Document\Traits\Article\ArticleRelations;
use Modules\Finance\Models\Document\Traits\Article\ArticleSetters;

class AccountingDocumentArticle extends Model
{
    use SoftDeletes, HasTenant, ArticleGetters, ArticleSetters, ArticleRelations, ArticleConstants;

    protected $fillable = [
        'tenant_id',
        'document_id',
        'detailed_account_id',
        'debit_amount',
        'credit_amount',
        'description',
        'deleted_at'
    ];
}
