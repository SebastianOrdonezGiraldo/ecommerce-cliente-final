<?php

namespace App\Services\ContaPyme;

use RuntimeException;

class ContaPymeException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly ?string $contaPymeCode = null,
    ) {
        parent::__construct($message);
    }
}
