<?php namespace App\Haku\Indexers;

use App;
use Log;
use Exception;

abstract class AbstractIndexer implements IndexerInterface
{
    protected $document;
    protected $client;

    public function __construct($document)
    {
        $this->setDocument($document);
        $this->client = App::make('elasticsearch');
    }

    public function getId()
    {
        return $this->document->id;
    }

    public function setDocument($document)
    {
        $this->document = $document;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function index()
    {
        $params = [
            'id' => $this->getId(),
            'body' => $this->getBody(),
            'type' => $this->getType(),
            'index' => $this->getIndexName(),
        ];

        try {
            Log::info('Indexing data', [
                'id' => $this->getId(),
                'endpoint' => $this->getIndexName().'/'.$this->getType(),
            ]);

            return $this->client->index($params);
        } catch (Exception $ex) {
            Log::error($ex->getMessage(), $params);
        }
    }

    public function updateSingleField($field, $value)
    {
         $params = [
            'id' => $this->getId(),
            'body' => [
                'doc' => [
                    $field => $value
                ]
            ],
            'type' => $this->getType(),
            'index' => $this->getIndexName(),
        ];

        try {
            Log::info('Update index', [
                'id' => $this->getId(),
                'endpoint' => $this->getIndexName().'/'.$this->getType() . '/' . $this->getId(),
            ]);

            return $this->client->update($params);
        } catch (Exception $ex) {
            Log::error($ex->getMessage(), $params);
        }
    }

    public function delete()
    {
        $params = [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'index' => $this->getIndexName(),
        ];

        try {
            return $this->client->delete($params);
        } catch (Exception $ex) {
            // Because delete action is idempotent, so silently failed
            $params['message'] = $ex->getMessage();
            Log::info('Cannot delete search index', $params);
        }
    }
}
