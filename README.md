# Google

Inspired by https://github.com/contributte/facebook

---

[![Build Status](https://img.shields.io/travis/martenb/google.svg?style=flat-square)](https://travis-ci.org/martenb/google)
[![Code coverage](https://img.shields.io/coveralls/martenb/google.svg?style=flat-square)](https://coveralls.io/r/martenb/google)
[![Licence](https://img.shields.io/packagist/l/martenb/google.svg?style=flat-square)](https://packagist.org/packages/martenb/google)
[![Downloads this Month](https://img.shields.io/packagist/dm/martenb/google.svg?style=flat-square)](https://packagist.org/packages/martenb/google)
[![Downloads total](https://img.shields.io/packagist/dt/martenb/google.svg?style=flat-square)](https://packagist.org/packages/martenb/google)
[![Latest stable](https://img.shields.io/packagist/v/martenb/google.svg?style=flat-square)](https://packagist.org/packages/martenb/google)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

## Content

- [Requirements - what do you need](#requirements)
- [Installation - how to register an extension](#installation)
- [Usage - how to use it](#usage)

## Requirements

You need to create a Google project and supply these parameters:

* **clientId**
* **clientSecret**

## Installation

```yaml
extensions:
    google: MartenB\Google\DI\Nette\GoogleExtension
    
google:
    clientId: %yourClientId%
    clientSecret: %yourClientSecret%
```

## Usage

Simple example how to use Google Login in Presenter

```php
namespace App\Presenters;

use Google_Service_Oauth2;
use InvalidArgumentException;
use MartenB\Google\GoogleLogin;
use Nette\Application\Responses\RedirectResponse;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;

final class SignPresenter extends Presenter
{

    /** @var GoogleLogin @inject */
    public $googleLogin;


    public function actionGoogle()
    {
        // Redirect to Google and ask customer to grant access to his account
        $url = $this->googleLogin->getLoginUrl($this->link('//googleAuthorize'), [Google_Service_Oauth2::USERINFO_PROFILE, Google_Service_Oauth2::USERINFO_EMAIL]);
        $this->sendResponse(new RedirectResponse($url));
    }


    /**
     * Log in user with accessToken obtained after redirected from Google
     *
     * @param $code
     * @return void
     */
    public function actionGoogleAuthorize($code)
    {
        // Fetch User data from Google and try to login
        try {
            $token = $this->googleLogin->getAccessToken($this->link('//googleAuthorize'), $code);

            $this->user->login('google', $this->googleLogin->getMe($token));
            $this->flashMessage('Login successful :-).', 'success');

        } catch (InvalidArgumentException | AuthenticationException $e) {
            $this->flashMessage('Login failed. :-( Try again.', 'danger');

        }
    }

}
```
