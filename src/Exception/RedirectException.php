<?php

namespace App\Exception;

use Symfony\Component\Config\Definition\Exception\Exception;
use Throwable;

class RedirectException extends Exception
{
    /**
     * @var String
     */
    private $redirectResponse;

    private $redirectMessage;

    public function __construct(
        string $redirectResponse,
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->redirectResponse = $redirectResponse;
        $this->redirectMessage = $message;
        parent::__construct($message, $code, $previous);
    }

    public function getRedirectResponse()
    {
        return $this->redirectResponse;
    }

    public function getRedirectMessage(){
        return $this->redirectMessage;
    }


}