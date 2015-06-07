<?php namespace App\Haku\Commands;

use Illuminate\Console\Command;
use App;

class DeleteIndexes extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:haku-delete-indexes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all indexes in ES';

    /**
     * ElasticSearch client
     *
     * @var ElasticSearch\Client
     */
    protected $client;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->client = App::make('elasticsearch');
        if ($this->confirm('This will delete all indexes in the system. Continue? [yes|no]')) {
            $this->client->indices()->delete(['index' => '_all']);
        }
    }
}
