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

use yii\base\NotSupportedException;
use yii\web\Response;

/**
 * This AuthAction performs authentication via Rho Social OAuth 2.0 Authenticator.
 *
 * Usage:
 *
 * Attach the Auth Action to Controller:
 * ~~~
 * class SiteController extends Controller
 * {
 *     public function actions()
 *     {
 *         return [
 *             'auth' => [
 *                 'class' => 'rhosocial\Oauth2Client\AuthAction',
 *                 'successCallback' => [$this, 'onAuthSuccess'],
 *             ],
 *         ]
 *     }
 *
 *     public function onAuthSuccess($client)
 *     {
 *         $attributes = $client->getUserAttributes();
 *         // user login or signup comes here
 *     }
 * }
 * ~~~
 *
 * Usually authentication via external services is performed inside the popup window.
 * This action handles the redirection and closing of popup window correctly.
 *
 * @see Collection
 * @see \yii\authclient\widgets\AuthChoice
 *
 * @author vistart <i@vistart.name>
 */
class AuthAction extends \yii\authclient\AuthAction 
{
    /**
     * @param mixed $client auth client instance.
     * @return Response response instance.
     * @throws \yii\base\NotSupportedException on invalid client.
     */
    protected function auth($client)
    {
        if ($client instanceof Oauth2client){
            return $this->authOAuth2($client);
        } else {
            throw new NotSupportedException('Provider "' . get_class($client) . '" is not supported.');
        }
    }
}
