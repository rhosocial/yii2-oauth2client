Rho Social OAuth 2 Client for Yii 2 Web Applications.
=====================================================
Rho Social OAuth 2 Client for Yii 2 Web Applications.
This extension is currently under development. Please do not download, fork or
use this extension if you didn't obtained the test invitation, and we temprarily
do not accept the pull requests.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist rhosocial/yii2-oauth2client "*"
```

or add

```
"rhosocial/yii2-oauth2client": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Attach the Auth Action to Controller:

```php
class SiteController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'rhosocial\Oauth2Client\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ]
    }
    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();
        // user login or signup comes here
    }
}
```

Attach the following code to the `Component` setion of main configuration:

```php
'authClientCollection' => [
    'class' => 'yii\authclient\Collection',
    'clients' => [
        'rho_social' => [
            'class' => 'rhosocial\Oauth2client\Oauth2client',
            'clientId' => <client id>,
            'clientSecret' => <client secret>,
        ],
    ],
],
```

Please replace the `<client id>` and `<client secret>` to those assigned for you.

Then use the AuthChoice Widget in your login view, insert the following code 
to that you want:

```php
<?= yii\authclient\widgets\AuthChoice::widget([
    'baseAuthUrl' => ['site/auth'],
    'popupMode' => true,
]);?>
```

If you didn't choose the 'site/auth' as your Auth Action, please modify the
'baseAuthUrl' to the actual route.