<?php

namespace Thelia\Model;

use Thelia\Model\Base\Content as BaseContent;
use Thelia\Tools\URL;
use Propel\Runtime\Connection\ConnectionInterface;

class Content extends BaseContent
{
    use \Thelia\Model\Tools\ModelEventDispatcherTrait;

    use \Thelia\Model\Tools\PositionManagementTrait;

    use \Thelia\Model\Tools\UrlRewritingTrait;

    /**
     * {@inheritDoc}
     */
    protected function getRewritenUrlViewName() {
        return 'content';
    }

    /**
     * Calculate next position relative to our parent
     */
    protected function addCriteriaToPositionQuery($query) {

        // TODO: Find the default folder for this content,
        // and generate the position relative to this folder
    }

    /**
     * {@inheritDoc}
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        $this->setPosition($this->getNextPosition());

        $this->generateRewritenUrl($this->getLocale());

        return true;
    }
}
