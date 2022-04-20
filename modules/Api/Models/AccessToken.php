<?php

namespace Modules\Api\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class AccessToken
 * @package Modules\Api\Models
 */
class AccessToken extends Model
{
    use HasPermissions, HasRoles;

    /**
     * @var string
     */
    protected $table = 'access_tokens';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function permissions(): MorphToMany
    {
        return $this->morphToMany(Permission::class, 'model', 'model_has_permissions');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}
