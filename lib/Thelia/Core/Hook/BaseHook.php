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

namespace Thelia\Core\Hook;

use Symfony\Component\Translation\TranslatorInterface;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Template\Assets\AssetManagerInterface;
use Thelia\Core\Template\Smarty\SmartyParser;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Model\Cart;
use Thelia\Model\ConfigQuery;
use Thelia\Model\Currency;
use Thelia\Model\Customer;
use Thelia\Model\Lang;
use Thelia\Model\Order;
use Thelia\Module\BaseModule;
use Thelia\Tools\URL;

/**
 * The base class for hook. If you provide hooks in your module you have to extends
 * this class.
 *
 * These class provides some helper functions to retrieve object from the current session
 * of the current user. It also provides a render function that allows you to get the right
 * template file from different locations and allows you to override templates in your current
 * template.
 *
 * Class BaseHook
 * @package Thelia\Core\Hook
 * @author  Julien Chanséaume <jchanseaume@openstudio.fr>
 */
abstract class BaseHook
{
    /**
     * @var BaseModule
     */
    public $module = null;

    /**
     * @var SmartyParser
     */
    public $parser = null;

    /** @var TranslatorInterface $translator */
    public $translator = null;

    /** @var AssetManagerInterface $assetManager */
    public $assetsManager = null;

    /** @var Request $request */
    protected $request = null;

    /** @var Session $session */
    protected $session = null;

    /** @var Customer $customer */
    protected $customer = null;

    /** @var Cart $cart */
    protected $cart = null;

    /** @var Order $order */
    protected $order = null;

    /** @var Lang $lang */
    protected $lang = null;

    /** @var Currency $currency */
    protected $currency = null;

    /**
     * helper function allowing you to render a template using smarty
     *
     * @param string $templateName the template path of the template
     * @param array  $parameters   an array of parameters to assign to smarty
     *
     * @return string the content generated by smarty
     */
    public function render($templateName, array $parameters = array())
    {
        $templateDir = $this->resolveSourcePath($templateName);

        $content = "";
        if (null !== $templateDir) {
            // retrieve the template
            $smartyParser = $this->parser;
            $content      = $smartyParser->render($templateDir . DS . $templateName, $parameters);
        } else {
            $content = sprintf("ERR: Unknown template %s for module %s", $templateName, $this->module->getCode());
        }

        return $content;
    }

    /**
     * helper function allowing you to get the content of a file
     *
     * @param string $fileName the template path of the template
     *
     * @return string the content of the file
     */
    public function dump($fileName)
    {
        $fileDir = $this->resolveSourcePath($fileName);

        $content = "";
        if (null !== $fileDir) {
            $content = file_get_contents($fileDir . DS . $fileName);
            if (false === $content) {
                $content = "";
            }
        } else {
            $content = sprintf("ERR: Unknown file %s for module %s", $fileName, $this->module->getCode());
        }

        return $content;
    }

    /**
     * helper function allowing you to generate the HTML link tag
     *
     * @param string $fileName   the path to the css file
     * @param array  $attributes the attributes of the tag
     *
     * @return string the link tag
     */
    public function addCSS($fileName, $attributes = array())
    {
        $tag = "";

        $url = $this->resolveURL($fileName, "css");
        if ("" !== $url) {
            $tags   = array();
            $tags[] = "<link rel='stylesheet' type='text/css' ";
            $tags[] = " href='" . $url . "' ";
            foreach ($attributes as $name => $val) {
                if (is_string($name) && !in_array($name, array("href", "rel", "type"))) {
                    $tags[] = $name . "='" . $val . "' ";
                }
            }
            $tags[] = "/>";
            $tag    = implode($tags);
        }

        return $tag;
    }

    /**
     * helper function allowing you to generate the HTML script tag
     *
     * @param string $fileName   the path to the js file
     * @param array  $attributes the attributes of the tag
     *
     * @return string the script tag
     */
    public function addJS($fileName, $attributes = array())
    {
        $tag = "";

        $url = $this->resolveURL($fileName, "js");
        if ("" !== $url) {
            $tags   = array();
            $tags[] = "<script type='text/javascript' ";
            $tags[] = " src='" . $url . "' ";
            foreach ($attributes as $name => $val) {
                if (is_string($name) && !in_array($name, array("src", "type"))) {
                    $tags[] = $name . "='" . $val . "' ";
                }
            }
            $tags[] = "></script>";
            $tag    = implode($tags);
        }

        return $tag;
    }

    protected function resolveURL($fileName, $type)
    {
        $url = "";

        $fileRoot = $this->resolveSourcePath($fileName);
        $fileDir  = dirname($fileName);
        if ("." == $fileDir) {
            $fileDir = "";
        }

        $asset_dir_from_web_root = ConfigQuery::read('asset_dir_from_web_root', 'assets');

        if (null !== $fileDir) {
            $url = $this->assetsManager->processAsset(
                $fileRoot . DS . $fileName,
                $fileRoot,
                THELIA_WEB_DIR . $asset_dir_from_web_root,
                $fileDir,
                $this->module->getCode(),
                URL::getInstance()->absoluteUrl($asset_dir_from_web_root, null, URL::PATH_TO_FILE /* path only */),
                $type,
                array(),
                false
            );
        }

        return $url;
    }

