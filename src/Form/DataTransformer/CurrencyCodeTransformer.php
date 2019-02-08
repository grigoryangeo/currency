<?php

namespace App\Form\DataTransformer;

use App\CurrencyConvertor\Convertor;
use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CurrencyCodeTransformer implements DataTransformerInterface
{
    protected $em;

    /** @var Convertor */
    private $convertor;

    public function __construct(EntityManagerInterface $em, Convertor $convertor)
    {
        $this->em        = $em;
        $this->convertor = $convertor;
    }

    /**
     * @param null|Currency $entity
     *
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @return null|string
     */
    public function transform($entity):?string
    {
        if (null === $entity) {
            return null;
        }

        return $entity->getCode();
    }

    /**
     * @param null|string $code
     *
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @return mixed|Currency
     */
    public function reverseTransform($code):?Currency
    {
        if (!$code) {
            return null;
        }

        $currency = $this->em->getRepository(Currency::class)
            ->getOneByCode($code, $this->convertor->getCurrencySource())
        ;

        if (!$currency || !$currency->getValue()) {
            throw new TransformationFailedException(
                sprintf(
                    'An currency with number "%s" does not exist',
                    $code
                )
            );
        }

        return $currency;
    }
}
