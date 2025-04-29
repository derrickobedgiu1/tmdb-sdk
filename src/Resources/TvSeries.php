<?php

declare(strict_types=1);

namespace Astrotomic\Tmdb\Resources;

use Astrotomic\Tmdb\Requests\TvSeries\GetDetailsRequest;
use Saloon\Http\BaseResource;

class TvSeries extends BaseResource
{
    public function getDetails(int $id, ?string $append_to_response = null): \Astrotomic\Tmdb\Data\TvSeries
    {
        return $this->connector->send(
            new GetDetailsRequest($id, $append_to_response)
        )->dto();
    }
}
