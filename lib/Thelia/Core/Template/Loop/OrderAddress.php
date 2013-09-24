<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace Thelia\Core\Template\Loop;

use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;

use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Core\Template\Loop\Argument\Argument;

use Thelia\Model\AddressQuery;
use Thelia\Model\OrderAddressQuery;
use Thelia\Type\TypeCollection;
use Thelia\Type;

/**
 *
 * OrderAddress loop
 *
 *
 * Class OrderAddress
 * @package Thelia\Core\Template\Loop
 * @author Etienne Roudeix <eroudeix@openstudio.fr>
 */
class OrderAddress extends BaseLoop
{
    public $timestampable = true;

    /**
     * @return ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id', null, true)
        );
    }

    /**
     * @param $pagination
     *
     * @return \Thelia\Core\Template\Element\LoopResult
     */
    public function exec(&$pagination)
    {
        $search = OrderAddressQuery::create();

        $id = $this->getId();

        $search->filterById($id, Criteria::IN);

        $orderAddresses = $this->search($search, $pagination);

        $loopResult = new LoopResult($orderAddresses);

        foreach ($orderAddresses as $orderAddress) {
            $loopResultRow = new LoopResultRow($loopResult, $orderAddress, $this->versionable, $this->timestampable, $this->countable);
            $loopResultRow
                ->set("ID", $orderAddress->getId())
                ->set("TITLE", $orderAddress->getCustomerTitleId())
                ->set("COMPANY", $orderAddress->getCompany())
                ->set("FIRSTNAME", $orderAddress->getFirstname())
                ->set("LASTNAME", $orderAddress->getLastname())
                ->set("ADDRESS1", $orderAddress->getAddress1())
                ->set("ADDRESS2", $orderAddress->getAddress2())
                ->set("ADDRESS3", $orderAddress->getAddress3())
                ->set("ZIPCODE", $orderAddress->getZipcode())
                ->set("CITY", $orderAddress->getCity())
                ->set("COUNTRY", $orderAddress->getCountryId())
                ->set("PHONE", $orderAddress->getPhone())
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
