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
namespace Thelia\Controller\Front;

use Thelia\Core\Event\Customer\CustomerCreateOrUpdateEvent;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\LostPasswordEvent;
use Thelia\Core\Security\Authentication\CustomerUsernamePasswordFormAuthenticator;
use Thelia\Core\Security\Exception\AuthenticationException;
use Thelia\Core\Security\Exception\UsernameNotFoundException;
use Thelia\Form\CustomerCreation;
use Thelia\Form\CustomerLogin;
use Thelia\Form\CustomerLostPasswordForm;
use Thelia\Form\CustomerUpdateForm;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\Customer;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\CustomerQuery;
use Thelia\Tools\URL;
use Thelia\Log\Tlog;
use Thelia\Core\Security\Exception\WrongPasswordException;

/**
 * Class CustomerController
 * @package Thelia\Controller\Front
 * @author Manuel Raynaud <mraynaud@openstudio.fr>
 */
class CustomerController extends BaseFrontController
{
    use \Thelia\Cart\CartTrait;

    public function newPasswordAction()
    {
        if (! $this->getSecurityContext()->hasCustomerUser()) {
            $message = false;

            $passwordLost = new CustomerLostPasswordForm($this->getRequest());

            try {

                $form = $this->validateForm($passwordLost);

                $event = new LostPasswordEvent($form->get("email")->getData());

                $this->dispatch(TheliaEvents::LOST_PASSWORD, $event);

            } catch (FormValidationException $e) {
                $message = sprintf("Please check your input: %s", $e->getMessage());
            } catch (\Exception $e) {
                $message = sprintf("Sorry, an error occured: %s", $e->getMessage());
            }

            if ($message !== false) {
                Tlog::getInstance()->error(sprintf("Error during customer creation process : %s. Exception was %s", $message, $e->getMessage()));

                $passwordLost->setErrorMessage($message);

                $this->getParserContext()
                    ->addForm($passwordLost)
                    ->setGeneralError($message)
                ;
            }
        }
    }

    /**
     * Create a new customer.
     * On success, redirect to success_url if exists, otherwise, display the same view again.
     */
    public function createAction()
    {
        if (! $this->getSecurityContext()->hasCustomerUser()) {

            $message = false;

            $customerCreation = new CustomerCreation($this->getRequest());

            try {
                $form = $this->validateForm($customerCreation, "post");

                $customerCreateEvent = $this->createEventInstance($form->getData());

                $this->dispatch(TheliaEvents::CUSTOMER_CREATEACCOUNT, $customerCreateEvent);

                $this->processLogin($customerCreateEvent->getCustomer());

                $cart = $this->getCart($this->getRequest());
                if ($cart->getCartItems()->count() > 0) {
                    $this->redirectToRoute("cart.view");
                } else {
                    $this->redirectSuccess($customerCreation);
                }
            } catch (FormValidationException $e) {
                $message = sprintf("Please check your input: %s", $e->getMessage());
            } catch (\Exception $e) {
                $message = sprintf("Sorry, an error occured: %s", $e->getMessage());
            }

            if ($message !== false) {
                Tlog::getInstance()->error(sprintf("Error during customer creation process : %s. Exception was %s", $message, $e->getMessage()));

                $customerCreation->setErrorMessage($message);

                $this->getParserContext()
                    ->addForm($customerCreation)
                    ->setGeneralError($message)
                ;
            }
        }
    }

    protected function getExistingCustomer($customer_id)
    {
        return CustomerQuery::create()
            ->findOneById($customer_id);
    }

    /**
     * Update customer data. On success, redirect to success_url if exists.
     * Otherwise, display the same view again.
     */
    public function viewAction()
    {
        $this->checkAuth();

        $customer = $this->getSecurityContext()->getCustomerUser();
        $data = array(
            'id'           => $customer->getId(),
            'title'        => $customer->getTitleId(),
            'firstname'    => $customer->getFirstName(),
            'lastname'     => $customer->getLastName(),
            'email'        => $customer->getEmail(),
        );

        $customerUpdateForm = new CustomerUpdateForm($this->getRequest(), 'form', $data);

        // Pass it to the parser
        $this->getParserContext()->addForm($customerUpdateForm);
    }

