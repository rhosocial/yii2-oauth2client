<?php

/*
 *  _   __ __ _____ _____ ___  ____  _____
 * | | / // // ___//_  _//   ||  __||_   _|
 * | |/ // /(__  )  / / / /| || |     | |
 * |___//_//____/  /_/ /_/ |_||_|     |_|
 * @link http://vistart.name/
 * @copyright Copyright (c) 2015 vistart
 * @license http://vistart.name/license/
 */

namespace rhosocial\Oauth2client;

use Yii;
use yii\authclient\OAuth2;
/**
 * Rho Social OAuth 2 Client for Yii 2 Applications.
 * Now we have suuported the following API:
 * @property string $userId Get the User's ID uniquely in your client.
 * 
 * The format of response data is JSON string. It includes the following 
 * members:
 * success: Whether the just request succeeds;
 * data: The wanted contents if request succeeded, or the error messages if not.
 * Please see the detail of each API note.
 * @author vistart <i@vistart.name>
 */
class Oauth2client extends OAuth2 
{
    public $authUrl = 'https://rho.social/authorize';
    public $tokenUrl = 'https://api.rho.social/v1/token';
    public $apiBaseUrl = 'https://api.rho.social/v1';
    /**
     * Composes default [[returnUrl]] value.
     * @return string return URL.
     */
    protected function defaultReturnUrl()
    {
        $params = $_GET;
        unset($params['code']);
        unset($params['state']);
        $params[0] = Yii::$app->controller->route;
        if (strpos(Yii::$app->urlManager->hostInfo, 'localhost') !== false){
            Yii::$app->urlManager->hostInfo = str_replace('localhost', '127.0.0.1', Yii::$app->urlManager->hostInfo);
        }
        $url = Yii::$app->getUrlManager()->createAbsoluteUrl($params);
        return $url;
    }
    
    protected function defaultName()
    {
        return 'rho_social';
    }

    protected function defaultTitle()
    {
        return 'ρ social';
    }
    
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 800,
            'popupHeight' => 500,
        ];
    }

    /**
     * Fetches access token from authorization code.
     * @param string $authCode authorization code, usually comes at $_GET['code'].
     * @param array $params additional request params.
     * @return OAuthToken access token.
     */
    public function fetchAccessToken($authCode, array $params = [])
    {
        $defaultParams = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $authCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getReturnUrl(),
        ];
        $response = $this->sendRequest('POST', $this->tokenUrl, array_merge($defaultParams, $params));
        $token = $this->createToken(['params' => $response['data']]);
        $this->setAccessToken($token);

        return $token;
    }
    
    /**
     * Get the User's ID uniquely in your client.
     * This request only receive the POST method.
     * the succeeded request will get the response content that only includes
     * the User's ID.
     * 
     * @return string User's ID.
     */
    public function getUserId()
    {
        $data = $this->initUserAttributes();
        return $data['user_id'];
    }
    
    /**
     * The api method will automatically attach the access_token property.
     * The job you need to do is providing your own parameter(s).
     * @return type
     */
    protected function initUserAttributes()
    {
        return $this->api('user', 'POST', ['client_id' => $this->clientId])['data'];
    }
}
