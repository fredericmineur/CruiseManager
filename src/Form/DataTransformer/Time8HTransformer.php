<?php


namespace App\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;

class Time8HTransformer implements DataTransformerInterface
{


    public function transform($value)
    {
        // TODO: Implement transform() method.
    }


    public function reverseTransform($time)
    {
        // TODO: Implement reverseTransform() method.
        $time->add(new \DateInterval('PT8H'));
        return $time;
    }
}