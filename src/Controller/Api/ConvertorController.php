<?php

namespace App\Controller\Api;

use App\CurrencyConvertor\Convertor;
use App\Form\ConvertType;
use App\Model\ConvertorRequest;
use App\Model\ConvertorResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConvertorController extends AbstractController
{
    /**
     * Convert currency
     *
     * @param Request              $request
     * @param FormFactoryInterface $formFactory
     * @param ValidatorInterface   $validator
     * @param Convertor            $convertor
     *
     * @return Response
     *
     * @SWG\Parameter(
     *     name="from",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Code currency from"
     * )
     * @SWG\Parameter(
     *     name="to",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Code currency to"
     * )
     * @SWG\Parameter(
     *     name="value",
     *     in="query",
     *     type="number",
     *     required=true,
     *     description="Value to convert"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Result",
     *     @Model(type=ConvertorResponse::class, groups={"api"})
     * )
     * @SWG\Tag(name="Currency")
     */
    public function convertAction(
        Request $request,
        FormFactoryInterface $formFactory,
        ValidatorInterface $validator,
        Convertor $convertor
    ) {
        $convertorRequest = new ConvertorRequest();
        $convertForm      = $formFactory->createNamed('', ConvertType::class, $convertorRequest);
        $convertForm->handleRequest($request);

        if ($convertForm->isSubmitted() && $convertForm->isValid()) {
            $errors = $validator->validate($convertorRequest);
            if (count($errors) > 0) {
                return $this->getInvalidResponse('Errors in the input parameters', (string) $errors);
            }

            $value = $convertor->convert($convertorRequest);
            return $this->getSuccessfulResponse(new ConvertorResponse($value));
        } else {
            return $this->getInvalidResponse('Errors in the input parameters', $convertForm->getErrors(true));
        }
    }
}