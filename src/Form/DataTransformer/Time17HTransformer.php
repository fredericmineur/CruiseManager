<?php


namespace App\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class Time17HTransformer implements DataTransformerInterface
{


    public function transform($value)
    {
        // TODO: Implement transform() method.
    }


    public function reverseTransform($time)
    {
        // TODO: Implement reverseTransform() method.
        $time->add(new \DateInterval('PT17H'));
        return $time;
    }
}