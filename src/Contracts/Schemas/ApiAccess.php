<<<<<<< HEAD
<?php

namespace Hanafalah\ApiHelper\Contracts\Schemas;

use Hanafalah\ApiHelper\Data\ApiAccessData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ApiHelper\Schemas\ApiAccess
 * @method self conditionals(mixed $conditionals)
 * @method bool deleteApiAccess()
 * @method bool prepareDeleteApiAccess(? array $attributes = null)
 * @method mixed getApiAccess()
 * @method ?Model prepareShowApiAccess(?Model $model = null, ?array $attributes = null)
 * @method array showApiAccess(?Model $model = null)
 * @method Collection prepareViewApiAccessList()
 * @method array viewApiAccessList()
 * @method LengthAwarePaginator prepareViewApiAccessPaginate(PaginateData $paginate_dto)
 * @method array viewApiAccessPaginate(?PaginateData $paginate_dto = null)
 * @method array storeApiAccess(?ApiAccessData $api_access_dto = null)
 * @method Builder apiAccess(mixed $conditionals = null)
 */
interface ApiAccess extends DataManagement {
    public function prepareStoreApiAccess(ApiAccessData $api_access_dto): Model;
}
=======
<?php

namespace Hanafalah\ApiHelper\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;

interface ApiAccess extends DataManagement {}
>>>>>>> 1e5343cfa591a87d2740d4c80c820b8ab7b420cf
