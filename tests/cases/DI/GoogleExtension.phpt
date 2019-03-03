<?php declare(strict_types = 1);

use MartenB\Google\DI\Nette\GoogleExtension;
use MartenB\Google\GoogleLogin;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

// Test if GoogleLogin is created
test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('google', new GoogleExtension())
			->addConfig([
				'google' => [
					'clientId' => '...',
					'clientSecret' => '...',
				],
			]);
	}, 1);
	/** @var Container $container */
	$container = new $class();

	Assert::type(GoogleLogin::class, $container->getService('google.login'));
});
