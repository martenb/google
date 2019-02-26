# Google

Inspired by https://github.com/contributte/facebook

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

        } catch (AuthenticationException $e) {
            $this->flashMessage('Login failed. :-( Try again.', 'danger');

        }
    }

}
```
