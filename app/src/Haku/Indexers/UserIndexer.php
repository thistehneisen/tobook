<?php namespace App\Haku\Indexers;

use App\Core\Models\User;
use InvalidArgumentException;

class UserIndexer extends AbstractIndexer
{
    const INDEX_NAME = 'users';
    const INDEX_TYPE = 'user';

    public function getIndexName()
    {
        return self::INDEX_NAME;
    }

    public function getType()
    {
        return self::INDEX_TYPE;
    }

    public function getId()
    {
        return $this->getDocument()->id;
    }

    public function getBody()
    {
        $document = $this->getDocument();
        $name = '';
        if ($document->business !== null) {
            $name = $document->business->name;
        } elseif ($document->consumer !== null) {
            $name = $document->consumer->name;
        }

        return [
            'email' => $document->email,
            'name' => $name
        ];
    }

    public static function getMapping()
    {
        return [
            'name'  => ['type' => 'string'],
            'email' => ['type' => 'string'],
        ];
    }

    public function setDocument($document)
    {
        if (!($document instanceof User)) {
            throw new InvalidArgumentException('Data must be an instance of App\Core\Models\User');
        }

        parent::setDocument($document);
    }
}
