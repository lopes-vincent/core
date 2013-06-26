<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*	email : info@thelia.net                                                          */
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

namespace Thelia\Admin\Controller;

use Symfony\Component\HttpFoundation\Response;
use Thelia\Form\AdminLogin;

class AdminController extends BaseAdminController {

    public function indexAction()
    {

        $form = $this->getLoginForm();

        $request = $this->getRequest();

        if($request->isMethod("POST")) {
            $form->bind($request);

            if($form->isValid()) {
                echo "valid"; exit;
            }
        }

        return $this->render("login.html", array(
            "form" => $form->createView()
        ));
    }

    protected function getLoginForm()
    {
        $form = $this->getFormBuilder();

        $adminLogin = new AdminLogin();

        return $adminLogin->buildForm($form, array())->getForm();
    }

    public function lostAction()
    {
        return new Response(
            $this->renderRaw("404.html"),
            404
        );
    }
}