<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use function Symfony\Component\Translation\t;

class FrenchToDateTimeTransformer implements \Symfony\Component\Form\DataTransformerInterface
{

    /**
     * @inheritDoc
     */
    public function transform($date)
    {
        if ($date == null){
            return '';
        }
        // TODO: Implement transform() method.
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($frenchDate)
    {
        if($frenchDate === null){
            // Exc
            throw new TransformationFailedException('Vous devez fournir un date');
        }
        $date =\DateTime::createFromFormat('d/m/Y', $frenchDate);
        if ($date == false){
            // Execption
            throw new TransformationFailedException('transformation failed');
        }

        // TODO: Implement reverseTransform() method.

        return $date;
    }
}