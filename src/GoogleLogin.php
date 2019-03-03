<?php declare(strict_types = 1);

namespace MartenB\Google;

use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Oauth2_Userinfoplus;
use InvalidArgumentException;

class GoogleLogin
{

	/** @var Google_Client */
	private $googleClient;

	public function __construct(Google_Client $googleClient)
	{
		$this->googleClient = $googleClient;
	}


	/**
	 * @param string|string[] $scope
	 */
	public function getLoginUrl(string $redirectUri, $scope = Google_Service_Oauth2::USERINFO_PROFILE, ?string $state = null): string
	{
		$this->googleClient->setIncludeGrantedScopes(true);
		$this->googleClient->addScope($scope);
		$this->googleClient->setRedirectUri($redirectUri);
		if (isset($state)) {
			$this->googleClient->setState($state);
		}

		return $this->googleClient->createAuthUrl();
	}


	/**
	 * @return mixed[]
	 * @throws InvalidArgumentException
	 */
	public function getAccessToken(string $redirectUri, string $authCode): array
	{
		$this->googleClient->setRedirectUri($redirectUri);

		return $this->googleClient->fetchAccessTokenWithAuthCode($authCode);
	}


	/**
	 * @param mixed[] $accessToken
	 */
	public function getMe(array $accessToken): Google_Service_Oauth2_Userinfoplus
	{
		$this->googleClient->setAccessToken($accessToken);

		return (new Google_Service_Oauth2($this->googleClient))->userinfo_v2_me->get();
	}

}
