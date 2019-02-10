<?php

namespace App\Controller\Api;

use App\Form\ConvertType;
use App\Model\ConvertorRequest;
use App\Model\ConvertorResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertorController extends AbstractController
{
    /**
     * Convert currency
     *
     * @param Request $request
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
    public function convertAction(Request $request)
    {
        $convertorRequest = new ConvertorRequest();
        $convertForm      = $this->formFactory->createNamed('', ConvertType::class, $convertorRequest);
        $convertForm->handleRequest($request);

        if($convertForm->isSubmitted() && !$convertForm->isValid()) {
            return $this->getInvalidResponse('Errors in the input parameters', $convertForm->getErrors(true));
        }

        $errors = $this->validator->validate($convertorRequest);
        if (count($errors) > 0) {
            return $this->getInvalidResponse('Errors in the input parameters', $errors);
        }

        try {
            $value = $this->convertor->convert($convertorRequest);
        } catch (\Exception $e) {
            return $this->getInvalidResponse('Errors in runtime', [$e->getMessage()]);
        }
        return $this->getSuccessfulResponse(new ConvertorResponse($value));
    }
}