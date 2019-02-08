<?php

namespace App\Model\Response;

use JMS\Serializer\Annotation as Serializer;

class InvalidResponse extends AbstractResponse
{
    /**
     * Error message
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"api"})
     * @Serializer\SerializedName("errorMsg")
     * @Serializer\Type("string")
     */
    public $errorMsg;

    /**
     * Errors
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"api"})
     * @Serializer\Type("array")
     */
    public $errors;

    public function __construct($message, $errors = null)
    {
        $this->success  = false;
        $this->errorMsg = $message;

        if (!empty($errors)) {
            $this->errors = $errors;
        }
    }
}
