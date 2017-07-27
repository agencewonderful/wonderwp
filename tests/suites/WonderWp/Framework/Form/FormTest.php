<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 26/07/2017
 * Time: 17:01
 */

namespace WonderWp\Framework\Form;

use WonderWp\Framework\Form\Field\FieldGroup;
use WonderWp\Framework\Form\Field\InputField;

class FormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Form
     */
    private $form;

    public function setUp()
    {
        $this->form = new Form();
    }

    public function testConstructorShouldReturnFormInstance()
    {
        $this->assertInstanceOf(FormInterface::class, $this->form);
    }

    public function testSetNameShouldSet()
    {
        $name = 'testName';
        $this->form->setName($name);
        $this->assertEquals($name, $this->form->getName());
    }

    public function testAddFieldShouldAdd()
    {
        $this->form->setFields([]);
        $this->assertEmpty($this->form->getFields());
        $this->form->addField(new InputField('test', null));
        $this->assertArrayHasKey('test', $this->form->getFields());
    }

    public function testGetFieldShouldGet()
    {
        $field = new InputField('test4', null);
        $this->form->addField($field);
        $this->assertEquals($field, $this->form->getField('test4'));
    }

    public function testAddGroupShouldAdd()
    {
        $this->form->addGroup(new FormGroup('testGroup', 'test group title'));
        $this->assertArrayHasKey('testGroup', $this->form->getGroups());
    }

    public function testGetGroupShouldGet()
    {
        $group = new FormGroup('testGroup2', 'test group title');
        $this->form->addGroup($group);
        $this->assertEquals($group, $this->form->getGroup('testGroup2'));
    }

    public function testRemoveGroupShouldRemove()
    {
        $this->form->setGroups([]);
        $this->assertEmpty($this->form->getGroups());
        $this->form->addGroup(new FormGroup('testGroup4', 'test group title'));
        $this->assertArrayHasKey('testGroup4', $this->form->getGroups());
        $this->form->removeGroup('testGroup4');
        $this->assertArrayNotHasKey('testGroup4', $this->form->getGroups());
    }

    public function testAddFieldInGroupShouldAdd()
    {
        $this->form->addGroup(new FormGroup('testGroup3', 'test group title'));
        $this->form->addField(new InputField('test2', null), 'testGroup3');
        $this->assertArrayHasKey('test2', $this->form->getGroup('testGroup3')->getFields());
    }

    public function testFillShouldFill()
    {
        $this->form->setGroups([]);
        $this->form->setFields([]);

        //Add test group
        $group = new FormGroup('testGroup3', 'test group title');
        $field = new InputField('test', null);
        $group->setFields(['test' => $field]);
        $this->form->addGroup($group);

        //Add test field
        $this->form->addField(new InputField('test2', null));

        $data = ['test' => 'tesVal1', 'test2' => 'testVal2'];

        $this->form->fill($data);

        $this->assertEquals($data['test'], $this->form->getGroup('testGroup3')->getField('test')->getValue());
        $this->assertEquals($data['test2'], $this->form->getField('test2')->getValue());
    }

    public function testGetViewShouldreturnInstanceOfFormView()
    {
        $this->assertInstanceOf(FormViewInterface::class, $this->form->getView());
    }

    public function testRenderViewShouldReturnHtml()
    {
        $markup = $this->form->renderView();
        $this->assertNotEmpty($markup);
    }

}
