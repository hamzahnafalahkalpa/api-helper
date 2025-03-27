<?php

namespace Hanafalah\ApiHelper\Facades;

use Hanafalah\ApiHelper\Contracts\ModuleApiAccess as ContractApiAccess;
use Illuminate\Support\Facades\Facade;

/**
 * @method static self init()
 * @method static self generateToken(): self
 * @method static \Illuminate\Http\JsonResponse response($callback)
 * @method static string getAppKey()
 * @method static int|string getTimestamp()
 * @method static self setTimestamp($timestamp)
 * @method static ?string getAppCode()
 * @method static mixed getApiAccess()
 * @method static self setAppCode(?string $app_code=null)
 * @method static mixed setApiAccess($api_access)
 * @method static string injector()
 * @method static self timeValidator()
 * @method static string getReqUsername()
 * @method static string getReqSecret()
 * @method static string getReqPassword()
 * @method static mixed chooseAlgorithm(string $alg)
 * @method static self setAlgorithm(string $algorithm)
 * @method static bool algorithmExists()
 * @method static self setApiAccessByAppCode()
 * @method static self setApiAccessByUsername()
 * @method static self setApiAccessByToken()
 * @method static self authorizing()
 * @method static mixed decrypting()
 * @method static string encrypting(string $data)
 * @method static self setEncryption($class)
 * @method static string getEncryption()
 * @method static self forToken()
 * @method static int expiration(? int $custom = null)
 * @method static self generateToken()
 * @method static string authorizing()
 * @method static ?string getToken()
 * @method static mixed getDecodedResult()
 * @method static self accessOnLogin(callable $callback)
 */
class ApiAccess extends Facade
{
   protected static function getFacadeAccessor()
   {
      return ContractApiAccess::class;
   }
}
