<?php

use MartenB\Google\GoogleLogin;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

// Test if loginUrl is string
test(function (): void {
	$googleClient = new Google_Client([
		'clientId' => '...',
		'clientSecret' => '...',
	]);
	$googleLogin = new GoogleLogin($googleClient);

	Assert::type('string', $googleLogin->getLoginUrl('https://...'));
});
