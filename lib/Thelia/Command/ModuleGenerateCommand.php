<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*	    email : info@thelia.net                                                      */
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
namespace Thelia\Command;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ModuleGenerateCommand extends ContainerAwareCommand {

    protected $module;
    protected $moduleDirectory;

    private $reservedKeyWords = array(
        "thelia"
    );

    private $neededDirectories = array(
        "Config",
        "Model",
        "Loop"
    );

    protected function configure()
    {
        $this
            ->setName("module:generate")
            ->setDescription("generate all needed files for creating a new Module")
            ->addArgument(
                "name" ,
                InputArgument::REQUIRED,
                "name wanted for your Module"
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->module = $this->formatModuleName($input->getArgument("name"));
        $this->moduleDirectory = THELIA_MODULE_DIR . DIRECTORY_SEPARATOR . $this->module;
        $this->verifyExistingModule();

        $this->createDirectories();
        $this->createFiles();
    }

    private function createDirectories()
    {
        $fs = new Filesystem();

        $fs->mkdir($this->moduleDirectory);

        foreach ($this->neededDirectories as $directory) {
            $fs->mkdir($this->moduleDirectory . DIRECTORY_SEPARATOR . $directory);
        }

    }

    private function createFiles()
    {
        $fs = new Filesystem();
        $skeletonDir = str_replace("/", DIRECTORY_SEPARATOR, THELIA_ROOT . "/core/lib/Thelia/Command/Skeleton/Module/");
        $fs->copy($skeletonDir . "config.xml", $this->moduleDirectory . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR . "config.xml");
        $fs->copy($skeletonDir . "plugin.xml", $this->moduleDirectory . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR . "plugin.xml");

        $classContent = file_get_contents($skeletonDir . "Class.php");

        $classContent = str_replace("%%CLASSNAME%%", $this->module, $classContent);
        $classContent = str_replace("%%NAMESPACE%%", $this->module, $classContent);

        file_put_contents($this->moduleDirectory . DIRECTORY_SEPARATOR . $this->module.".php", $classContent);

        $schemaContent = file_get_contents($skeletonDir . "schema.xml");

        $schemaContent = str_replace("%%CONFIG_DIR%%", THELIA_CONF_DIR, $schemaContent);

        file_put_contents($this->moduleDirectory . DIRECTORY_SEPARATOR . "Config". DIRECTORY_SEPARATOR . "schema.xml", $schemaContent);
    }


    private function verifyExistingModule()
    {
        if (file_exists($this->moduleDirectory)) {
            throw new \RuntimeException(sprintf("%s module already exists", $this->module));
        }
    }

    private function formatModuleName($name)
    {
        if (in_array(strtolower($name), $this->reservedKeyWords)) {
            throw new \RuntimeException(sprintf("%s module name is a reserved keyword", $name));
        }
        return ucfirst($name);
    }
}