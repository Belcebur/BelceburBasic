<?php
namespace BelceburBasic\Resource\Doctrine;

/**
 * Class EntityRepositoryTrait
 *
 * @package BelceburBasic\Resource\Doctrine
 *
 */
trait EntityRepositoryTrait {

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return \Doctrine\ORM\Query
     */
    public function queryFindNotBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL) {

        $qb   = $this->getEntityManager()->createQueryBuilder();
        $expr = $this->getEntityManager()->getExpressionBuilder();

        $qb->select('entity')->from($this->getEntityName(), 'entity');

        foreach ($criteria as $field => $value) {
            $qb->andWhere($expr->neq('entity.' . $field, $value));
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
     * @param null  $limit
     * @param null  $offset
     *
     * @return \Doctrine\ORM\Query
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function queryFindLikeBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL) {
        $qb   = $this->getEntityManager()->createQueryBuilder();
        $expr = $this->getEntityManager()->getExpressionBuilder();

        $qb->select('entity')->from($this->getEntityName(), 'entity');
        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $orx = $expr->orX();
                foreach ($value as $internalValue) {
                    if (is_string($internalValue) && strpos($internalValue, '%') !== FALSE) {
                        $orx->add($expr->like('entity.' . $field, "'{$internalValue}'"));
                    } else {
                        $orx->add($expr->eq('entity.' . $field, "'{$internalValue}'"));
                    }
                }
                $qb->andWhere($orx);
            } elseif (is_object($value)) {
                $meta               = $this->getEntityManager()->getClassMetadata(get_class($value));
                $identifier         = $meta->getSingleIdentifierFieldName();
                $identifierFunction = "get{$identifier}";
                $qb->andWhere($expr->eq('entity.' . $field, call_user_func(array($value, $identifierFunction))));
            } elseif (is_string($value) && strpos($value, '%') !== FALSE) {
                $qb->andWhere($expr->like('entity.' . $field, "'{$value}'"));
            } else {
                $qb->andWhere($expr->eq('entity.' . $field, "'{$value}'"));
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
     * @param array      $orCriteria  Example: array('name'=>'%david%') OR array('name'=>'david%') or array('name'=>'%david')
     * @param array|NULL $orderBy
     * @param null       $limit
     * @param null       $offset
     * @param null       $andCriteria Example: array('name'=>'%david%') OR array('name'=>'david%') or array('name'=>'%david')
     *
     * @return \Doctrine\ORM\Query
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function queryFindOrLikeBy(array $orCriteria, array $orderBy = NULL, $limit = NULL, $offset = NULL, $andCriteria = NULL) {
        $qb   = $this->getEntityManager()->createQueryBuilder();
        $expr = $this->getEntityManager()->getExpressionBuilder();
        $orx  = $expr->orX();

        $qb->select('entity')->from($this->getEntityName(), 'entity');
        foreach ($orCriteria as $field => $value) {
            if (is_array($value)) {
                foreach ($value as $internalValue) {
                    if (is_string($internalValue) && strpos($internalValue, '%') !== FALSE) {
                        $orx->add($expr->like('entity.' . $field, "'{$internalValue}'"));
                    } else {
                        $orx->add($expr->eq('entity.' . $field, "'{$internalValue}'"));
                    }
                }
            } elseif (is_string($value) && strpos($value, '%') !== FALSE) {
                $orx->add($expr->like('entity.' . $field, "'{$value}'"));
            } elseif (is_object($value)) {
                $meta               = $this->getEntityManager()->getClassMetadata(get_class($value));
                $identifier         = $meta->getSingleIdentifierFieldName();
                $identifierFunction = "get{$identifier}";
                $qb->andWhere($expr->eq('entity.' . $field, call_user_func(array($value, $identifierFunction))));
            } else {
                $orx->add($expr->eq('entity.' . $field, "'{$value}'"));
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
                    $andX->add($expr->like('entity.' . $field, "'{$value}'"));
                } else {
                    $andX->add($expr->eq('entity.' . $field, "'{$value}'"));
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
     * @param null  $limit
     * @param null  $offset
     *
     * @return \Doctrine\ORM\Query
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function queryFindNotLikeBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL) {
        $qb   = $this->getEntityManager()->createQueryBuilder();
        $expr = $this->getEntityManager()->getExpressionBuilder();

        $qb->select('entity')->from($this->getEntityName(), 'entity');
        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $andx = $expr->andX();
                foreach ($value as $internalValue) {
                    if (is_string($internalValue) && strpos($internalValue, '%') !== FALSE) {
                        $andx->add($expr->notLike('entity.' . $field, "'{$internalValue}'"));
                    } elseif (is_object($internalValue)) {
                        $meta               = $this->getEntityManager()->getClassMetadata(get_class($internalValue));
                        $identifier         = $meta->getSingleIdentifierFieldName();
                        $identifierFunction = "get{$identifier}";
                        $andx->add($expr->neq('entity.' . $field, call_user_func(array($internalValue, $identifierFunction))));
                    } else {
                        $andx->add($expr->neq('entity.' . $field, "'{$internalValue}'"));
                    }
                }
                $qb->andWhere($andx);
            } elseif (is_string($value) && strpos($value, '%') !== FALSE) {
                $qb->andWhere($expr->notLike('entity.' . $field, "'{$value}'"));
            } elseif (is_object($value)) {
                $meta               = $this->getEntityManager()->getClassMetadata(get_class($value));
                $identifier         = $meta->getSingleIdentifierFieldName();
                $identifierFunction = "get{$identifier}";
                $qb->andWhere($expr->neq('entity.' . $field, call_user_func(array($value, $identifierFunction))));
            } else {
                $qb->andWhere($expr->neq('entity.' . $field, "'{$value}'"));
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