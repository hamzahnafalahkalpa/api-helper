<?php

namespace Hanafalah\ApiHelper\Models;

use Illuminate\Support\Facades\Hash;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelSupport\Models\SupportBaseModel;

class ApiAccess extends SupportBaseModel
{
  use SoftDeletes, HasProps;

  protected $table = "api_accesses";

  protected $fillable = [
    'id','app_code','reference_id','reference_type','token','props'
  ];

  /**
   * Set the password attribute using bcrypt
   *
   * @param string $value
   * @return void
   */
  public function setPasswordAttribute($value)
  {
    $this->attributes['password'] = Hash::make($value);
  }

  //LOCAL SCOPE
  /**
   * Find ApiAccess model by token
   *
   * @param Builder $builder
   * @param string $token
   * @return Builder
   */
  public function scopeFindToken($builder, $token)
  {
    return $builder->where('token', $token);
  }

  /**
   * Find ApiAccess model by app_code
   *
   * @param Builder $builder
   * @param string $app_code
   * @return Builder
   */
  public function scopeFindAppCode($builder, $app_code)
  {
    return $builder->where('app_code', $app_code);
  }

  /**
   * Find ApiAccess model by username
   *
   * @param Builder $builder
   * @param string $username
   * @return Builder
   */
  public function scopeFindUsername($builder, $username)
  {
    return $builder->where('username', $username);
  }

  //EIGER SECTION
  public function reference()
  {
    return $this->morphTo();
  }
  //END EIGER SECTION
}
