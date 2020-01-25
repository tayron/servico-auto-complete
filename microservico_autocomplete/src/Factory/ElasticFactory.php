<?php

namespace Application\Factory;
use Elasticsearch\ClientBuilder;

class ElasticFactory
{
	private $index;
	private $method;
	private $type;
	private $id;
	private $data;
	private $client;
	//default data size of elasticsearch
	private $size = 10;


	public function __construct($index){
		$this->index = $index;
		$this->client = $client = ClientBuilder::create()
        ->setHosts(["172.22.0.3:9200"])
        ->setRetries(0)
        ->build();

		return $this;
	}	

	
	//create a index into my_index/my_type/<autogenerated_id>
	public function create()
	{
		$params['index'] = $this->index;
		$params['type'] = $this->type;
		$params['body'] = $this->data;
		
		return $this->client->index($params);
	}

	//update a index into my_index/my_type/my_id
	public function update()
	{
		$params['index'] = $this->index;
		$params['type'] = $this->type;
		$params['body']['doc'] = $this->data;
		$params['id'] = $this->id;

		return $this->client->index($params);
	}

	//create a query
	public function find($query)
	{	
		$params = [
			'size' => $this->size,
			'index' => $this->index,
			'body'  => [
				'query' => $query
			]
		];
		
		return $this->client->search($params);
	}

	public function setIndex($index)
	{
		$this->index = $index;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function setSize($size)
	{
		$this->size = $size;
	}

	public function getIndex()
	{
		return $this->index;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getSize()
	{
		return $this->size;
	}
}