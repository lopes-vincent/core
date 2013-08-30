<?php

namespace Thelia\Model\Tools;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Thelia\Model\Base\LangQuery;
use Thelia\Model\ConfigQuery;

/**
 * Class ModelCriteriaTools
 *
 * @package Thelia\Model\Tools
 * @author Etienne Roudeix <eroudeix@openstudio.fr>
 */
class ModelCriteriaTools
{
    /**
     * @param ModelCriteria $search
     * @param               $requestedLocale
     * @param array         $columns
     * @param null          $foreignTable
     * @param string        $foreignKey
     */
    public static function getFrontEndI18n(ModelCriteria &$search, $requestedLocale, $columns, $foreignTable, $foreignKey)
    {
        if($foreignTable === null) {
            $foreignTable = $search->getTableMap()->getName();
            $aliasPrefix = '';
        } else {
            $aliasPrefix = $foreignTable . '_';
        }

        $defaultLangWithoutTranslation = ConfigQuery::read("default_lang_without_translation", 1);

        $requestedLocaleI18nAlias = 'requested_locale_i18n';
        $defaultLocaleI18nAlias = 'default_locale_i18n';

        if (!$defaultLangWithoutTranslation == 0) {

            $requestedLocaleJoin = new Join();
            $requestedLocaleJoin->addExplicitCondition($search->getTableMap()->getName(), $foreignKey, null, $foreignTable . '_i18n', 'ID', $requestedLocaleI18nAlias);
            $requestedLocaleJoin->setJoinType(Criteria::INNER_JOIN);

            $search->addJoinObject($requestedLocaleJoin, $requestedLocaleI18nAlias)
                ->addJoinCondition($requestedLocaleI18nAlias ,'`' . $requestedLocaleI18nAlias . '`.LOCALE = ?', $requestedLocale, null, \PDO::PARAM_STR);

            $search->withColumn('NOT ISNULL(`' . $requestedLocaleI18nAlias . '`.`ID`)', $aliasPrefix . 'IS_TRANSLATED');

            foreach($columns as $column) {
                $search->withColumn('`' . $requestedLocaleI18nAlias . '`.`' . $column . '`', $aliasPrefix . 'i18n_' . $column);
            }
        } else {
            $defaultLocale = LangQuery::create()->findOneById($defaultLangWithoutTranslation)->getLocale();

            $defaultLocaleJoin = new Join();
            $defaultLocaleJoin->addExplicitCondition($search->getTableMap()->getName(), $foreignKey, null, $foreignTable . '_i18n', 'ID', $defaultLocaleI18nAlias);
            $defaultLocaleJoin->setJoinType(Criteria::LEFT_JOIN);

            $search->addJoinObject($defaultLocaleJoin, $defaultLocaleI18nAlias)
                ->addJoinCondition($defaultLocaleI18nAlias ,'`' . $defaultLocaleI18nAlias . '`.LOCALE = ?', $defaultLocale, null, \PDO::PARAM_STR);

            $requestedLocaleJoin = new Join();
            $requestedLocaleJoin->addExplicitCondition($search->getTableMap()->getName(), $foreignKey, null, $foreignTable . '_i18n', 'ID', $requestedLocaleI18nAlias);
            $requestedLocaleJoin->setJoinType(Criteria::LEFT_JOIN);

            $search->addJoinObject($requestedLocaleJoin, $requestedLocaleI18nAlias)
                ->addJoinCondition($requestedLocaleI18nAlias ,'`' . $requestedLocaleI18nAlias . '`.LOCALE = ?', $requestedLocale, null, \PDO::PARAM_STR);

            $search->withColumn('NOT ISNULL(`' . $requestedLocaleI18nAlias . '`.`ID`)', $aliasPrefix . 'IS_TRANSLATED');

            $search->where('NOT ISNULL(`' . $requestedLocaleI18nAlias . '`.ID)')->_or()->where('NOT ISNULL(`' . $defaultLocaleI18nAlias . '`.ID)');

            foreach($columns as $column) {
                $search->withColumn('CASE WHEN NOT ISNULL(`' . $requestedLocaleI18nAlias . '`.ID) THEN `' . $requestedLocaleI18nAlias . '`.`' . $column . '` ELSE `' . $defaultLocaleI18nAlias . '`.`' . $column . '` END', $aliasPrefix . 'i18n_' . $column);
            }
        }
    }

    public static function getBackEndI18n(ModelCriteria &$search, $requestedLocale, $columns, $foreignTable, $foreignKey)
    {
        if($foreignTable === null) {
            $foreignTable = $search->getTableMap()->getName();
            $aliasPrefix = '';
        } else {
            $aliasPrefix = $foreignTable . '_';
        }

        $requestedLocaleI18nAlias = 'requested_locale_i18n';

        $requestedLocaleJoin = new Join();
        $requestedLocaleJoin->addExplicitCondition($search->getTableMap()->getName(), $foreignKey, null, $foreignTable . '_i18n', 'ID', $requestedLocaleI18nAlias);
        $requestedLocaleJoin->setJoinType(Criteria::LEFT_JOIN);

        $search->addJoinObject($requestedLocaleJoin, $requestedLocaleI18nAlias)
            ->addJoinCondition($requestedLocaleI18nAlias ,'`' . $requestedLocaleI18nAlias . '`.LOCALE = ?', $requestedLocale, null, \PDO::PARAM_STR);

        $search->withColumn('NOT ISNULL(`' . $requestedLocaleI18nAlias . '`.`ID`)', $aliasPrefix . 'IS_TRANSLATED');

        foreach($columns as $column) {
            $search->withColumn('`' . $requestedLocaleI18nAlias . '`.`' . $column . '`', $aliasPrefix . 'i18n_' . $column);
        }
    }

    public static function getI18n($backendContext, $requestedLangId, ModelCriteria &$search, $currentLocale, $columns, $foreignTable, $foreignKey)
    {
        // If a lang has been requested, find the related Lang object, and get the locale
        if ($requestedLangId !== null) {
            $localeSearch = LangQuery::create()->findOneById($requestedLangId);

            if ($localeSearch === null) {
                throw new \InvalidArgumentException(sprintf('Incorrect lang argument given in attribute loop: lang ID %d not found', $requestedLangId));
            }

            $locale = $localeSearch->getLocale();
        }
        else {
            // Use the currently defined locale
            $locale = $currentLocale;
        }

        // Call the proper method depending on the context: front or back
        if ($backendContext) {
            self::getBackEndI18n($search, $locale, $columns, $foreignTable, $foreignKey);
        } else {
            self::getFrontEndI18n($search, $locale, $columns, $foreignTable, $foreignKey);
        }
    }
}
