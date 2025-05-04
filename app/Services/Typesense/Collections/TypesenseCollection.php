<?php

namespace App\Services\Typesense\Collections;

use App\Services\Typesense\Data\TypesenseField;
use App\Services\Typesense\Documents\TypesenseDocument;
use App\Services\Typesense\TypesenseClient;
use Illuminate\Support\LazyCollection;

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
     * Retrieve the typesense schema.
     *
     * @return list<TypesenseField>
     */
    abstract public function schema(): array;

    /**
     * Create collection on the typesense server
     * Override this method if you need to customize the collection creation process.
     */
    public function create(): void
    {
        $this->client()->collections->create([
            'name' => $this->name(),
            'fields' => $this->schema(),
        ]);
    }

    /**
     * Retrieve the collection name.
     */
    public function drop(): void
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
            throw new \RuntimeException(
                sprintf('Collection "%s" does not exist on the typesense server.', $this->name())
            );
        }
    }

    /**
     * Upsert the documents to the typesense server.
     *
     * @param  LazyCollection<int,T>  $documents
     */
    public function import(LazyCollection $documents): void
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
     */
    public function getDetails(): array
    {
        $this->ensureCollectionExists();
        $collections = $this->client()->getCollections();

        return $collections[$this->name()]->retrieve();
    }

    /**
     * Return the typesense client.
     */
    public static function client(): TypesenseClient
    {
        return resolve(TypesenseClient::class);
    }
}
