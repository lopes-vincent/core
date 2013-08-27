<?php
/**********************************************************************************/
/*                                                                                */
/*      Thelia	                                                                  */
/*                                                                                */
/*      Copyright (c) OpenStudio                                                  */
/*      email : info@thelia.net                                                   */
/*      web : http://www.thelia.net                                               */
/*                                                                                */
/*      This program is free software; you can redistribute it and/or modify      */
/*      it under the terms of the GNU General Public License as published by      */
/*      the Free Software Foundation; either version 3 of the License             */
/*                                                                                */
/*      This program is distributed in the hope that it will be useful,           */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of            */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             */
/*      GNU General Public License for more details.                              */
/*                                                                                */
/*      You should have received a copy of the GNU General Public License         */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.      */
/*                                                                                */
/**********************************************************************************/

namespace Thelia\Coupon;

use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Thelia\Coupon\Type\CouponInterface;
use Thelia\Exception\CouponExpiredException;
use Thelia\Exception\InvalidRuleException;
use Thelia\Model\Coupon;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Created by JetBrains PhpStorm.
 * Date: 8/19/13
 * Time: 3:24 PM
 *
 * Generate a CouponInterface
 *
 * @package Coupon
 * @author  Guillaume MOREL <gmorel@openstudio.fr>
 *
 */
class CouponFactory
{
    /** @var  CouponAdapterInterface Provide necessary value from Thelia*/
    protected $adapter;

    /**
     * Constructor
     *
     * @param CouponAdapterInterface $adapter Provide necessary value from Thelia
     */
    function __construct(CouponAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Build a CouponInterface from its database data
     *
     * @param string $couponCode Coupon code ex: XMAS
     *
     * @throws \Thelia\Exception\CouponExpiredException
     * @throws \Symfony\Component\Translation\Exception\NotFoundResourceException
     * @return CouponInterface ready to be processed
     */
    public function buildCouponFromCode($couponCode)
    {
        /** @var Coupon $couponModel */
        $couponModel = $this->adapter->findOneCouponByCode($couponCode);
        if ($couponModel === null) {
            throw new NotFoundResourceException(
                'Coupon ' . $couponCode . ' not found in Database'
            );
        }

        if ($couponModel->getExpirationDate() < new \DateTime()) {
            throw new CouponExpiredException($couponCode);
        }

        /** @var CouponInterface $couponInterface */
        $couponInterface = $this->buildCouponInterfacFromModel($couponModel);
        if ($couponInterface->getRules()->isEmpty()) {
            throw new InvalidRuleException(
                get_class($couponInterface)
            );
        }

        return $couponInterface;
    }

    /**
     * Build a CouponInterface from its Model data contained in the DataBase
     *
     * @param Coupon $model Database data
     *
     * @return CouponInterface ready to use CouponInterface object instance
     */
    protected function buildCouponInterfacFromModel(Coupon $model)
    {
        $isCumulative = ($model->getIsCumulative() == 1 ? true : false);
        $isRemovingPostage = ($model->getIsRemovingPostage() == 1 ? true : false);
        $couponClass = $model->getType();

        /** @var CouponInterface $coupon*/
        $coupon = new $couponClass(
            $model->getCode(),
            $model->getTitle(),
            $model->getShortDescription(),
            $model->getDescription(),
            $model->getAmount(),
            $isCumulative,
            $isRemovingPostage,
            $model->getIsAvailableOnSpecialOffers(),
            $model->getIsEnabled(),
            $model->getMaxUsage(),
            $model->getExpirationDate()
        );

        /** @var CouponRuleCollection $rules */
        $rules = unserialize(base64_decode($model->getSerializedRules()));

        $coupon->setRules($rules);

        return $coupon;
    }
}
