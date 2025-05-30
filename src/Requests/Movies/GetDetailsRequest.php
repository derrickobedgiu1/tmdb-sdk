<?php

declare(strict_types=1);

namespace Astrotomic\Tmdb\Requests\Movies;

use Astrotomic\Tmdb\Data\Movie;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Request\CreatesDtoFromResponse;

/**
 * @link https://developer.themoviedb.org/reference/movie-details
 */
class GetDetailsRequest extends Request
{
    use CreatesDtoFromResponse;

    protected Method $method = Method::GET;

    public function __construct(public readonly int $id, public readonly ?string $append_to_response = null) {}

    public function resolveEndpoint(): string
    {
        return "/movie/{$this->id}";
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'append_to_response' => $this->append_to_response,
        ]);
    }

    public function createDtoFromResponse(Response $response): Movie
    {
        return Movie::fromArray($response->json());
    }
}
