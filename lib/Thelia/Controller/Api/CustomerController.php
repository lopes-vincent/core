<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Thelia\Controller\Api;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Thelia\Core\Event\Customer\CustomerCreateOrUpdateEvent;
use Thelia\Core\Event\Customer\CustomerEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Customer;
use Thelia\Exception\CustomerException;
use Thelia\Form\Api\Customer\CustomerCreateForm;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\CustomerQuery;
use Thelia\Model\Map\CustomerTableMap;
use Thelia\Model\Map\CustomerTitleI18nTableMap;
use Thelia\Type\EnumType;
use Thelia\Type\TypeCollection;

/**
 * Class CustomerController
 * @package Thelia\Controller\Api
 * @author Manuel Raynaud <mraynaud@openstudio.fr>
 */
class CustomerController extends BaseApiController
{

    public function listAction()
    {
        $this->checkAuth(AdminResources::CUSTOMER, [], AccessManager::VIEW);
        $request = $this->getRequest();

        $customerLoop = new Customer($this->getContainer());
        try {
            $parameters = array_merge($request->query->all(), [
                'current' => false,
                'limit' => $request->query->get('limit', 10),
                'offset' => $request->query->get('offset', 0)
            ]);
            $customerLoop->initializeArgs($parameters);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException(sprintf('{"error": "%s"}', $e->getMessage()));
        }


        $results = $customerLoop
            ->buildModelCriteria()
            ->select(['Id', 'Ref', 'TitleId', 'Firstname', 'Lastname', 'Email', 'Reseller', 'Lang', 'Sponsor', 'Discount', 'CreatedAt', 'UpdatedAt'])
            ->limit($parameters['limit'])
            ->offset($parameters['offset'])
            ->find()->toJSON(false, true);

        return Response::create($results);

    }

    public function getCustomerAction($customer_id)
    {
        $this->checkAuth(AdminResources::CUSTOMER, [], AccessManager::VIEW);
        $request = $this->getRequest();

        $customer = new Customer($this->getContainer());

        try {
            $customer->initializeArgs([
                'current'   => false,
                'id'        => $customer_id
            ]);
        } catch(\InvalidArgumentException $e) {
            throw new BadRequestHttpException(sprintf('{"error": "%s"}', $e->getMessage()));
        }

        $result = $customer->buildModelCriteria()
            ->select(['Id', 'Ref', 'TitleId', 'Firstname', 'Lastname', 'Email', 'Reseller', 'Lang', 'Sponsor', 'Discount', 'CreatedAt', 'UpdatedAt'])
            ->find();

        if ($result->isEmpty()) {
            throw new HttpException(404, sprintf('{"error": "customer with id %d not found"}', $customer_id));
        }

        return Response::create($result->toJSON(false, true));

    }

    public function createCustomerAction()
    {
        $this->checkAuth(AdminResources::CUSTOMER, [], AccessManager::CREATE);
        $request = $this->getRequest();
        $form = new CustomerCreateForm($this->getRequest(), "form",[], ['csrf_protection' => false]);

        try {
            $customerForm = $this->validateForm($form);
            $event = $this->hydrateEvent($customerForm);

            $this->dispatch(TheliaEvents::CUSTOMER_CREATEACCOUNT, $event);
            $customer = $event->getCustomer()->toArray();
            unset($customer['Password']);
            unset($customer['Algo']);

            return Response::create(
                json_encode($customer)
                , 201
            );
        } catch (FormValidationException $e) {
            return Response::create($e->getMessage(), 400);
        }
    }

    public function deleteCustomerAction($customer_id)
    {
        $this->checkAuth(AdminResources::CUSTOMER, [], AccessManager::DELETE);

        $customer = CustomerQuery::create()
            ->findPk($customer_id);



        if (null === $customer) {
            throw new HttpException(404, sprintf('{"error": "customer with id %d not found"}', $customer_id));
        }

        $event = new CustomerEvent($customer);

        try {
            $this->dispatch(TheliaEvents::CUSTOMER_DELETEACCOUNT, $event);
        } catch (CustomerException $e) {
            throw new HttpException(403, '{"error": "Impossible to delete a customer who already have orders"}');
        }

        return Response::create(null, 204);
    }

    protected function hydrateEvent(Form $form)
    {
        $customerCreateEvent = new CustomerCreateOrUpdateEvent(
            $form->get('title')->getData(),
            $form->get('firstname')->getData(),
            $form->get('lastname')->getData(),
            $form->get('address1')->getData(),
            $form->get('address2')->getData(),
            $form->get('address3')->getData(),
            $form->get('phone')->getData(),
            $form->get('cellphone')->getData(),
            $form->get('zipcode')->getData(),
            $form->get('city')->getData(),
            $form->get('country')->getData(),
            $form->get('email')->getData(),
            $form->get('password')->getData(),
            $form->get('lang')->getData(),
            $form->has('reseller') ? $form->get('reseller')->getData():null,
            $form->has('sponsor') ? $form->get('sponsor')->getData():null,
            $form->has('discount') ? $form->get('discount')->getData():null,
            $form->get('company')->getData(),
            null
        );

        return $customerCreateEvent;
    }
}
