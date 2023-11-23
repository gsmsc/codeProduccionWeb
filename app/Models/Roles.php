<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class Roles extends Model
{
    use HasPermissions;
    use RefreshesPermissionCache;
    protected $table = "roles";

    protected $fillable = [
        'id',
        'name',
        'guard_name',
        'created_at',
        'updated_at'
    ];
}
