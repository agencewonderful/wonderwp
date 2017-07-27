<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 27/07/2017
 * Time: 09:52
 */

namespace WonderWp\Framework\Form;

class FormGroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormGroup
     */
    private $formGroup;

    public function setUp()
    {
        $this->formGroup = new FormGroup('testName', 'testTitle');
    }

    public function testConstructorShouldReturnFormGroupInstance()
    {
        $this->assertInstanceOf(FormGroup::class, $this->formGroup);
    }

    public function testSetNameShouldSet()
    {
        $name = 'testName';
        $this->formGroup->setName($name);
        $this->assertEquals($name, $this->formGroup->getName());
    }

    public function testSetTitleShouldSet()
    {
        $title = 'testTitle';
        $this->formGroup->setTitle($title);
        $this->assertEquals($title, $this->formGroup->getTitle());
    }

    public function testSetDisplayRulesShouldSet()
    {
        $displayRules = ['class' => 'test'];
        $this->formGroup->setDisplayRules($displayRules);
        $this->assertEquals($displayRules, $this->formGroup->getDisplayRules());
    }
}
