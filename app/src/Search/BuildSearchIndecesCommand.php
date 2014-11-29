<?php namespace App\Search;

use Queue;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class BuildSearchIndecesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:build-search-indices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build indices of one or many models';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $default = [
            'App\Core\Models\User',
            'App\Core\Models\Business',
            'App\FlashDeal\Models\Service',
            'App\FlashDeal\Models\FlashDeal',
        ];

        $models = $this->argument('models');
        $models = (!empty($models))
            ? explode(',', $models)
            : $default;

        foreach ($models as $model) {
            $this->info('Enqueue to rebuild index of '.$model);

            foreach ($model::all() as $item) {
                // Push into queue to reindex
                $id = $item->id;
                Queue::push(function ($job) use ($model, $id) {
                    $item = $model::find($id);
                    if ($item) {
                        $item->updateSearchIndex();
                        $job->delete();
                    }
                });
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['models', InputArgument::OPTIONAL, 'Comma-separated models'],
        ];
    }
}
