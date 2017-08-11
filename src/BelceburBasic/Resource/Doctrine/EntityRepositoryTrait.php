<?php

namespace BelceburBasic\Resource\Doctrine;

/**
 * Class EntityRepositoryTrait
 *
 * @package BelceburBasic\Resource\Doctrine
 *
 */
use Doctrine\ORM\Query;

trait EntityRepositoryTrait
{

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return \Doctrine\ORM\Query
     */
    public function queryFindNotBy(array $criteria = [], array $orderBy = [], $limit = NULL, $offset = NULL): Query
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $expr = $em->getExpressionBuilder();

        $qb->select('entity')->from($this->getEntityName(), 'entity');

        foreach ($criteria as $field => $value) {
            $qb->andWhere($expr->neq('entity.' . $field, $expr->literal($value)));
        }

        if ($orderBy) {
            foreach ($orderBy as $field => $order) {
                $qb->addOrderBy('entity.' . $field, $order);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery();
    }

    /**
     * @param array $criteria Example: array('name'=>'%david%') OR array('name'=>'david%') or array('name'=>'%david')
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return \Doctrine\ORM\Query
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function queryFindLikeBy(array $criteria = [], array $orderBy = [], $limit = NULL, $offset = NULL): Query
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $expr = $em->getExpressionBuilder();

        $qb->select('entity')->from($this->getEntityName(), 'entity');
        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $orx = $expr->orX();
                foreach ((array)$value as $internalValue) {
                    if (is_string($internalValue) && strpos($internalValue, '%') !== FALSE) {
                        $orx->add($expr->like('entity.' . $field, $expr->literal($internalValue)));
                    } else {
                        $orx->add($expr->eq('entity.' . $field, $expr->literal($internalValue)));
                    }
                }
                $qb->andWhere($orx);
            } elseif (is_object($value)) {
                $meta = $em->getClassMetadata(get_class($value));
                $identifier = $meta->getSingleIdentifierFieldName();
                $identifierFunction = "get{$identifier}";
                $qb->andWhere($expr->eq('entity.' . $field, $value->$identifierFunction()));
            } elseif (is_string($value) && strpos($value, '%') !== FALSE) {
                $qb->andWhere($expr->like('entity.' . $field, $expr->literal($value)));
            } else {
                $qb->andWhere($expr->eq('entity.' . $field, $expr->literal($value)));
            }
        }
        if ($orderBy) {
            foreach ($orderBy as $field => $order) {
                $qb->addOrderBy('entity.' . $field, $order);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery();
    }


    /**
     * @param array $orCriteria Example: array('name'=>'%david%') OR array('name'=>'david%') or array('name'=>'%david')
     * @param array|NULL $orderBy
     * @param null $limit
     * @param null $offset
     * @param array $andCriteria Example: array('name'=>'%david%') OR array('name'=>'david%') or array('name'=>'%david')
     *
     * @return \Doctrine\ORM\Query
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function queryFindOrLikeBy(array $orCriteria, array $orderBy = [], $limit = NULL, $offset = NULL, array $andCriteria = []): Query
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $expr = $em->getExpressionBuilder();
        $orx = $expr->orX();

        $qb->select('entity')->from($this->getEntityName(), 'entity');
        foreach ($orCriteria as $field => $value) {
            if (is_array($value)) {
                foreach ((array)$value as $internalValue) {
                    if (is_string($internalValue) && strpos($internalValue, '%') !== FALSE) {
                        $orx->add($expr->like('entity.' . $field, $expr->literal($internalValue)));
                    } else {
                        $orx->add($expr->eq('entity.' . $field, $expr->literal($internalValue)));
                    }
                }
            } elseif (is_string($value) && strpos($value, '%') !== FALSE) {
                $orx->add($expr->like('entity.' . $field, $expr->literal($value)));
            } elseif (is_object($value)) {
                $meta = $em->getClassMetadata(get_class($value));
                $identifier = $meta->getSingleIdentifierFieldName();
                $identifierFunction = "get{$identifier}";
                $qb->andWhere($expr->eq('entity.' . $field, $value->$identifierFunction()));
            } else {
                $orx->add($expr->eq('entity.' . $field, $expr->literal($value)));
            }
        }
        $qb->where($orx);
        if ($orderBy) {
            foreach ($orderBy as $field => $order) {
                $qb->addOrderBy('entity.' . $field, $order);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }
        if ($andCriteria) {
            $andX = $expr->andX();
            foreach ($andCriteria as $field => $value) {
                if (is_string($value) && strpos($value, '%') !== FALSE) {
                    $andX->add($expr->like('entity.' . $field, $expr->literal($value)));
                } else {
                    $andX->add($expr->eq('entity.' . $field, $expr->literal($value)));
                }
            }
            $qb->where($andX);
        }

        return $qb->getQuery();
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
     * @return \Doctrine\ORM\Query
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function queryFindNotLikeBy(array $criteria = [], array $orderBy = [], $limit = NULL, $offset = NULL): Query
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $expr = $em->getExpressionBuilder();

        $qb->select('entity')->from($this->getEntityName(), 'entity');
        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $andX = $expr->andX();
                foreach ((array)$value as $internalValue) {
                    if (is_string($internalValue) && strpos($internalValue, '%') !== FALSE) {
                        $andX->add($expr->notLike('entity.' . $field, $expr->literal($internalValue)));
                    } elseif (is_object($internalValue)) {
                        $meta = $em->getClassMetadata(get_class($internalValue));
                        $identifier = $meta->getSingleIdentifierFieldName();
                        $identifierFunction = "get{$identifier}";
                        $andX->add($expr->neq('entity.' . $field, $internalValue->$identifierFunction()));
                    } else {
                        $andX->add($expr->neq('entity.' . $field, $expr->literal($internalValue)));
                    }
                }
                $qb->andWhere($andX);
            } elseif (is_string($value) && strpos($value, '%') !== FALSE) {
                $qb->andWhere($expr->notLike('entity.' . $field, $expr->literal($value)));
            } elseif (is_object($value)) {
                $meta = $em->getClassMetadata(get_class($value));
                $identifier = $meta->getSingleIdentifierFieldName();
                $identifierFunction = "get{$identifier}";
                $qb->andWhere($expr->neq('entity.' . $field, $value->$identifierFunction()));
            } else {
                $qb->andWhere($expr->neq('entity.' . $field, $expr->literal($value)));
            }
        }
        if ($orderBy) {
            foreach ($orderBy as $field => $order) {
                $qb->addOrderBy('entity.' . $field, $order);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery();
    }
} 