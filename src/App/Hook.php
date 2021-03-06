<?php
namespace App;

use Helper\Mail;

class Hook
{

  public static function main(){

    $rawPost = NULL;
    if ($_ENV['SECRET'] !== NULL) {
    	if (!isset($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
    		throw new \Exception("HTTP header 'X-Hub-Signature' is missing.");
    	} elseif (!extension_loaded('hash')) {
    		throw new \Exception("Missing 'hash' extension to check the secret code validity.");
    	}

    	list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + array('', '');
    	if (!in_array($algo, hash_algos(), TRUE)) {
    		throw new \Exception("Hash algorithm '$algo' is not supported.");
    	}

    	$rawPost = file_get_contents('php://input');
    	if ($hash !== hash_hmac($algo, $rawPost, $_ENV['SECRET'])) {
    		throw new \Exception('Hook secret does not match.');
    	}
    };

    if (!isset($_SERVER['CONTENT_TYPE'])) {
    	throw new \Exception("Missing HTTP 'Content-Type' header.");
    } elseif (!isset($_SERVER['HTTP_X_GITHUB_EVENT'])) {
    	throw new \Exception("Missing HTTP 'X-Github-Event' header.");
    }

    switch ($_SERVER['CONTENT_TYPE']) {
    	case 'application/json':
    		$json = $rawPost ?: file_get_contents('php://input');
    		break;

    	case 'application/x-www-form-urlencoded':
    		$json = $_POST['payload'];
    		break;

    	default:
    		throw new \Exception("Unsupported content type: $_SERVER[CONTENT_TYPE]");
    }

    $payload = json_decode($json);
    $config  = json_decode(file_get_contents($_ENV['CONFIG']), true);

    switch (strtolower($_SERVER['HTTP_X_GITHUB_EVENT'])) {
    	case 'ping':
    		echo 'pong';
    		break;

    	case 'push':
        $result = shell_exec($config[$payload->repository->name]);
        echo "Event:$_SERVER[HTTP_X_GITHUB_EVENT] Payload:\n";
        Mail::send($payload->repository->name, "Event : $_SERVER[HTTP_X_GITHUB_EVENT] Result :", $result);
    		break;

    	default:
    		header('HTTP/1.0 404 Not Found');
    		echo "Event:$_SERVER[HTTP_X_GITHUB_EVENT] Payload:\n";
    		die();
    }

  }


}
