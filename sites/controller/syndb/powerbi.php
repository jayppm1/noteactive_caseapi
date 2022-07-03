<?php  
class Controllersyndbpowerbi extends Controller {
	
	public function index() {
		
		
		$provider = new \League\OAuth2\Client\Provider\GenericProvider([
			'clientId'                => '77aa4f72-2454-4622-939a-10547e59d9b8',
			'clientSecret'            => 'eODsMFLX2660FkICmOCpd90w3Xauv+Ed9e1P1Fh0zUU=',
			'urlAuthorize'            => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
			'urlAccessToken'          => 'https://login.windows.net/<tenant-id>/oauth2/token',
			'urlResourceOwnerDetails' => '',
			'scopes'                  => 'openid',
		]);

		try {
			// Try to get an access token using the resource owner password credentials grant.
			$accessToken = $provider->getAccessToken('password', [
				'username' => '<Azure-Username>',
				'password' => '<Azure-Password>',
				'resource' => 'https://analysis.windows.net/powerbi/api'
			]);

			$token = $accessToken->getToken();
		} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
			// Failed to get the access token
			exit($e->getMessage());
		}
		 
	}
	
}