<?php
/**
 * Created by PhpStorm.
 * User: filipw
 * Date: 21-01-20
 * Time: 4:35 PM
 */

namespace App\Form\DataTransformer;

use App\Entity\Investigators;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;


/**
 * Class ReceiverToNumberTransformer, responsible for converting a receiver id to a Receiver (Entity) and v.v.
 * This solution is chosen to prevent that the receiver field on an Deployment form must be defined as an
 * EntityType. An EntityType has as disadvantage that it loads all entities from the database, something
 * we are not interested in because auto-complete functionality (and ajax-calls behind) is used (so no need
 * to load all data).
 *
 * @package VLIZ\OtnAdminBundle\Form\DataTransformer
 */
class InvestigatorToNumberTransformer implements DataTransformerInterface
{

    private $entityManager;


    /**
     * InvestigatorToNumberTransformer constructor.
     *
     * @param EntityManagerInterface = $entityManager;
     * @param string  $entityName As the transformer supports both acoustic telemetry receivers AND cpod receivers, the actual entity name should be passed in
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * @param Investigators $investigator The value in the original representation
     * @return integer | null The value in the transformed representation
     * @throws TransformationFailedException When the transformation fails.
     */
    public function transform($investigator)
    {
        // TODO can this be optional?
        if ($investigator === null) {
            return '';
        }
        return $investigator->getInvestigatorid();
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * @param integer $investigatorIdPk The value in the transformed representation
     * @return null|object instance of Investigator (or null if the system does not find the receiver)
     * @throws TransformationFailedException When the transformation fails.
     */
    public function reverseTransform($investigatorIdPk)
    {
        // no issue number? It's optional, so that's ok
        if (!$investigatorIdPk) {
            return;
        }

        $investigator = $this->entityManager
            ->getRepository(Investigators::class)
            // query for the issue with this id
            ->find($investigatorIdPk);

        if (null === $investigator) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $investigatorIdPk
            ));
        }

        return $investigator;

    }

}