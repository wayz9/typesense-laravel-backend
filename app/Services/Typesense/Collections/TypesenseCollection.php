<?php

declare(strict_types=1);

namespace App\Services\Typesense\Collections;

use RuntimeException;
use Illuminate\Support\LazyCollection;
use App\Services\Typesense\TypesenseClient;
use App\Services\Typesense\Documents\TypesenseDocument;

/**
 * @template T of TypesenseDocument
 */
abstract class TypesenseCollection
{
    /**
     * Retrieve the typesense collection name.
     *
     * 1. Keep collection names in snake_case.
     * 2. Use plural form for collections e.g "products".
     */
    abstract public function name(): string;

    /**
     * Return the typesense client.
     */
    final public static function client(): TypesenseClient
    {
        return resolve(TypesenseClient::class);
    }

    /**
     * Retrieve the collection name.
     */
    final public function drop(): void
    {
        $this->ensureCollectionExists();
        $this->client()->collections[$this->name()]->delete();
    }

    /**
     * Ensure the collection exists on the typesense server.
     */
    final public function ensureCollectionExists(): void
    {
        $exists = $this->client()
            ->collections[$this->name()]
            ->exists();

        if (! $exists) {
            throw new RuntimeException(
                sprintf('Collection "%s" does not exist on the typesense server.', $this->name())
            );
        }
    }

    /**
     * Upsert the documents to the typesense server.
     *
     * @param  LazyCollection<int,T>  $documents
     */
    final public function import(LazyCollection $documents): void
    {
        $this->ensureCollectionExists();
        $client = $this->client();

        $chunks = $documents->chunk(100);

        foreach ($chunks as $chunk) {
            $data = $chunk->map(fn (TypesenseDocument $doc) => $doc->toDocument());

            $client
                ->collections[$this->name()]
                ->documents
                ->import($data->toArray());
        }
    }

    /**
     * Retrieve the collection details.
     *
     * @return array<string,mixed>
     *
     * @throws RuntimeException
     */
    final public function details(): array
    {
        $this->ensureCollectionExists();
        $collections = $this->client()->getCollections();

        return $collections[$this->name()]->retrieve();
    }
}
