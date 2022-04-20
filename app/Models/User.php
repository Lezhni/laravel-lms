<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Modules\Learning\Models\Enrollment;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @property \Illuminate\Database\Eloquent\Collection roles
 * @property \Illuminate\Database\Eloquent\Collection permissions
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasRoles, HasPermissions, HasApiTokens, Notifiable;

    /**
     * @var string
     */
    public const IMAGES_FOLDER = 'avatars';

    /**
     * @var string[]
     */
    public const SEX_LIST = [
        'Мужской',
        'Женский',
        'Другое',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'extra_fields',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email_verified_at',
        'password',
        'remember_token',
        'extra_fields',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'avatarUrl',
        'sex',
        'country',
        'city',
        'telegram',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'email_verified_at' => 'datetime',
        'extra_fields' => 'array',
        'last_activity_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles(): MorphToMany
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('is_admin', true);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAdmins(Builder $query): Builder
    {
        return $query->where('is_admin', false);
    }

    /**
     * @return string|null
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar) { return null; }
        return Storage::disk('uploads')->url($this->avatar);
    }

    /**
     * @return string|null
     */
    public function getTelegramAttribute(): ?string
    {
        return $this->getExtraField('telegram');
    }

    /**
     * @return string|null
     */
    public function getSexAttribute(): ?string
    {
        return $this->getExtraField('sex');
    }

    /**
     * @return string|null
     */
    public function getCountryAttribute(): ?string
    {
        return $this->getExtraField('country');
    }

    /**
     * @return string|null
     */
    public function getCityAttribute(): ?string
    {
        return $this->getExtraField('city');
    }

    /**
     * @param string|null $value
     */
    public function setPhoneAttribute(?string $value)
    {
        $this->attributes['phone'] = is_string($value)
            ? PhoneNumber::make($value, 'AUTO')->formatE164()
            : null;
    }

    /**
     * @param $value
     */
    public function setSexAttribute($value)
    {
        $this->setExtraField('sex', $value);
    }

    /**
     * @param $value
     */
    public function setCountryAttribute($value)
    {
        $this->setExtraField('country', $value);
    }

    /**
     * @param $value
     */
    public function setCityAttribute($value)
    {
        $this->setExtraField('city', $value);
    }


    /**
     * @param $value
     */
    public function setTelegramAttribute($value)
    {
        $this->setExtraField('telegram', $value);
    }
    /**
     * @param string $key
     * @return string|null
     */
    protected function getExtraField(string $key): ?string
    {
        if (! $this->extra_fields) { return null; }
        return Arr::get($this->extra_fields, $key);
    }

    /**
     * @param string $key
     * @param string|integer|null $value
     */
    protected function setExtraField(string $key, $value)
    {
        $extraFields = $this->extra_fields;
        $extraFields[$key] = $value;
        $this->extra_fields = $extraFields;
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'student.'.$this->id;
    }
}
