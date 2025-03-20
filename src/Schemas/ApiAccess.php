<?php

namespace Zahzah\ApiHelper\Schemas;

use Zahzah\ApiHelper\Contracts\SchemaApiAccess;
use Zahzah\ApiHelper\Supports\BaseApiAccess;

class ApiAccess extends BaseApiAccess implements SchemaApiAccess{
    public function booting(): self{
        static::$__class = $this;
        static::$__model = $this->{$this->__entity."Model"}();
        return $this;
}

protected array $__guard   = ['id','app_code']; 
    protected array $__add     = ['token'];
    protected string $__entity = 'ApiAccess';

    /**
     * Add a new API access or update the existing one if found.
     *
     * The given attributes will be merged with the existing API access.
     *
     * @param array $attributes The attributes to be added to the API access.
     *
     * @return \Illuminate\Database\Eloquent\Model The API access model.
     */
    public function addOrChange(? array $attributes=[]): self{    
        $this->updateOrCreate($attributes);
        return $this;
    }   
}