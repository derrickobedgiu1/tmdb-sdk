<?php

declare(strict_types=1);

namespace Astrotomic\Tmdb\Data;

use Astrotomic\Tmdb\Collections\CompanyCollection;
use Astrotomic\Tmdb\Collections\CountryCollection;
use Astrotomic\Tmdb\Collections\GenreCollection;
use Astrotomic\Tmdb\Collections\LanguageCollection;
use Astrotomic\Tmdb\Collections\PersonCreditCollection;
use Astrotomic\Tmdb\Collections\VideoCollection;
use Astrotomic\Tmdb\Enums\MovieStatus;
use Astrotomic\Tmdb\Images\Backdrop;
use Astrotomic\Tmdb\Images\Poster;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;

readonly class Movie
{
    final public function __construct(
        public bool $adult,
        public ?string $backdropPath,
        public ?Collection $collection,
        public ?int $budget,
        public GenreCollection $genres,
        public ?string $homepage,
        public int $id,
        public ?string $imdbId,
        public string $originalLanguage,
        public string $originalTitle,
        public ?string $overview,
        public ?float $popularity,
        public ?string $posterPath,
        public CompanyCollection $productionCompanies,
        public CountryCollection $productionCountries,
        public ?CarbonImmutable $releaseDate,
        public ?int $revenue,
        public ?CarbonInterval    $runtime,
        public LanguageCollection $spokenLanguages,
        public ?MovieStatus       $status,
        public ?string            $tagline,
        public string             $title,
        public ?bool              $video,
        public ?float             $voteAverage,
        public ?int               $voteCount,
        public ?VideoCollection   $videos,
        public ?ExternalIds       $externalIds,
        public ?AlternativeTitle  $alternativeTitles,
        public ?array $credits,
        public array $originCountries,
    ) {}

    public static function fromArray(array $data): self
    {
        $credits = [];

        if(isset($data['credits'])){
            $credits['cast'] = empty($data['credits']['cast']) ? null : PersonCreditCollection::fromArray($data['credits']['cast']);
            $credits['crew'] = empty($data['credits']['crew']) ? null : PersonCreditCollection::fromArray($data['credits']['crew']);
        }

        return new static(
            adult: $data['adult'],
            backdropPath: $data['backdrop_path'],
            collection: empty($data['belongs_to_collection']) ? null : Collection::fromArray($data['belongs_to_collection']),
            budget: $data['budget'] ?? null,
            genres: GenreCollection::fromArray($data['genres'] ?? null),
            homepage: $data['homepage'] ?? null,
            id: $data['id'],
            imdbId: $data['imdb_id'] ?? null,
            originalLanguage: $data['original_language'],
            originalTitle: $data['original_title'],
            overview: $data['overview'],
            popularity: $data['popularity'] ?? null,
            posterPath: $data['poster_path'],
            productionCompanies: CompanyCollection::fromArray($data['production_companies'] ?? null),
            productionCountries: CountryCollection::fromArray($data['production_countries'] ?? null),
            releaseDate: empty($data['release_date']) ? null : CarbonImmutable::parse($data['release_date']),
            revenue: $data['revenue'] ?? null,
            runtime: empty($data['runtime']) ? null : CarbonInterval::minutes($data['runtime'])->cascade(),
            spokenLanguages: LanguageCollection::fromArray($data['spoken_languages'] ?? null),
            status: empty($data['status']) ? null : MovieStatus::from($data['status']),
            tagline: $data['tagline'] ?? null,
            title: $data['title'],
            video: $data['video'] ?? null,
            voteAverage: $data['vote_average'] ?? null,
            voteCount: $data['vote_count'] ?? null,
            videos: VideoCollection::fromArray($data['videos']['results'] ?? null),
            externalIds: empty($data['external_ids']) ? null : ExternalIds::fromArray($data['external_ids']),
            alternativeTitles: empty($data['alternative_titles']) ? null : AlternativeTitle::fromArray($data['alternative_titles']),
            credits: empty($credits) ? null : $credits,
            originCountries: (array)($data['origin_country'] ?? []),
        );
    }

    public function poster(): Poster
    {
        return new Poster(
            $this->posterPath,
            $this->title
        );
    }

    public function backdrop(): Backdrop
    {
        return new Backdrop(
            $this->backdropPath,
            $this->title
        );
    }
}
