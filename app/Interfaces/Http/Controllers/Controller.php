<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ResponseTrait;

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array<string, string>
     */
    protected function resourceAbilityMap(): array
    {
        return [
            'index'   => 'viewAny',
            'show'    => 'view',
            'create'  => 'create',
            'store'   => 'create',
            'edit'    => 'update',
            'update'  => 'update',
            'destroy' => 'delete',
        ];
    }
}
