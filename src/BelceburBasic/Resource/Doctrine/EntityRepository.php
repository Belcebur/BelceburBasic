<?php

namespace BelceburBasic\Resource\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;

/**
 * Class EntityRepository
 *
 * @package BelceburBasic\Resource\Doctrine
 */
abstract class EntityRepository extends DoctrineEntityRepository
{
    use EntityRepositoryTrait;

    /**
     * @var string
     */
    protected $gedmoWalker = TranslationWalker::class;

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return ArrayCollection
     */
    public function findNotBy(array $criteria = [], array $orderBy = [], $limit = NULL, $offset = NULL): ArrayCollection
    {
        $query = $this->queryFindNotBy($criteria, $orderBy, $limit, $offset);
        return new ArrayCollection($query->getResult());
    }


    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneNotBy(array $criteria = [], array $orderBy = [])
    {
        $query = $this->queryFindNotBy($criteria, $orderBy, 1);
        return $query->getOneOrNullResult();
    }

    /**
     * @param array $orCriteria Example: array('name'=>'%david%') OR array('name'=>'david%') or array('name'=>'%david')
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @param array $andCriteria Example: array('name'=>'%david%') OR array('name'=>'david%') or array('name'=>'%david')
     *
     * @return ArrayCollection
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function findOrLikeBy(array $orCriteria = [], array $orderBy = [], $limit = NULL, $offset = NULL, $andCriteria = []): ArrayCollection
    {
        $query = $this->queryFindOrLikeBy($orCriteria, $orderBy, $limit, $offset, $andCriteria);
        return new ArrayCollection($query->getResult());
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function findOneOrLikeBy(array $criteria = [], array $orderBy = [])
    {
        $query = $this->queryFindOrLikeBy($criteria, $orderBy, 1);
        return $query->getOneOrNullResult();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function findOneBy(array $criteria = [], array $orderBy = [])
    {
        return $this->findOneLikeBy($criteria, $orderBy);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function findOneLikeBy(array $criteria = [], array $orderBy = [])
    {
        $query = $this->queryFindLikeBy($criteria, $orderBy, 1);
        return $query->getOneOrNullResult();
    }

    /**
     *
     * Busca realizando not likes
     *
     *
     * @param array $criteria Los valores deben estar entre "" aunque sea strings y con los % delante y/o detras
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return ArrayCollection
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function findNotLikeBy(array $criteria = [], array $orderBy = [], $limit = NULL, $offset = NULL): ArrayCollection
    {
        $query = $this->queryFindNotLikeBy($criteria, $orderBy, $limit, $offset);
        return new ArrayCollection($query->getResult());
    }

    /**
     * @return ArrayCollection
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function findAll(): ArrayCollection
    {
        return $this->findBy();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return ArrayCollection
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function findBy(array $criteria = [], array $orderBy = [], $limit = NULL, $offset = NULL): ArrayCollection
    {
        return $this->findLikeBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return ArrayCollection
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function findLikeBy(array $criteria = [], array $orderBy = [], $limit = NULL, $offset = NULL): ArrayCollection
    {

        $query = $this->queryFindLikeBy($criteria, $orderBy, $limit, $offset);
        return new ArrayCollection($query->getResult());
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function findOneNotLikeBy(array $criteria = [], array $orderBy = [])
    {
        $query = $this->queryFindNotLikeBy($criteria, $orderBy, 1);
        return $query->getOneOrNullResult();
    }

} 