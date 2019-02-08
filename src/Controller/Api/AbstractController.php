<?php

namespace App\Controller\Api;

use App\Model\Response\AbstractResponse;
use App\Model\Response\InvalidResponse;
use App\Model\Response\SimpleResponse;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends Controller
{
    /** @var  Serializer */
    protected $serializer;

    protected $serializationContext;

    protected $deserializationContext;

    /** @var string */
    protected $view;

    /** @var string */
    protected $format;

    /*  @var string */
    protected $apiVersion;

    /*  @var array */
    protected $responseData = ['success' => true];

    /* @var array */
    protected $formats = ["json", "xml"];

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        // Получение из строки запроса версии API
        $request = $container->get('request_stack')->getCurrentRequest();

        $pathParts        = preg_split('/\//', $request->getPathInfo(), -1, PREG_SPLIT_NO_EMPTY);
        $this->apiVersion = substr($pathParts[1], 1) . '.0';
        $format           = $request->get("_format");

        $this->deserializationContext = new DeserializationContext();
        $this->deserializationContext
            ->setVersion($this->apiVersion)
            ->setGroups(['api'])
        ;

        if (!$format || !in_array($format, $this->formats)) {
            $format = $this->formats[0];
        }

        $this->serializer = $this->get('jms_serializer');
        $this->view       = View::create()->setFormat($format)->setStatusCode(200);

        $this->serializationContext = new Context();
        $this->serializationContext->setVersion($this->apiVersion);
        $this->serializationContext->setGroups(['api']);
        $this->view->setContext($this->serializationContext);
    }

    /**
     * @param  AbstractResponse $response   (default: null)
     * @param  int              $statusCode (default: 200)
     *
     * @return Response
     */
    protected function getSuccessfulResponse(AbstractResponse $response = null, $statusCode = 200)
    {
        if (!$response) {
            $response = new SimpleResponse();
        }

        $this->view->setStatusCode($statusCode)->setData($response);

        return $this->get('fos_rest.view_handler')->handle($this->view);
    }

    /**
     * @param  string $message
     *
     * @return Response
     */
    protected function getNotFoundResponse($message = 'Not found')
    {
        return $this->getInvalidResponse($message, null, 404);
    }

    /**
     * @param  string $message
     * @param  array  $errors     (default: null)
     * @param  int    $statusCode (default: 400)
     *
     * @return Response
     */
    protected function getInvalidResponse($message, $errors = null, $statusCode = 400)
    {
        $response = new InvalidResponse($message, $errors);
        $this->view->setStatusCode($statusCode)->setData($response);

        return $this->get('fos_rest.view_handler')->handle($this->view);
    }
}