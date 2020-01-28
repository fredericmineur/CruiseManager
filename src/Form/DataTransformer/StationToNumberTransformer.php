<?php


namespace App\Form\DataTransformer;


use App\Entity\Stations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StationToNumberTransformer implements DataTransformerInterface
{

    private $entityManager;

    /**
     * StationToNumberTransformer constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * Transforms a value from the original representation to a transformed representation.
     * @param mixed $value The value in the original representation
     * @return mixed The value in the transformed representatio
     * @throws TransformationFailedException when the transformation fails
     */
    public function transform($station)
    {
        if (null === $station ) {
            return '';
        }
        return $station->getNr();
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     * @param mixed $value The value in the transformed representation
     * @return mixed The value in the original representation
     * @throws TransformationFailedException when the transformation fails
     */
    public function reverseTransform($stationNumber)
    {
        if (!$stationNumber) {
            return;
        }

        $station = $this->entityManager
            ->getRepository(Stations::class)
            ->find($stationNumber);

        if (null === $station){
            throw new TransformationFailedException(sprintf(
                'A Station with number "%s" does not exist!', $stationNumber
            ));
        }

        return $station;
    }
}