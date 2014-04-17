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

namespace Thelia\Core\Event\Lang;

/**
 * Class LangCreateEvent
 * @package Thelia\Core\Event\Lang
 * @author Manuel Raynaud <mraynaud@openstudio.fr>
 */
class LangCreateEvent extends LangEvent
{
    protected $title;
    protected $code;
    protected $locale;
    protected $date_format;
    protected $time_format;

    /**
     * @param mixed $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $date_format
     *
     * @return $this
     */
    public function setDateFormat($date_format)
    {
        $this->date_format = $date_format;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateFormat()
    {
        return $this->date_format;
    }

    /**
     * @param mixed $locale
     *
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $time_format
     *
     * @return $this
     */
    public function setTimeFormat($time_format)
    {
        $this->time_format = $time_format;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimeFormat()
    {
        return $this->time_format;
    }

    /**
     * @param mixed $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

}
