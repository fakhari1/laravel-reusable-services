<?php

namespace Modules\Modules\Shared\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Modules\Shared\Services\Responder;
use Modules\Tenancy\Models\Tenant;
use function Modules\Shared\Http\Controllers\auth;
use function Modules\Shared\Http\Controllers\request;
use function Modules\Shared\Http\Controllers\setPermissionsTeamId;

/**
 *
 */
abstract class BaseCrudHandler extends Controller
{
    public Tenant|null $tenant = null;
    public FormRequest|Request $request;

    protected bool $shouldPaginate = false;
    protected int $defaultPerPage = 15;
    protected int $maxPerPage = 30;


    protected function isFormRequest(Request $request): bool
    {
        return $request instanceof FormRequest;
    }

    public function __invoke()
    {
        $this->request = request();
        $attributes = $this->request->route()->parameters();
        $attributes = array_merge($attributes, $this->request->all());

        $this->beforeExecution($attributes);

        if (method_exists(self::class, 'validate') && !empty($this->validate())) {
            $validatedData = $this->handleValidation();

            if (!$validatedData) {
                throw ValidationException::withMessages([]);
            }
        }

        $result = $this->handle($attributes);

        $this->afterExecution($result, $attributes);

        return $result;
    }

    public function handle(array $attributes = [])
    {
        DB::beginTransaction();

        try {
            $data = $this->execute($attributes);

            DB::commit();

            return $data;
        } catch (\Exception $exception) {
            DB::rollBack();

            return Responder::error($exception->getMessage(), $exception->getTrace());
        }
    }

    public function execute(array $attributes = [])
    {

    }

    protected function beforeExecution(array $attributes = [])
    {
        if (isset($attributes['tenant_id']))
            setPermissionsTeamId($attributes['tenant_id']);
        else if (auth('api-tenant')->check())
            setPermissionsTeamId(auth('api-tenant')->user()->tenant?->id);
        $this->setCurrentTenant();
        $this->determinePagination();
    }

    protected function getValidatedData(FormRequest|Request $request): array
    {
        if ($this->isFormRequest($request)) {
            return $request->validated();
        }

        return $request->all();
    }

    protected function afterExecution($result, array $attributes = []): void
    {
    }

    protected function setCurrentTenant()
    {
        if (!is_null($this->request->tenant_id)) {
            $this->tenant = Tenant::where('id', $this->request->tenant_id)->firstOrFail();
            return;
        }

        $this->tenant = Tenant::where('id', $this->request->headers->get('X-Current-Tenant-ID'))->firstOrFail();

        if (is_null($this->tenant)) {
            return Responder::error('Tenant is null');
        }

        return $this->tenant;
    }

    public function validate()
    {
        return [];
    }

    public function handleValidation()
    {
        if (count($this->validate()) > 0)
            return $this->request->validate($this->validate());

        return [];
    }

    protected function determinePagination()
    {
        $this->shouldPaginate = $this->request->has('page') ||
            $this->request->has('per_page') ||
            $this->isPaginationForced();
    }

    protected function isPaginationForced()
    {
        return false;
    }

    protected function getPaginationParams()
    {
        $perPage = (int)$this->request->get('per_page', $this->defaultPerPage);
        $page = (int)$this->request->get('page', 1);

        $perPage = min($perPage, $this->maxPerPage);
        $perPage = max($perPage, 1);

        return [
            'per_page' => $perPage,
            'page' => $page
        ];
    }

    protected function shouldPaginate()
    {
        if ($this->request->has('no_pagination') && $this->request->get('no_pagination') == 'true') {
            return false;
        }

        return $this->shouldPaginate;
    }

    protected function applySorting($query, array $allowedSortFields = [])
    {
        $sortBy = $this->request->get('sort_by', 'id');
        $sortDirection = $this->request->get('sort_direction', 'desc');

        $sortDirection = in_array(strtolower($sortDirection), ['asc', 'desc']) ? strtolower($sortDirection) : 'desc';

        if (!empty($allowedSortFields) && !in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'id';
        }

        return $query->orderBy($sortBy, $sortDirection);
    }


}
