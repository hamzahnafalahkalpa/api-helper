<?php

namespace Hanafalah\ApiHelper\Models;

use Illuminate\Support\Facades\Hash;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiAccess extends BaseModel
{
  use SoftDeletes, HasProps, HasUlids;

  public $incrementing = false;
  protected $table = "api_accesses";
  protected $keyType = 'string';
  protected $primaryKey = 'id';
  
  protected $fillable = [
    'id','app_code','reference_id','reference_type','token','props'
  ];

  public function viewUsingRelation(): array{
    return [];
  }

  public function showUsingRelation(): array{
    return ['reference'];
  }

  public function setPasswordAttribute($value){$this->attributes['password'] = Hash::make($value);}
  public function scopeFindToken($builder, $token){return $builder->where('token', $token);}
  public function scopeFindAppCode($builder, $app_code){return $builder->where('app_code', $app_code);}
  public function scopeFindUsername($builder, $username){return $builder->where('username', $username);}

  public function reference(){return $this->morphTo();}
}
