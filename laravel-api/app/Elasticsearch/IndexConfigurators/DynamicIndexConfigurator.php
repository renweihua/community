<?php

namespace App\Elasticsearch\IndexConfigurators;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class DynamicIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $name = 'dynamic_index_test';

    // You can specify any settings you want, for example, analyzers.
    protected $settings = [
        'number_of_shards' => 5,
        'number_of_replicas' => 0,
        'max_result_window' => 100000000
    ];

    protected $defaultMapping = [
        'properties' => [
            'dynamic_id' => [
                'type' => 'integer',
            ],
            'dynamic_title' => [
                'type' => 'text',
                'analyzer' => 'ik_max_word',
                'search_analyzer' => 'ik_smart',
            ],
            'dynamic_content' => [
                'type' => 'text',
                'analyzer' => 'ik_max_word',
                'search_analyzer' => 'ik_smart'
            ],
            'created_time' => [
                'type' => 'integer'
            ]
        ],
    ];
}
