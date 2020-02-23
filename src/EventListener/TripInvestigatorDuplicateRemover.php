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
    public function preUpdate (LifecycleEventArgs $args) {

        $entity = $args->getEntity();

        dump('preUpdate');
        dump($entity);
        if($entity instanceof Cruise) {
            $entityManager= $args->getObjectManager();
            $trips = $entity->getTrips();
            foreach ($trips as $trip) {
                //IF THERE ARE DUPLICATES...Create a UNIQUE_ARRAY (remove the duplicates)

                //https://groups.google.com/forum/#!topic/doctrine-user/M_azUnv9VlU



                $tripinvestigators = $trip->getTripinvestigators();
//
//                $n = count($tripinvestigators);
//
//                if($n<=1) {
//                    return $n;
//                }

//https://stackoverflow.com/questions/3820258/cannot-use-for-reading
                //https://www.javatpoint.com/java-program-to-remove-duplicate-element-in-an-array

                $collectionUniqueInvestigators = new ArrayCollection();
                $flagDuplicates =false;

//                $j = 0;
//                for ($i=0; $i<$n-1; $i++) {
//                    if({$tripinvestigators}[$i]){
//
//                    };
//                }

                foreach ($tripinvestigators as $tripinvestigator) {
                    $criteria = new Criteria();
                    $criteria->andWhere($criteria->expr()->eq('surname', $tripinvestigator->getSurname()))
                        ->andWhere($criteria->expr()->eq('firstname', $tripinvestigator->getFirstname()));
                    $found = $tripinvestigators->matching($criteria);
//                    dump($found);
                    if(count($found)==1){
//                        array_push($collectionUniqueInvestigators, $tripinvestigator);
                        $collectionUniqueInvestigators->add($tripinvestigator);
                    } else {
                        $flagDuplicates=true;

                        if (count($collectionUniqueInvestigators->matching($criteria))==0){
                            $collectionUniqueInvestigators->add($tripinvestigator);
                        };
                    }

                }
                dump($collectionUniqueInvestigators);
                if($flagDuplicates){

                    foreach ($tripinvestigators as $tripinvestigator) {
                        $trip->removeTripinvestigator($tripinvestigator);
                        $entityManager->remove($tripinvestigator);
                    }

                    foreach ($collectionUniqueInvestigators as $uniqueTripinvestigator) {
                        $trip->addTripinvestigator($uniqueTripinvestigator);
                    }
                }
                dump('trip');
                dump($flagDuplicates);
                dump($trip);
                $entityManager->persist($trip);






//                dump(count($tripinvestigators));
//                dump(count(array_unique($tripinvestigators, SORT_REGULAR)));





//                foreach ($tripinvestigators as $tripinvestigator) {
//                    dump($tripinvestigator);
//                    if($tripinvestigator->getTripnr()->getTripid()==null){
//                        dump('null');
//                        $results = $entityManager->getRepository(Tripinvestigators::class)
//                            ->findBy([
//                                'surname'=> $tripinvestigator->getSurname(),
//                                'firstname' => $tripinvestigator->getFirstname(),
//                                'tripnr' => $trip
//                            ], []);
//                        dump($trip);
//                    } else {
//                        dump('not null');
//                        $results = $entityManager->getRepository(Tripinvestigators::class)
//                            ->findBy([
//                                'surname'=> $tripinvestigator->getSurname(),
//                                'firstname' => $tripinvestigator->getFirstname(),
//                                'tripnr' => $tripinvestigator->getTripnr()
//                            ], []);
//                    }

//                    $results = $entityManager->getRepository(Tripinvestigators::class)
//                        ->findBy([
//                            'surname'=> $tripinvestigator->getSurname(),
//                            'firstname' => $tripinvestigator->getFirstname(),
////                            'tripnr' => $tripinvestigator->getTripnr()
//                        ], []);
//                    dump(count($results));
//                    if (count($results) > 1){
//
//                        $alreadyPresentTripinvestigator = $results[0];
//                        dump('alreadypresenttripinvestigator');
//                        dump($alreadyPresentTripinvestigator);
//                        $trip->addTripinvestigator($alreadyPresentTripinvestigator);
//
//                        $duplicatedTripinvestigator = $results[1];
//                        dump('duplicated');
//                        dump($duplicatedTripinvestigator);
//                        $trip->removeTripinvestigator($duplicatedTripinvestigator);
//                        $entityManager->remove($duplicatedTripinvestigator);
//                    }else {
//                        $trip->addTripinvestigator($tripinvestigator);
//                    }
//                }
            }
            $entityManager->persist($entity);
//            die();
        }


    }


    /**
     * @ORM\PreFlush()
     */
    public function postPersist (LifecycleEventArgs $args) {

        $entity = $args->getEntity();

        dump('postPersist');
        dump($entity);
//        die();
        if($entity instanceof Cruise) {
            $entityManager= $args->getObjectManager();
            $trips = $entity->getTrips();
            foreach ($trips as $trip) {

                $tripinvestigators = $trip->getTripinvestigators();


//https://stackoverflow.com/questions/3820258/cannot-use-for-reading
                //https://www.javatpoint.com/java-program-to-remove-duplicate-element-in-an-array

                $collectionUniqueInvestigators = new ArrayCollection();
                $flagDuplicates =false;

                foreach ($tripinvestigators as $tripinvestigator) {
                    $criteria = new Criteria();
                    $criteria->andWhere($criteria->expr()->eq('surname', $tripinvestigator->getSurname()))
                        ->andWhere($criteria->expr()->eq('firstname', $tripinvestigator->getFirstname()));
                    $found = $tripinvestigators->matching($criteria);
//                    dump($found);
                    if(count($found)==1){
//                        array_push($collectionUniqueInvestigators, $tripinvestigator);
                        $collectionUniqueInvestigators->add($tripinvestigator);
                    } else {
                        $flagDuplicates=true;

                        if (count($collectionUniqueInvestigators->matching($criteria))==0){
                            $collectionUniqueInvestigators->add($tripinvestigator);
                        };
                    }

                }
                dump($collectionUniqueInvestigators);
                if($flagDuplicates){
                    $newCollectionInvestigators = new ArrayCollection();

                    foreach ($tripinvestigators as $tripinvestigator) {
                        $criteria = new Criteria();
                        $criteria->andWhere($criteria->expr()->eq('surname', $tripinvestigator->getSurname()))
                            ->andWhere($criteria->expr()->eq('firstname', $tripinvestigator->getFirstname()));
                        $newCollectionInvestigators->add($tripinvestigator);
                        $newFound = $newCollectionInvestigators->matching($criteria);
                        if(count($newFound)>1){
                            $trip->removeTripinvestigator($tripinvestigator);
                            $entityManager->remove($tripinvestigator);
                        }
                    }

                    // ERROR "managed + dirty entity cannot be inserted"
//                    foreach ($collectionUniqueInvestigators as $uniqueTripinvestigator) {
//                        $trip->addTripinvestigator($uniqueTripinvestigator);
//                    }
                }
                dump('trip');
                dump($flagDuplicates);
                dump($trip);
                $entityManager->persist($trip);


            }
//            die();

        }


    }

