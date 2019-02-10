<?php

namespace App\Controller\Api;

use App\CurrencyConvertor\Convertor;
use App\Model\Response\AbstractResponse;
use App\Model\Response\InvalidResponse;
use App\Model\Response\SimpleResponse;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use JMS\Serializer\DeserializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SfAbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractController extends SfAbstractController
{
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

    /** @var ViewHandler */
    protected $viewHandler;

    /** @var FormFactoryInterface */
    protected $formFactory;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var Convertor */
    protected $convertor;

    /** @var RequestStack */
    protected $requestStack;

    public function __construct(
        ViewHandler $viewHandler,
        FormFactoryInterface $formFactory,
        ValidatorInterface $validator,
        RequestStack $requestStack,
        Convertor $convertor
    ) {
        $this->viewHandler = $viewHandler;
        $this->formFactory = $formFactory;
        $this->validator   = $validator;
        $this->convertor   = $convertor;

        // Получение из строки запроса версии API
        $request = $requestStack->getCurrentRequest();

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

        $this->view = View::create()->setFormat($format)->setStatusCode(200);

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
    protected function getSuccessfulResponse(AbstractResponse $response = null, int $statusCode = 200)
    {
        if (!$response) {
            $response = new SimpleResponse();
        }

        $this->view->setStatusCode($statusCode)->setData($response);

        return $this->viewHandler->handle($this->view);
    }

    /**
     * @param  string $message
     *
     * @return Response
     */
    protected function getNotFoundResponse(string $message = 'Not found')
    {
        return $this->getInvalidResponse($message, null, 404);
    }

    /**
     * @param  string $message
     * @param         $errors     (default: null)
     * @param  int    $statusCode (default: 400)
     *
     * @return Response
     */
    protected function getInvalidResponse(string $message, $errors = null, int $statusCode = 400)
    {
        $response = new InvalidResponse($message, $errors);
        $this->view->setStatusCode($statusCode)->setData($response);

        return $this->viewHandler->handle($this->view);
    }
}