<?php

namespace App\Console\Commands;

use Elasticsearch\ClientBuilder;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class EsInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init es';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 废弃！！！无法创建模版

        $client = new Client();
        //创建模板
        $url = config('scout.elasticsearch.hosts')[0].'/_template/template_1';
        $param = [
            'json' => [
                'template' => config('scout.elasticsearch.index'),
                'mappings' => [
                    '_default_' => [
                        'dynamic_templates' => [
                            [
                                'strings' => [
                                    'match_mapping_type' => 'string',
                                    'mapping' => [
                                        'type' => 'text',
                                        'analyzer' => 'ik_max_word',
                                        'fields' => [
                                            'keyword' => [
                                                'type' => 'keyword',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $client->put($url, $param);

        $this->info('========= create template success ========');

        //创建索引
        $url = config('scout.elasticsearch.hosts')[0].'/'.config('scout.elasticsearch.index');
        $param = [
            'json' => [
                'settings' => [
                    'refresh_interval' => '5s',
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    '_default_' => [
                        '_all' => [
                            'enabled' => false,
                        ],
                    ],
                ],
            ],
        ];
        $client->put($url, $param);

        $this->info('=========== create index success ==========');
    }
}