//    public function prePersist (LifecycleEventArgs $args) {
//
//        $entity = $args->getEntity();
//        dump('prePersist');
//        dd($entity);
//        if($entity instanceof Cruise) {
//            $entityManager= $args->getObjectManager();
//            $trips = $entity->getTrips();
//            foreach ($trips as $trip) {
//                $tripinvestigators = $trip->getTripinvestigators();
//                foreach ($tripinvestigators as $key => $tripinvestigator) {
//                    $results = $entityManager->getRepository(Tripinvestigators::class)
//                        ->findBy([
//                            'surname'=> $tripinvestigator->getSurname(),
//                            'firstname' => $tripinvestigator->getFirstname(),
//                            'tripnr' => $tripinvestigator->getTripnr()
//                        ], []);
//                    if (count($results) > 1){
//
//                        $alreadyPresentTripinvestigator = $results[0];
//                        dump('alreadypresenttripinvestigator');
//                        dump($alreadyPresentTripinvestigator);
//                        $trip->addTripinvestigator($alreadyPresentTripinvestigator);
//
//                        $duplicatedTripinvestigator = $results[1];
//                        dump('duplicated');
//                        dump($duplicatedTripinvestigator);
//                        $trip->removeTripinvestigator($duplicatedTripinvestigator);
//                        $entityManager->remove($duplicatedTripinvestigator);
//                    }else {
//                        $trip->addTripinvestigator($tripinvestigator);
//                    }
//                }
//            }
//        }
//
//
//    }

    //https://stackoverflow.com/questions/21280011/prevent-duplicates-in-the-database-in-a-many-to-many-relationship
    //https://stackoverflow.com/questions/29601773/symfony-persisting-embedded-forms-and-avoiding-duplicate-entries