    public function updateAction()
    {
        if ($this->getSecurityContext()->hasCustomerUser()) {

            $message = false;

            $customerUpdateForm = new CustomerUpdateForm($this->getRequest());

            try {

                $customer = $this->getSecurityContext()->getCustomerUser();

                $form = $this->validateForm($customerUpdateForm, "post");

                $customerChangeEvent = $this->createEventInstance($form->getData());
                $customerChangeEvent->setCustomer($customer);
                $this->dispatch(TheliaEvents::CUSTOMER_UPDATEACCOUNT, $customerChangeEvent);

                $this->processLogin($customerChangeEvent->getCustomer());

                $this->redirectSuccess($customerUpdateForm);

            } catch (FormValidationException $e) {
                $message = sprintf("Please check your input: %s", $e->getMessage());
            } catch (\Exception $e) {
                $message = sprintf("Sorry, an error occured: %s", $e->getMessage());
            }

            if ($message !== false) {
                Tlog::getInstance()->error(sprintf("Error during customer modification process : %s.", $message));

                $customerUpdateForm->setErrorMessage($message);

                $this->getParserContext()
                    ->addForm($customerUpdateForm)
                    ->setGeneralError($message)
                ;
            }
        }
    }

    /**
     * Perform user login. On a successful login, the user is redirected to the URL
     * found in the success_url form parameter, or / if none was found.
     *
     * If login is not successfull, the same view is displayed again.
     *
     */
    public function loginAction()
    {
        if (! $this->getSecurityContext()->hasCustomerUser()) {
            $message = false;

            $request = $this->getRequest();
            $customerLoginForm = new CustomerLogin($request);

            try {

                $form = $this->validateForm($customerLoginForm, "post");

                // If User is a new customer
                if ($form->get('account')->getData() == 0 && !$form->get("email")->getErrors()) {
                    $this->redirectToRoute("customer.create.view", array("email" => $form->get("email")->getData()));
                } else {

                    try {

                        $authenticator = new CustomerUsernamePasswordFormAuthenticator($request, $customerLoginForm);

                        $customer = $authenticator->getAuthentifiedUser();

                        $this->processLogin($customer);

                        $this->redirectSuccess($customerLoginForm);

                    } catch (UsernameNotFoundException $e) {
                        $message = "Wrong email or password. Please try again";
                    } catch (WrongPasswordException $e) {
                        $message = "Wrong email or password. Please try again";
                    } catch (AuthenticationException $e) {
                        $message = "Wrong email or password. Please try again";
                    }

                }

            } catch (FormValidationException $e) {
                $message = sprintf("Please check your input: %s", $e->getMessage());
            } catch (\Exception $e) {
                $message = sprintf("Sorry, an error occured: %s", $e->getMessage());
            }

            if ($message !== false) {
                Tlog::getInstance()->error(sprintf("Error during customer login process : %s. Exception was %s", $message, $e->getMessage()));

                $customerLoginForm->setErrorMessage($message);

                $this->getParserContext()->addForm($customerLoginForm);
            }
        }
    }

    /**
     * Perform customer logout.
     */
    public function logoutAction()
    {
        if ($this->getSecurityContext()->hasCustomerUser()) {
            $this->dispatch(TheliaEvents::CUSTOMER_LOGOUT);
        }

        // Redirect to home page
        $this->redirect(URL::getInstance()->getIndexPage());
    }

    /**
     * Dispatch event for customer login action
     *
     * @param Customer $customer
     */
    protected function processLogin(Customer $customer)
    {
        $this->dispatch(TheliaEvents::CUSTOMER_LOGIN, new CustomerLoginEvent($customer));
    }

    /**
     * @param $data
     * @return \Thelia\Core\Event\Customer\CustomerCreateOrUpdateEvent
     */
    private function createEventInstance($data)
    {
        $customerCreateEvent = new CustomerCreateOrUpdateEvent(
            $data["title"],
            $data["firstname"],
            $data["lastname"],
            isset($data["address1"])?$data["address1"]:null,
            isset($data["address2"])?$data["address2"]:null,
            isset($data["address3"])?$data["address3"]:null,
            isset($data["phone"])?$data["phone"]:null,
            isset($data["cellphone"])?$data["cellphone"]:null,
            isset($data["zipcode"])?$data["zipcode"]:null,
            isset($data["city"])?$data["city"]:null,
            isset($data["country"])?$data["country"]:null,
            isset($data["email"])?$data["email"]:null,
            isset($data["password"]) ? $data["password"]:null,
            $this->getRequest()->getSession()->getLang()->getId(),
            isset($data["reseller"])?$data["reseller"]:null,
            isset($data["sponsor"])?$data["sponsor"]:null,
            isset($data["discount"])?$data["discount"]:null,
            isset($data["company"])?$data["company"]:null
        );

        return $customerCreateEvent;
    }
}
