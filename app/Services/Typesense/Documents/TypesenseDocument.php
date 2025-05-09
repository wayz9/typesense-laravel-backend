<?php

declare(strict_types=1);

namespace App\Services\Typesense\Documents;

use App\Services\Typesense\Data\TypesenseField;

abstract class TypesenseDocument
{
    /**
     * Default locale
     */
    public static string $locale = 'en';

    /**
     * Retrieve the typesense collection schema.
     *
     * @return list<TypesenseField>
     */
    abstract public static function schema(): array;

    /**
     * Convert the Laravel model to a typesense document.
     *
     * @return array<string,mixed>
     */
    abstract public function toDocument(): array;
}
