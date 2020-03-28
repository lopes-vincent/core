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

namespace Thelia\ImportExport\Export;

use SplFileObject;

/**
 * Class JsonFileAbstractExport
 * @package Thelia\ImportExport\Export
 * @author Florian Bernard <fbernard@openstudio.fr>
 */
abstract class JsonFileAbstractExport extends AbstractExport
{
    /**
     * @var SplFileObject Data to export
     */
    private $data;

    public function current()
    {
        /** @var resource $file */
        $result = json_decode($this->data->current(), true);

        if ($result !== null) {
            return $result;
        }

        return [];
    }

    public function key()
    {
        return $this->data->key();
    }

    public function next()
    {
        $this->data->next();
    }

    public function rewind()
    {
        if ($this->data === null) {
            $data = $this->getData();

            // Check if $data is a path to a json file
            if (\is_string($data)
                && '.json' === substr($data, -5)
                && file_exists($data)
            ) {
                $this->data = new \SplFileObject($data, 'r');
                $this->data->setFlags(SPLFileObject::READ_AHEAD);

                $this->data->rewind();

                return;
            }

            throw new \DomainException(
                'Data shoul de a JSON file, ending with .json'
            );
        }

        throw new \LogicException('Export data can\'t be rewinded');
    }

    public function valid()
    {
        return $this->data->valid();
    }

    /**
     * Apply order and aliases on data
     *
     * @param array $data Raw data
     *
     * @return array Ordered and aliased data
     */
    public function applyOrderAndAliases(array $data)
    {
        if ($this->orderAndAliases === null) {
            return $data;
        }

        $processedData = [];

        foreach ($this->orderAndAliases as $key => $value) {
            if (\is_int($key)) {
                $fieldName = $value;
                $fieldAlias = $value;
            } else {
                $fieldName = $key;
                $fieldAlias = $value;
            }

            $processedData[$fieldAlias] = null;
            if (array_key_exists($fieldName, $data)) {
                $processedData[$fieldAlias] = $data[$fieldName];
            }
        }

        return $processedData;
    }
}
