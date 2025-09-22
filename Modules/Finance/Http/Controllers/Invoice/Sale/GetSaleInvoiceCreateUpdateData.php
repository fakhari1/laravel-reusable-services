<?php

namespace Modules\Finance\Http\Controllers\Invoice\Sale;

use Modules\Finance\Http\Resources\DetailedAccount\DetailedAccountResource;
use Modules\Finance\Http\Resources\Invoice\InvoiceItemable\InvoiceItemableResource;
use Modules\Finance\Models\Account\AccountingDetailedAccount;
use Modules\Finance\Models\Account\AccountingSpecificAccount;
use Modules\Finance\Models\Invoice\AccountingSaleInvoice;
use Modules\Service\Models\TenantProvidedService;
use Modules\Shared\Http\Controllers\BaseCrudHandler;
use Modules\Shared\Services\Responder;
use Modules\Warehouse\Models\Product;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/workshops/accounting/invoices/sale-invoices/get-create-update-data",
 *     operationId="getSaleInvoiceCreateUpdateData",
 *     tags={"Accounting > Invoices > SaleInvoices"},
 *     summary="Get sale invoices create update data",
 *     description="Returns sale invoices create update data for the tenant",
 *          @OA\Parameter(
 *          name="customer_account",
 *          in="query",
 *          required=false,
 *          @OA\Schema(type="string"),
 *          description="Customer's Accounts"
 *      ),
 *         @OA\Parameter(
 *           name="itemable",
 *           in="query",
 *           required=false,
 *           @OA\Schema(type="string"),
 *           description="Product and Services"
 *       ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *             )
 *         )
 *     )
 * )
 */
class GetSaleInvoiceCreateUpdateData extends BaseCrudHandler
{
    public function handle(array $attributes = [])
    {
        $latestSaleInvoices = AccountingSaleInvoice::ForTenant($this->tenant->id)->latest()->first();
        $code = $latestSaleInvoices?->code ? $latestSaleInvoices->code + 1 : 1;

        $customersSpecificAccount = AccountingSpecificAccount::ForTenant($this->tenant->id)
            ->where('slug', AccountingSpecificAccount::SLUG_CUSTOMERS)
            ->first();

        $customersDetailedAccounts = $customersSpecificAccount->detailedAccounts()->whereDoesntHave('children');

        $products = Product::ForTenant($this->tenant->id)->latest();
        $services = TenantProvidedService::ForTenant($this->tenant->id)->latest();

        if ($this->request->has('customer_account')) {
            $search = $this->request->get('customer_account');

            $customersDetailedAccounts->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        if ($this->request->has('itemable')) {
            $search = $this->request->get('itemable');

            $products->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        if ($this->request->has('itemable')) {
            $search = $this->request->get('itemable');

            $services->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            });
        }

        $products = $products->limit(20)->get();
        $services = $services->limit(20)->get();
        $invoiceItemable = $products->merge($services);

        $customersDetailedAccounts = $customersDetailedAccounts->limit(20)->get();


        return Responder::success([
            'code' => $code,
            'customer_accounts' => DetailedAccountResource::collection($customersDetailedAccounts),
            'invoice_itemables' => InvoiceItemableResource::collection($invoiceItemable)
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
