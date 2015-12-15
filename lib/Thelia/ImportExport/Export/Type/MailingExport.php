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

namespace Thelia\ImportExport\Export\Type;

use Thelia\Core\Translation\Translator;
use Thelia\ImportExport\Export\AbstractExport;
use Thelia\Model\NewsletterQuery;

/**
 * Class MailingExport
 * @author Benjamin Perche <bperche@openstudio.fr>
 * @author Jérôme Billiras <jbilliras@openstudio.fr>
 */
class MailingExport extends AbstractExport
{
    const FILE_NAME = 'mailing';

    const EXPORT_IMAGE = true;

    protected $orderAndAliases = [
        'Id' => 'Identifiant',
        'Email' => 'Email',
        'Fistname' => 'Prénom',
        'Lastname' => 'Nom'
    ];

    protected $imagesPaths = [
        THELIA_LOCAL_DIR . 'media/images'
    ];

    protected function getData()
    {
        if ($this->language !== null) {
            Translator::getInstance()->setLocale($this->language->getLocale());
            foreach ($this->orderAndAliases as &$alias) {
                $alias = Translator::getInstance()->trans($alias);
            }
        }

        return new NewsletterQuery;
    }
}
