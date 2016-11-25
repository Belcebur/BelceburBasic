<?php

namespace BelceburBasic\Resource\Doctrine\Gedmo;

use BelceburBasic\Resource\Doctrine\EntityRepositoryTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use Gedmo\Translatable\TranslatableListener;

/**
 * Class EntityRepositoryTranslatable
 *
 * @package BelceburBasic\Resource\Doctrine\Gedmo
 *
 */
abstract class EntityRepositoryTranslatable extends TranslationRepository {
    use EntityRepositoryTrait;

    /**
     * @var string
     */
    protected $gedmoWalker = 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker';

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

        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, $this->gedmoWalker);
        $this->applyTranslatorGedmoHints($query);


        return new ArrayCollection($query->getResult());
    }

    /**
     * @param Query $query
     */
    protected function applyTranslatorGedmoHints(Query &$query) {
        if (BELCEBUR_GEDMO_TRANSLATION_LOCALE !== 'BELCEBUR_GEDMO_TRANSLATION_LOCALE') {
            $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, $this->gedmoWalker);
            $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, BELCEBUR_GEDMO_TRANSLATION_LOCALE);
            $query->setHint(TranslatableListener::HINT_FALLBACK, TRUE);
        }
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneNotBy(array $criteria) {
        $query = $this->queryFindNotBy($criteria, array(), 1);
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, $this->gedmoWalker);
        $this->applyTranslatorGedmoHints($query);

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
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, $this->gedmoWalker);
        $this->applyTranslatorGedmoHints($query);

        return new ArrayCollection($query->getResult());
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findOneOrLikeBy(array $criteria) {
        $query = $this->queryFindOrLikeBy($criteria, array(), 1);
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, $this->gedmoWalker);
        $this->applyTranslatorGedmoHints($query);

        return $query->getOneOrNullResult();
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findOneBy(array $criteria) {
        return $this->findOneLikeBy($criteria);
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneLikeBy(array $criteria) {
        $query = $this->queryFindLikeBy($criteria, array(), 1);
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, $this->gedmoWalker);
        $this->applyTranslatorGedmoHints($query);

        return $query->getOneOrNullResult();
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
     * @return ArrayCollection
     */
    public function findNotLikeBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL) {
        $query = $this->queryFindNotLikeBy($criteria, $orderBy, $limit, $offset);
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, $this->gedmoWalker);
        $this->applyTranslatorGedmoHints($query);

        return new ArrayCollection($query->getResult());
    }

    /**
     * @return ArrayCollection
     */
    public function findAll() {
        return $this->findBy([]);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return ArrayCollection|mixed
     */
    public function findBy(array $criteria, array $orderBy = array(), $limit = NULL, $offset = NULL) {
        if ($limit === 1) {
            return $this->findOneLikeBy($criteria);
        }

        return $this->findLikeBy($criteria, $orderBy, $limit, $offset);
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
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, $this->gedmoWalker);
        $this->applyTranslatorGedmoHints($query);

        return new ArrayCollection($query->getResult());
    }

}