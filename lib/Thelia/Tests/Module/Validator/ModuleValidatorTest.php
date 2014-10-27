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

namespace Thelia\Tests\Module\Validator;

use Thelia\Core\Thelia;
use Thelia\Exception\ModuleException;
use Thelia\Module\Validator\ModuleValidator;
use Thelia\Tools\Version\Version;

/**
 * Class ModuleValidator
 * @package Thelia\Tests\Module\Validator
 * @author Julien Chanséaume <jchanseaume@openstudio.fr>
 */
class ModuleValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Thelia\Exception\ModuleException
     */
    public function testCheque()
    {
        $moduleChequePath = THELIA_MODULE_DIR . "Cheque";

        $moduleValidator = new ModuleValidator($moduleChequePath);

        $moduleValidator->setTranslator($this->getStubTranslator());

        // load module
        $moduleValidator->load();

        $moduleDescriptor = $moduleValidator->getModuleDescriptor();

        $this->assertInstanceOf('SimpleXMLElement', $moduleDescriptor);
        $this->assertEquals("1", $moduleValidator->getModuleVersion());

        $moduleDefinition = $moduleValidator->getModuleDefinition();

        $this->assertInstanceOf('Thelia\Module\Validator\ModuleDefinition', $moduleDefinition);

        $this->assertEquals("Cheque", $moduleDefinition->getCode());
        $this->assertEquals("Cheque\\Cheque", $moduleDefinition->getNamespace());

        // validate
        $moduleValidator->validate();
    }

    public function testVirtualProductDelivery()
    {
        $modulePath = THELIA_MODULE_DIR . "VirtualProductDelivery";

        $moduleValidator = new ModuleValidator($modulePath);

        $moduleValidator->setTranslator($this->getStubTranslator());

        // load module
        $moduleValidator->load();

        $moduleDescriptor = $moduleValidator->getModuleDescriptor();

        $this->assertInstanceOf('SimpleXMLElement', $moduleDescriptor);
        $this->assertEquals("2", $moduleValidator->getModuleVersion());

        $moduleDefinition = $moduleValidator->getModuleDefinition();

        $this->assertInstanceOf('Thelia\Module\Validator\ModuleDefinition', $moduleDefinition);

        $this->assertEquals("VirtualProductDelivery", $moduleDefinition->getCode());
        $this->assertEquals("VirtualProductDelivery\\VirtualProductDelivery", $moduleDefinition->getNamespace());
        $this->assertEquals(2, count($moduleDefinition->getLanguages()));
        $this->assertEquals(0, count($moduleDefinition->getDependencies()));
        $this->assertEquals(1, count($moduleDefinition->getAuthors()));
        $this->assertEquals("", $moduleDefinition->getDocumentation());
        $this->assertEquals("", $moduleDefinition->getLogo());
        $this->assertEquals("2.1.0", $moduleDefinition->getTheliaVersion());
        $this->assertTrue(
            Version::test(
                Thelia::RELEASE_VERSION,
                $moduleDefinition->getTheliaVersion(),
                false,
                ">="
            )
        );

        // validate
        $moduleValidator->validate(false);
    }

    public function validatorProvider()
    {
        return [
            ['Module1', '\Thelia\Exception\ModuleException', 'The module requires a version of Thelia'],
            ['Module2', '\Thelia\Exception\ModuleException', 'The module requires this activated modules'],
            ['Module3', '\Thelia\Exception\FileNotFoundException', 'Module should have a module.xml in the Config directory'],
            ['Module4', '\Thelia\Exception\FileNotFoundException', 'Module should have a config.xml in the Config directory'],
            ['Module5', '\Thelia\Module\Exception\InvalidXmlDocumentException', null],
        ];
    }

    /**
     * @dataProvider validatorProvider
     */
    public function testValidator($path, $exceptionExpected, $exceptionMessage)
    {
        $modulePath = __DIR__ . "/" . $path;
        /** @var \Exception $exception */
        $exception = null;

        try {
            $moduleValidator = new ModuleValidator($modulePath);

            $moduleValidator->setTranslator($this->getStubTranslator("opiopi"));

            $moduleValidator->load();

            $moduleValidator->validate(true);
        } catch (\Exception $ex) {
            $exception = $ex;
        }

        if (null !== $exceptionExpected) {

            $this->assertInstanceOf(
                $exceptionExpected,
                $exception,
                $path . " module should return exception " . $exceptionExpected
            );

            if (null !== $exceptionMessage) {
                $this->assertNotEmpty(
                    $exception->getMessage(),
                    $path . " module exception should not be empty"
                );

                $this->assertTrue(
                    false !== strpos($exception->getMessage(), $exceptionMessage),
                    $path . " module exception should contain : " . $exceptionMessage
                );
            }

        } else {
            $this->assertNull(
                $exception,
                $path . " module should not return exception [" . $exception->getMessage() . ']'
            );
        }

    }

    /**
     * @expectedException \Thelia\Exception\FileNotFoundException
     */
    public function testNonExistentModule()
    {
        $moduleChuckNorrisPath = THELIA_MODULE_DIR . "ChuckNorris";

        $moduleValidator = new ModuleValidator($moduleChuckNorrisPath);

        $moduleValidator->setTranslator($this->getStubTranslator());

        // load
        $moduleValidator->load();
    }


    private function getStubTranslator()
    {
        $stubTranslator = $this->getMockBuilder('\Thelia\Core\Translation\Translator')
            ->disableOriginalConstructor()
            ->getMock();

        $stubTranslator->expects($this->any())
            ->method('trans')
            ->will(
                $this->returnCallback(
                    function($l, $p) {
                        foreach ($p as $pk => $pv){
                            $l = str_replace($pk, $pv, $l);
                        }
                        return $l;
                    }
                )
            )
        ;

        return $stubTranslator;
    }
}
