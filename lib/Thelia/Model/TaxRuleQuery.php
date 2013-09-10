<?php

namespace Thelia\Model;

use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Model\Base\TaxRuleQuery as BaseTaxRuleQuery;
use Thelia\Model\Map\TaxRuleCountryTableMap;
use Thelia\Model\Map\TaxTableMap;

/**
 * Skeleton subclass for performing query and update operations on the 'tax_rule' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class TaxRuleQuery extends BaseTaxRuleQuery
{
    const ALIAS_FOR_TAX_RULE_COUNTRY_POSITION = 'taxRuleCountryPosition';
    const ALIAS_FOR_TAX_RATE_SUM = 'taxRateSum';

    public function getTaxCalculatorGroupedCollection(Product $product, Country $country)
    {
        $search = TaxQuery::create()
            ->filterByTaxRuleCountry(
                TaxRuleCountryQuery::create()
                    ->filterByCountry($country, Criteria::EQUAL)
                    ->filterByTaxRuleId($product->getTaxRuleId())
                    ->groupByPosition()
                    ->orderByPosition()
                    ->find()
            )
            ->withColumn(TaxRuleCountryTableMap::POSITION, self::ALIAS_FOR_TAX_RULE_COUNTRY_POSITION)
            ->withColumn('ROUND(SUM(' . TaxTableMap::RATE . '), 2)', self::ALIAS_FOR_TAX_RATE_SUM)
        ;

        return $search->find();
    }
} // TaxRuleQuery
