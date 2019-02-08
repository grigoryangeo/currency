<?php

namespace App\Model\Response;

use JMS\Serializer\Annotation as Serializer;

abstract class AbstractResponse
{
    /**
     * Request result
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"api"})
     * @Serializer\Type("boolean")
     */
    public $success = true;
}