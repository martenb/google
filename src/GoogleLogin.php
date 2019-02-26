<?php

namespace MartenB\Google;

use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Oauth2_Userinfoplus;

class GoogleLogin
{

	/** @var Google_Client */
	private $googleClient;


	public function __construct(Google_Client $googleClient)
	{
		$this->googleClient = $googleClient;
	}


	/**
	 * @param string $redirectUri
	 * @param string|array $scope
	 * @return string
	 */
	public function getLoginUrl(string $redirectUri, $scope = Google_Service_Oauth2::USERINFO_PROFILE): string
	{
		$this->googleClient->setIncludeGrantedScopes(TRUE);
		$this->googleClient->addScope($scope);
		$this->googleClient->setRedirectUri($redirectUri);

		return $this->googleClient->createAuthUrl();
	}


	/**
	 * @param string $redirectUri
	 * @param string $authCode
	 * @return array
	 */
	public function getAccessToken(string $redirectUri, string $authCode): array
	{
		$this->googleClient->setRedirectUri($redirectUri);

		return $this->googleClient->fetchAccessTokenWithAuthCode($authCode);
	}


	/**
	 * @param array $accessToken
	 * @return Google_Service_Oauth2_Userinfoplus
	 */
	public function getMe(array $accessToken): Google_Service_Oauth2_Userinfoplus
	{
		$this->googleClient->setAccessToken($accessToken);

		return (new Google_Service_Oauth2($this->googleClient))->userinfo_v2_me->get();
	}

}
