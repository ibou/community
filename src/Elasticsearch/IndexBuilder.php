<?php

namespace App\Elasticsearch;

use Elastica\Client;
use Symfony\Component\Yaml\Yaml;

class IndexBuilder
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create()
    {
        // We name our index "community"
        $index = $this->client->getIndex('community');

        $settings = Yaml::parse(
            file_get_contents(
                __DIR__.'/../../config/elasticsearch_index_community.yaml'
            )
        );

        // We build our index settings and mapping
        $index->create($settings, true);

        return $index;
    }
}
