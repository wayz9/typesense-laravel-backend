<?php

namespace App\Services\Typesense\Data;

use App\Services\Typesense\Enums\FieldType;
use App\Services\Typesense\Enums\Locale;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string,string|bool>
 */
final readonly class TypesenseField implements Arrayable
{
    public function __construct(
        public string $name,
        public FieldType $type,
        public bool $optional = false,
        public bool $index = true,
        public bool $facet = false,
        public bool $sort = false,
        public bool $stem = false,
        public Locale $locale = Locale::EN,
        /** @var list<string> */
        public array $symbolsToIndex = [],
    ) {}

    /**
     * Convert the field to an array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type->value,
            'optional' => $this->optional,
            'index' => $this->index,
            'facet' => $this->facet,
            'stem' => $this->stem,
            'sort' => $this->sort,
            'locale' => $this->locale->value,
            'symbols_to_index' => $this->symbolsToIndex,
        ];
    }

    /**
     * Static soft-deleted field.
     */
    public static function softDeleted(): self
    {
        return new self(
            name: 'deleted_at',
            type: FieldType::INT32,
            optional: true,
            index: false,
            facet: false,
            sort: false,
        );
    }
}
