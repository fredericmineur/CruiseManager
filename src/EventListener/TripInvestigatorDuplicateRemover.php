<?php


namespace App\EventListener;


use App\Entity\Cruise;
use App\Entity\Trip;

use App\Entity\Tripinvestigators;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

class TripInvestigatorDuplicateRemover
{
    /**
     * @ORM\PreFlush()
     */
    public function preUpdate(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if ($entity instanceof Cruise) {
            $entityManager = $args->getObjectManager();
            $trips = $entity->getTrips();
            foreach ($trips as $trip) {
                //IF THERE ARE DUPLICATES...Create a UNIQUE_ARRAY (remove the duplicates)
                //https://groups.google.com/forum/#!topic/doctrine-user/M_azUnv9VlU


                $tripinvestigators = $trip->getTripinvestigators();

//https://stackoverflow.com/questions/3820258/cannot-use-for-reading
                //https://www.javatpoint.com/java-program-to-remove-duplicate-element-in-an-array

                $collectionUniqueInvestigators = new ArrayCollection();
                $flagDuplicates = false;

                foreach ($tripinvestigators as $tripinvestigator) {
                    $criteria = new Criteria();
                    $criteria->andWhere($criteria->expr()->eq('surname', $tripinvestigator->getSurname()))
                        ->andWhere($criteria->expr()->eq('firstname', $tripinvestigator->getFirstname()));
                    $found = $tripinvestigators->matching($criteria);
                    if (count($found) == 1) {
                        $collectionUniqueInvestigators->add($tripinvestigator);
                    } else {
                        $flagDuplicates = true;

                        if (count($collectionUniqueInvestigators->matching($criteria)) == 0) {
                            $collectionUniqueInvestigators->add($tripinvestigator);
                        };
                    }

                }
                if ($flagDuplicates) {

                    foreach ($tripinvestigators as $tripinvestigator) {
                        $trip->removeTripinvestigator($tripinvestigator);
                        $entityManager->remove($tripinvestigator);
                    }

                    foreach ($collectionUniqueInvestigators as $uniqueTripinvestigator) {
                        $trip->addTripinvestigator($uniqueTripinvestigator);
                    }
                }
                $entityManager->persist($trip);






            }
            $entityManager->persist($entity);
//            die();
        }


    }


    /**
     * @ORM\PreFlush()
     */
    public function postPersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        dump('postPersist');
        dump($entity);
//        die();
        if ($entity instanceof Cruise) {
            $entityManager = $args->getObjectManager();
            $trips = $entity->getTrips();
            foreach ($trips as $trip) {

                $tripinvestigators = $trip->getTripinvestigators();


                //https://stackoverflow.com/questions/3820258/cannot-use-for-reading
                //https://www.javatpoint.com/java-program-to-remove-duplicate-element-in-an-array

                $collectionUniqueInvestigators = new ArrayCollection();
                $flagDuplicates = false;

                foreach ($tripinvestigators as $tripinvestigator) {
                    $criteria = new Criteria();
                    $criteria->andWhere($criteria->expr()->eq('surname', $tripinvestigator->getSurname()))
                        ->andWhere($criteria->expr()->eq('firstname', $tripinvestigator->getFirstname()));
                    $found = $tripinvestigators->matching($criteria);
//                    dump($found);
                    if (count($found) == 1) {
//                        array_push($collectionUniqueInvestigators, $tripinvestigator);
                        $collectionUniqueInvestigators->add($tripinvestigator);
                    } else {
                        $flagDuplicates = true;

                        if (count($collectionUniqueInvestigators->matching($criteria)) == 0) {
                            $collectionUniqueInvestigators->add($tripinvestigator);
                        };
                    }

                }
                dump($collectionUniqueInvestigators);
                if ($flagDuplicates) {
                    $newCollectionInvestigators = new ArrayCollection();

                    foreach ($tripinvestigators as $tripinvestigator) {
                        $criteria = new Criteria();
                        $criteria->andWhere($criteria->expr()->eq('surname', $tripinvestigator->getSurname()))
                            ->andWhere($criteria->expr()->eq('firstname', $tripinvestigator->getFirstname()));
                        $newCollectionInvestigators->add($tripinvestigator);
                        $newFound = $newCollectionInvestigators->matching($criteria);
                        if (count($newFound) > 1) {
                            $trip->removeTripinvestigator($tripinvestigator);
                            $entityManager->remove($tripinvestigator);
                        }
                    }

                }
                dump('trip');
                dump($flagDuplicates);
                dump($trip);
                $entityManager->persist($trip);


            }


        }


    }

}