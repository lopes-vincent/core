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
namespace Thelia\Form;

use Symfony\Component\Validator\Constraints\NotBlank;

class ProductCreationForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add("ref", "text", array(
                "constraints" => array(
                    new NotBlank()
                ),
                "label" => "Product reference *",
                "label_attr" => array(
                    "for" => "ref"
                )
            ))
            ->add("title", "text", array(
                "constraints" => array(
                    new NotBlank()
                ),
                "label" => "Product title *",
                "label_attr" => array(
                    "for" => "title"
                )
            ))
            ->add("default_category", "integer", array(
                "constraints" => array(
                    new NotBlank()
                )
            ))
            ->add("locale", "text", array(
                "constraints" => array(
                    new NotBlank()
                )
            ))
            ->add("visible", "integer", array(
                "label" => Translator::getInstance()->trans("This product is online."),
                "label_attr" => array("for" => "visible_create")
            ))
            ;
    }

    public function getName()
    {
        return "thelia_product_creation";
    }
}