    /**
     * Resolve the file path.
     *
     * A system of fallback enables file overriding. It will look for the template :
     *      - in the current template in directory /modules/{module code}/
     *      - in the module in the current template if it exists
     *      - in the module in the default template
     *
     * @param  $fileName    the filename
     *
     * @return mixed the path to directory containing the file if exists
     */
    protected function resolveSourcePath($fileName)
    {
        $fileDir = null;
        $found   = false;

        // retrieve the template
        $smartyParser = $this->parser;

        // First look into the current template in the right scope : frontOffice, backOffice, ...
        // template should be overrided in : {template_path}/modules/{module_code}/{template_name}
        /** @var \Thelia\Core\Template\Smarty\SmartyParser $templateDefinition */
        $templateDefinition  = $smartyParser->getTemplateDefinition(false);
        $templateDirectories = $smartyParser->getTemplateDirectories($templateDefinition->getType());

        if (isset($templateDirectories[$templateDefinition->getName()]["0"])) {
            $fileDir = $templateDirectories[$templateDefinition->getName()]["0"]
                . DS . TemplateDefinition::HOOK_OVERRIDE_SUBDIR
                . DS . $this->module->getCode();
            if (file_exists($fileDir . DS . $fileName)) {
                $found = true;
            }
        }

        // If the smarty template doesn't exist, we try to see if there is an
        // implementation for the template used in the module directory
        if (!$found) {
            if (isset($templateDirectories[$templateDefinition->getName()][$this->module->getCode()])) {
                $fileDir = $templateDirectories[$templateDefinition->getName()][$this->module->getCode()];
                if (file_exists($fileDir . DS . $fileName)) {
                    $found = true;
                }
            }
        }

        // Not here, we finally try to fallback on the default theme in the module
        if (!$found && $templateDefinition->getName() !== TemplateDefinition::HOOK_DEFAULT_THEME) {
            if ($templateDirectories[TemplateDefinition::HOOK_DEFAULT_THEME]
                && isset($templateDirectories[TemplateDefinition::HOOK_DEFAULT_THEME][$this->module->getCode()])
            ) {
                $fileDir = $templateDirectories[TemplateDefinition::HOOK_DEFAULT_THEME][$this->module->getCode()];
                if (file_exists($fileDir . DS . $fileName)) {
                    $found = true;
                }
            }
        }

        return $fileDir;
    }

    /**
     * @param \Thelia\Module\BaseModule $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return \Thelia\Module\BaseModule
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param \Thelia\Core\Template\Smarty\SmartyParser $parser
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return \Thelia\Core\Template\Smarty\SmartyParser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * Translates the given message.
     *
     * @param string $id         The message id (may also be an object that can be cast to string)
     * @param array  $parameters An array of parameters for the message
     * @param string $domain     The domain for the message
     * @param string $locale     The locale
     *
     * @return string The translated string
     *
     * @api
     */
    protected function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * get the request
     *
     * @return Request
     */
    protected function getRequest()
    {
        if (null === $this->request) {
            $this->request = $this->getParser()->getRequest();
        }

        return $this->request;
    }

    /**
     * get the session
     *
     * @return Session
     */
    protected function getSession()
    {
        if (null === $this->session) {
            if (null !== $this->getRequest()) {
                $this->session = $this->request->getSession();
            }
        }

        return $this->session;
    }

    /**
     * Get the view argument for the request.
     *
     * It allows you to identify the page currently displayed. eg: index, category, ...
     *
     * @return string the current view
     */
    protected function getView()
    {
        $ret = "";
        if (null !== $this->getRequest()) {
            $ret = $this->getRequest()->attributes->get("_view", "");
        }

        return $ret;
    }

    /**
     * Get the cart from the session
     *
     * @return \Thelia\Model\Cart|null
     */
    protected function getCart()
    {
        if (null === $this->cart) {
            $this->cart = $this->getSession() ? $this->getSession()->getCart() : null;
        }

        return $this->cart;
    }

    /**
     * Get the order from the session
     *
     * @return \Thelia\Model\Order|null
     */
    protected function getOrder()
    {
        if (null === $this->order) {
            $this->cart = $this->getSession() ? $this->getSession()->getOrder() : null;
        }

        return $this->cart;
    }

    /**
     * Get the current currency used or if not present the default currency for the shop
     *
     * @return \Thelia\Model\Currency
     */
    protected function getCurrency()
    {
        if (null === $this->currency) {
            $this->currency = $this->getSession() ? $this->getSession()->getCurrency(true) : Currency::getDefaultCurrency();
        }

        return $this->currency;
    }

    /**
     * Get the current customer logged in. If no customer is logged return null
     *
     * @return \Thelia\Model\Customer|null
     */
    protected function getCustomer()
    {
        if (null === $this->customer) {
            $this->customer = $this->getSession() ? $this->getSession()->getCustomerUser() : null;
        }

        return $this->customer;
    }

    /**
     * Get the current lang used or if not present the default lang for the shop
     *
     * @return \Thelia\Model\Lang
     */
    protected function getLang()
    {
        if (null === $this->lang) {
            $this->lang = $this->getSession() ? $this->getSession()->getLang(true) : $this->lang = Lang::getDefaultLanguage();
        }

        return $this->lang;
    }

}
