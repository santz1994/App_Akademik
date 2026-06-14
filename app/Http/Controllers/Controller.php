<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Controller
{
    protected function resolvePerPage(Request $request, int $default = 10): string
    {
        $value = strtolower((string) $request->query('per_page', (string) $default));

        if (in_array($value, ['10', '50', 'all'], true)) {
            return $value;
        }

        return (string) $default;
    }

    protected function paginateWithPerPage(Builder $query, Request $request, int $default = 10): LengthAwarePaginator
    {
        $selected = $this->resolvePerPage($request, $default);
        $perPage = $selected === 'all'
            ? max((clone $query)->toBase()->getCountForPagination(), 1)
            : (int) $selected;

        return $query->paginate($perPage)->withQueryString();
    }
}
