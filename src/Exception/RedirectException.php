<?php

namespace App\Exception;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

class RedirectException extends Exception
{
    /**
     * @var RedirectResponse
     */
    private $redirectResponse;

    public function __construct(
        RedirectResponse $redirectResponse,
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->redirectResponse = $redirectResponse;
        parent::__construct($message, $code, $previous);
    }

    public function getRedirectResponse()
    {
        return $this->redirectResponse;
    }
}