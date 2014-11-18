<?php namespace App\Core\Commands;
use DB;
use App\Core\Models\Role;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class IndexExistingBusinessCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:es-index-business';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Indexing business data to ElasticSearch';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

    public function deleteIndex()
    {
        $client = new \Elasticsearch\Client();
        $deleteParams['index'] = 'businesses';
        $client->indices()->delete($deleteParams);
    }

    public function createIndex()
    {
        $client = new \Elasticsearch\Client();
        $indexParams['index']  = 'businesses';    //index
        $indexParams['body']['settings']['analysis']['analyzer'] = [
            'varaa_ngrams' => [
                'type' => 'custom',
                'tokenizer' => 'varaa_tokenizer',
                'filter' => ['lowercase']
            ]
        ];
        //Index 'Hakunamatata' => 'Hak','aku','una' .etc
        $indexParams['body']['settings']['analysis']['tokenizer'] = [
            'varaa_tokenizer' => [
                'type'        => 'nGram',
                'min_gram'    => '4',
                'max_gram'    => '8',
                'token_chars' => ['letter', 'digit']
            ]
        ];
        // Index Mapping
        $business = [
            '_source' => [
                'enabled' => true
            ],
            'properties' => array(
                'business_name' => [
                    'type' => 'string',
                    'analyzer' => 'varaa_ngrams'
                ],
                'category_name' => [
                    'type' => 'string',
                    'analyzer' => 'varaa_ngrams'
                ],
                'keywords' => [
                    'type' => 'string',
                    'analyzer' => 'varaa_ngrams'
                ],
                'postcode' => [
                    'type' => 'string',
                    'analyzer' => 'varaa_ngrams'
                ],
                'city' => [
                    'type' => 'string',
                    'analyzer' => 'varaa_ngrams'
                ],
                'country' => [
                    'type' => 'string',
                    'analyzer' => 'varaa_ngrams'
                ],
                'phone' => [
                    'type' => 'string',
                    'analyzer' => 'varaa_ngrams'
                ],
                'description' => [
                    'type' => 'string',
                    'analyzer' => 'varaa_ngrams'
                ],
                'location' => [
                    "type"          =>"geo_point",
                    "geohash"       => true,
                    "geohash_prefix"=> true
                ]
            )
        ];
        $indexParams['body']['mappings']['business'] = $business;
        $client->indices()->create($indexParams);
    }

    public function index()
    {
        $client = new \Elasticsearch\Client();

        $params['index'] = 'businesses';
        $params['type']  = 'business';

        // get all business users
        $users = Role::find(1)->users()->get();
        foreach ($users as $user) {
            $categories = DB::table('business_categories')
                ->select(DB::raw("GROUP_CONCAT(varaa_business_categories.name SEPARATOR ', ') as names"),
                    DB::raw("GROUP_CONCAT(varaa_business_categories.keywords SEPARATOR ', ') as keywords"))
                ->join('business_category_user','business_category_user.business_category_id','=','business_categories.id')
                ->where('business_category_user.user_id','=', $user->id)->first();

            $params['body'][] = [
                'index' => [
                    '_id' => $user->id
                ]
            ];

            $params['body'][] = [
                // 'business_name' => $user->business->name ?: '',
                'business_name' => $user->business->name,// filter exists only works with null value, so let it be null
                'category_name' => str_replace('_', ' ', $categories->names),
                'keywords'      => str_replace('_', ' ', $categories->keywords),
                'address'       => $user->business->address ?: '',
                'postcode'      => $user->business->postcode ?: '',
                'city'          => $user->business->city ?: '',
                'country'       => $user->business->country ?: '',
                'phone'         => $user->business->phone ?: '',
                'description'   => $user->business->description ?: '',
                'location'      => [
                    'lat' => $user->business->lat ?: 0,
                    'lon' => $user->business->lng ?: 0
                ]
            ];
        }

        $responses = $client->bulk($params);
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $action = $this->argument('action');
        switch ($action) {
            case 'create':
                $this->createIndex();
                break;
            case 'delete':
                $this->deleteIndex();
                break;
            case 'index':
                $this->index();
                break;
            default:
                $this->index();
                break;
        }
	}

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('action', InputArgument::OPTIONAL, 'Action create, delete, index'),
        );
    }

}
