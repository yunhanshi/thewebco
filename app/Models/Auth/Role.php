<?php
namespace App\Models\Auth;

use App\Models\Auth\Acl;
use Spatie\Permission\Models\Permission;

/**
 * Class Role
 *
 * @property \App\Models\Auth\Permission[] $permissions
 * @property string $name
 * @package App\Models
 */
class Role extends \Spatie\Permission\Models\Role
{
    public $guard_name = 'api';

    /**
     * Check whether current role is admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->name === Acl::ROLE_ADMIN;
    }
}
