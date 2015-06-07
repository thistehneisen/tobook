<?php namespace App\Haku\Indexers;

interface IndexerInterface
{
    /**
     * Name of index that this document will be stored
     *
     * @return string
     */
    public function getIndexName();

    /**
     * Name of document type
     *
     * @return string
     */
    public function getIndexType();

    /**
     * Document ID
     *
     * @return mixed
     */
    public function getId();

    /**
     * Return array of data that will be stored
     *
     * @return array
     */
    public function getBody();

    /**
     * Schema mapping for this type of document
     *
     * @return array
     */
    public function getMapping();

    /**
     * The the source document that data should be extracted from.
     * Usually it should be an Eloquent model
     *
     * @param mixed $document
     */
    public function setDocument($document);

    /**
     * Send data to ES to be indexed
     *
     * @return array Result data from ES
     */
    public function index();

    /**
     * Delete an indexed document in ES
     *
     * @return array Result data from ES
     */
    public function delete();
}
