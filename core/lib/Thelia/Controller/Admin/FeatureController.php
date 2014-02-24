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

namespace Thelia\Controller\Admin;

use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Event\Feature\FeatureDeleteEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\Feature\FeatureUpdateEvent;
use Thelia\Core\Event\Feature\FeatureCreateEvent;
use Thelia\Core\Security\AccessManager;
use Thelia\Model\FeatureQuery;
use Thelia\Form\FeatureModificationForm;
use Thelia\Form\FeatureCreationForm;
use Thelia\Core\Event\UpdatePositionEvent;

use Thelia\Core\Event\Feature\FeatureAvUpdateEvent;
use Thelia\Core\Event\Feature\FeatureEvent;

/**
 * Manages features
 *
 * @author Franck Allimant <franck@cqfdev.fr>
 */
class FeatureController extends AbstractCrudController
{
    public function __construct()
    {
        parent::__construct(
            'feature',
            'manual',
            'order',

            AdminResources::FEATURE,

            TheliaEvents::FEATURE_CREATE,
            TheliaEvents::FEATURE_UPDATE,
            TheliaEvents::FEATURE_DELETE,
            null, // No visibility toggle
            TheliaEvents::FEATURE_UPDATE_POSITION
        );
    }

    protected function getCreationForm()
    {
        return new FeatureCreationForm($this->getRequest());
    }

    protected function getUpdateForm()
    {
        return new FeatureModificationForm($this->getRequest());
    }

    protected function getCreationEvent($formData)
    {
        $createEvent = new FeatureCreateEvent();

        $createEvent
            ->setTitle($formData['title'])
            ->setLocale($formData["locale"])
            ->setAddToAllTemplates($formData['add_to_all'])
        ;

        return $createEvent;
    }

    protected function getUpdateEvent($formData)
    {
        $changeEvent = new FeatureUpdateEvent($formData['id']);

        // Create and dispatch the change event
        $changeEvent
            ->setLocale($formData["locale"])
            ->setTitle($formData['title'])
            ->setChapo($formData['chapo'])
            ->setDescription($formData['description'])
            ->setPostscriptum($formData['postscriptum'])
        ;

        return $changeEvent;
    }

    /**
     * Process the features values (fix it in future version to integrate it in the feature form as a collection)
     *
     * @see \Thelia\Controller\Admin\AbstractCrudController::performAdditionalUpdateAction()
     */
    protected function performAdditionalUpdateAction($updateEvent)
    {
        $attr_values = $this->getRequest()->get('feature_values', null);

        if ($attr_values !== null) {

            foreach ($attr_values as $id => $value) {

                $event = new FeatureAvUpdateEvent($id);

                $event->setTitle($value);
                $event->setLocale($this->getCurrentEditionLocale());

                $this->dispatch(TheliaEvents::FEATURE_AV_UPDATE, $event);
            }
        }

        return null;
    }

    protected function createUpdatePositionEvent($positionChangeMode, $positionValue)
    {
        return new UpdatePositionEvent(
                $this->getRequest()->get('feature_id', null),
                $positionChangeMode,
                $positionValue
        );
    }

    protected function getDeleteEvent()
    {
        return new FeatureDeleteEvent($this->getRequest()->get('feature_id'));
    }

    protected function eventContainsObject($event)
    {
        return $event->hasFeature();
    }

    protected function hydrateObjectForm($object)
    {

        $data = array(
            'id'           => $object->getId(),
            'locale'       => $object->getLocale(),
            'title'        => $object->getTitle(),
            'chapo'        => $object->getChapo(),
            'description'  => $object->getDescription(),
            'postscriptum' => $object->getPostscriptum()
        );

        // Setup the object form
        return new FeatureModificationForm($this->getRequest(), "form", $data);
    }

    protected function getObjectFromEvent($event)
    {
        return $event->hasFeature() ? $event->getFeature() : null;
    }

    protected function getExistingObject()
    {
        $feature = FeatureQuery::create()
        ->findOneById($this->getRequest()->get('feature_id', 0));

        if (null !== $feature) {
            $feature->setLocale($this->getCurrentEditionLocale());
        }

        return $feature;
    }

    protected function getObjectLabel($object)
    {
        return $object->getTitle();
    }

    protected function getObjectId($object)
    {
        return $object->getId();
    }

    protected function renderListTemplate($currentOrder)
    {
        return $this->render('features', array('order' => $currentOrder));
    }

    protected function renderEditionTemplate()
    {
        return $this->render(
                'feature-edit',
                array(
                        'feature_id' => $this->getRequest()->get('feature_id'),
                        'featureav_order' => $this->getFeatureAvListOrder()
                )
        );
    }

    protected function redirectToEditionTemplate()
    {
        $this->redirectToRoute(
                "admin.configuration.features.update",
                array(
                        'feature_id' => $this->getRequest()->get('feature_id'),
                        'featureav_order' => $this->getFeatureAvListOrder()
                )
        );
    }

    protected function redirectToListTemplate()
    {
        $this->redirectToRoute('admin.configuration.features.default');
    }

    /**
     * Get the Feature value list order.
     *
     * @return string the current list order
     */
    protected function getFeatureAvListOrder()
    {
        return $this->getListOrderFromSession(
                'featureav',
                'featureav_order',
                'manual'
        );
    }

    /**
     * Add or Remove from all product templates
     */
    protected function addRemoveFromAllTemplates($eventType)
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth($this->resourceCode, array(), AccessManager::UPDATE)) return $response;

        try {
            if (null !== $object = $this->getExistingObject()) {

                $event = new FeatureEvent($object);

                $this->dispatch($eventType, $event);
            }
        } catch (\Exception $ex) {
            // Any error
            return $this->errorPage($ex);
        }

        $this->redirectToListTemplate();
    }

    /**
     * Remove from all product templates
     */
    public function removeFromAllTemplates()
    {
        return $this->addRemoveFromAllTemplates(TheliaEvents::FEATURE_REMOVE_FROM_ALL_TEMPLATES);
    }

    /**
     * Add to all product templates
     */
    public function addToAllTemplates()
    {
        return $this->addRemoveFromAllTemplates(TheliaEvents::FEATURE_ADD_TO_ALL_TEMPLATES);
    }
}