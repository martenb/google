<?php

declare(strict_types=1);

namespace MartenB\Google\DI\Nette;

use MartenB\Google\GoogleFactory;
use MartenB\Google\GoogleLogin;
use Nette\DI\CompilerExtension;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;

class GoogleExtension extends CompilerExtension
{

	/** @var string[] */
	private $defaults = [
		'clientId' => NULL,
		'clientSecret' => NULL,
	];


	/**
	 * @throws AssertionException
	 */
	public function loadConfiguration(): void
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		Validators::assertField($config, 'clientId', 'string');
		Validators::assertField($config, 'clientSecret', 'string');

		$clientData = [
			'client_id' => $config['clientId'],
			'client_secret' => $config['clientSecret'],
		];

		$builder->addDefinition($this->prefix('googleFactory'))
			->setType(GoogleFactory::class)
			->setArguments([$clientData]);

		$builder->addDefinition($this->prefix('google'))
			->setFactory('@' . $this->prefix('googleFactory') . '::create');

		$builder->addDefinition($this->prefix('login'))
			->setType(GoogleLogin::class);
	}

}
