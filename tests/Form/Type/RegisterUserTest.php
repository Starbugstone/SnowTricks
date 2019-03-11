<?php

namespace App\Tests\Form\Type;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterUserTest extends TypeTestCase{

    private $validator;
    //need to add the validator extenstions
    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        // use getMock() on PHPUnit 5.3 or below
        // $this->validator = $this->getMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return [
            new ValidatorExtension($this->validator),
        ];
    }

    public function testSubmitValidData(){

        $formData = [
            'userName' => 'testUniqueUser',
            'email' => 'testUniqueUser@localhost.dev',
        ];

        $objectToCompare = new User();

        $form = $this->factory->create(RegistrationFormType::class, $objectToCompare);

        $object = new User();
        $object
            ->setUserName('testUniqueUser')
            ->setEmail('testUniqueUser@localhost.dev')
            ;

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());


        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

    }
    
}