//     * @ORM\PreFlush()
//    /**
//
//     */
//    public function preUpdate(LifecycleEventArgs $args) {
//
//        $entity = $args->getEntity();
//        dump('preupdate');
//        dd($entity);
//        if($entity instanceof Cruise) {
////            dd('instance of cruise');
//            $entityManager = $args->getObjectManager();
//            $trips= $entity->getTrips();
//
//            foreach ($trips as $trip) {
//                $tripinvestigators = $trip->getTripinvestigators();
//                dump($tripinvestigators);
//                foreach($tripinvestigators as $tripinvestigator){
//                    $results = $entityManager->getRepository(Tripinvestigators::class)
//                        ->findBy([
//                            'surname'=> $tripinvestigator->getSurname(),
//                            'firstname' => $tripinvestigator->getFirstname(),
//                            'tripnr' => $tripinvestigator->getTripnr()
//                        ], []);
//                    if (count($results) > 1) {
//
//                        $alreadyPresentTripinvestigator = $results[0];
//                        dump('alreadypresenttripinvestigator');
//                        dump($alreadyPresentTripinvestigator);
//                        $trip->addTripinvestigator($alreadyPresentTripinvestigator);
//
//                        $duplicatedTripinvestigator = $results[1];
//                        dump('duplicated');
//                        dump($duplicatedTripinvestigator);
//                        $trip->removeTripinvestigator($duplicatedTripinvestigator);
//                        $entityManager->remove($duplicatedTripinvestigator);
//                    }else {
//                        $trip->addTripinvestigator($tripinvestigator);
//                    }
//                    dump(count($results));
//                }
//
//            }
//            die();
//
//
////            $tripinvestigators = $entity->getTripinvestigators();
//
//
//
//        }
//
//
//    }

}


//public function prePersist (LifecycleEventArgs $args) {
//    dump('prepersist');
//    $entity = $args->getEntity();
////    dd('prepersist');
//    if($entity instanceof Trip) {
//        $entityManager = $args->getObjectManager();
//        $tripinvestigators = $entity->getTripinvestigators();
//
//        foreach ($tripinvestigators as $key => $tripinvestigator) {
//            $results = $entityManager->getRepository(Tripinvestigators::class)
//                ->findBy([
//                    'surname'=> $tripinvestigator->getSurname(),
//                    'firstname' => $tripinvestigator->getFirstname(),
//                    'tripnr' => $tripinvestigator->getTripnr()
//                ], []);
//            if (count($results) > 0){
//                $tripinvestigators[$key] = $results[0];
//            }
//        }
//    }
//}
//
//
//public function preUpdate(LifecycleEventArgs $args) {
//    dump('preupdate');
//    $entity = $args->getEntity();
//    dd($entity);
//    if($entity instanceof Trip) {
//        dd('instance of trip');
//        $entityManager = $args->getObjectManager();
//        $tripinvestigators = $entity->getTripinvestigators();
//
//
//        foreach($tripinvestigators as $tripinvestigator){
//            $results = $entityManager->getRepository(Tripinvestigators::class)
//                ->findBy([
//                    'surname'=> $tripinvestigator->getSurname(),
//                    'firstname' => $tripinvestigator->getFirstname(),
//                    'tripnr' => $tripinvestigator->getTripnr()
//                ], []);
//            if (count($results) > 1) {
//                $alreadyPresentTripinvestigator = $results[0];
//                $entity->addTripinvestigator($alreadyPresentTripinvestigator);
//
//                $duplicatedTripinvestigator = $results[1];
//                $entityManager->remove($duplicatedTripinvestigator);
//            }else {
//                $entity->addTripinvestigator($tripinvestigator);
//            }
//
//
//
//        }
//    }
//
//
//}