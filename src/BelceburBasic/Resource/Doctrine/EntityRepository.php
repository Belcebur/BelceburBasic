<?php
namespace BelceburBasic\Resource\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;

/**
 * Class EntityRepository
 *
 * @package BelceburBasic\Resource\Doctrine
 */
abstract class EntityRepository extends DoctrineEntityRepository {
    use EntityRepositoryTrait;

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return ArrayCollection
     */
    public function findNotBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL) {
        $query = $this->queryFindNotBy($criteria, $orderBy, $limit, $offset);

        return new ArrayCollection($query->getResult());
    }


    /**
     * @param array $criteria
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneNotBy(array $criteria) {
        $query = $this->queryFindNotBy($criteria, array(), 1);

        return $query->getOneOrNullResult();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return ArrayCollection
     */
    public function findLikeBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL) {

        $query = $this->queryFindLikeBy($criteria, $orderBy, $limit, $offset);

        return new ArrayCollection($query->getResult());
    }


    /**
     * @param array $criteria
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneLikeBy(array $criteria) {
        $query = $this->queryFindLikeBy($criteria, array(), 1);

        return $query->getOneOrNullResult();
    }

    /**
     * @param array $orCriteria  Example: array('name'=>'%david%') OR array('name'=>'david%') or array('name'=>'%david')
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     * @param null  $andCriteria Example: array('name'=>'%david%') OR array('name'=>'david%') or array('name'=>'%david')
     *
     * @return ArrayCollection
     */
    public function findOrLikeBy(array $orCriteria, array $orderBy = NULL, $limit = NULL, $offset = NULL, $andCriteria = NULL) {
        $query = $this->queryFindOrLikeBy($orCriteria, $orderBy, $limit, $offset, $andCriteria);

        return new ArrayCollection($query->getResult());
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findOneOrLikeBy(array $criteria) {
        $query = $this->queryFindOrLikeBy($criteria, array(), 1);

        return $query->getOneOrNullResult();
    }

    /**
     * @param array $criteria Example: array('name'=>'%david%') OR array('name'=>'david%') or array('name'=>'%david')
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return ArrayCollection
     */
    public function findNotLikeBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL) {
        $query = $this->queryFindNotLikeBy($criteria, $orderBy, $limit, $offset);

        return new ArrayCollection($query->getResult());
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findOneNotLikeBy(array $criteria) {
        $query = $this->queryFindNotLikeBy($criteria, array(), 1);

        return $query->getOneOrNullResult();
    }
} 