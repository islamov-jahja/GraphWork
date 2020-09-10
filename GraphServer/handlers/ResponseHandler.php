<?php


namespace app\handlers;


use Yii;
use common\utils\configuration;
class ResponseHandler
{
    public function beforeSend ( Response $response ) : void {
        // CORS headers
        $this->setupCorsHeaders ($response);

        // Format output response
        $this->responseFormatter ($response);
    }

    /**
     * Execute event after response sent
     * @param \yii\web\Response $response Response object
     */
    public function afterSend ( Response $response ) : void {}

    /**
     * Format output response
     * @param \yii\web\Response $response Response object
     */
    private function responseFormatter ( Response $response ) : void {
        /** @var array $data */
        $data = $response->data;

        if ( !$response->isSuccessful ) {
            if ( !$response->statusCode ) {
                $response->statusCode = 400;
            }

            if ( \is_countable ($data) && \count ($data) ) {
                $response->data = [];
                unset($data['previous']);
                $response->data = $data;
            }
            return;
        }

        if ( \null !== $data ) {
            if ( \is_countable($data) && \count ($data) ) {
                $response->data = [];
                $response->data['data'] = $data;
            }
        }

        if ( !$response->statusCode ) {
            $response->statusCode = 200;
        }
    }

    /**
     * Setup CORS headers
     * @param \yii\web\Response $response Response object
     */
    private function setupCorsHeaders ( Response $response ) : void {
        /** @var \yii\web\HeaderCollection $headers */
        $headers = $response->headers;

        /** @var array $allowedHeaders */
        $allowedHeaders = Configuration::current ('security.cors.allowed.headers', ['toArray'=>\true]);

        $headers->set ('Access-Control-Allow-Origin', static::getOrigin ());
        $headers->set ('Access-Control-Allow-Credentials', 'true');
        $headers->set ('Access-Control-Max-Age', (int) Configuration::current ('security.cors.maxAge'));
        $headers->set ('Access-Control-Allow-Headers', \implode (', ', $allowedHeaders));
        $headers->set ('Access-Control-Allow-Methods', 'GET, OPTIONS, POST, DELETE, PUT, HEAD, PATCH');
    }

    /**
     * Get origin from request
     * @return string
     */
    private static function getOrigin () : string {
        /** @var \yii\web\HeaderCollection $headers */
        $headers = Yii::$app->request->headers;

        /** @var string[] $whitelistOrigins */
        $whitelistOrigins = Configuration::current ('security.cors.allowed.origins', ['toArray'=>\true]);

        /** @var string $origin */
        $origin = $headers->get ('origin')
            ?? $headers->get ('host')
            ?? \null;

        foreach ( $whitelistOrigins as $host ) {
            if ( \false !== \strpos ($origin, $host) ) {
                return $origin;
            }
        }

        return Configuration::current ('uri.baseUrl');
    }
}