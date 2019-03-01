<?php

declare(strict_types=1);

namespace MartenB\Google;

use Google_Client;

class GoogleFactory
{

	/** @var string[] */
	private $config;


	/**
	 * @param string[] $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}


	/**
	 * @return Google_Client
	 */
	public function create(): Google_Client
	{
		return new Google_Client($this->config);
	}

}
