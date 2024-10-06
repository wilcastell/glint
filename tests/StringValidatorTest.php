<?php

use PHPUnit\Framework\TestCase;
use Lib\Validators\StringValidator;


class StringValidatorTest extends TestCase
{

    public function testValidationGt()
    {
        $data = ['age' => 20];
        $validator = new StringValidator($data);
        $validator->validateGt('age', 18);
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para edad mayor que 18.');
    }


    public function testValidationLt()
    {
        $data = ['age' => 15];
        $validator = new StringValidator($data);
        $validator->validateLt('age', 18);
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para edad menor que 18.');
    }


    public function testValidationGte()
    {
        $data = ['age' => 18];
        $validator = new StringValidator($data);
        $validator->validateGte('age', 18);
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para edad mayor o igual a 18.');
    }


    public function testValidationLte()
    {
        $data = ['age' => 18];
        $validator = new StringValidator($data);
        $validator->validateLte('age', 18);
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para edad menor o igual a 18.');
    }


    public function testValidationConfirmed()
    {
        $data = ['password' => 'secret', 'password_confirmation' => 'secret'];
        $validator = new StringValidator($data);
        $validator->validateConfirmed('password', null);
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para contraseña confirmada.');
    }


    public function testValidationInteger()
    {
        $data = ['age' => 25];
        $validator = new StringValidator($data);
        $validator->validateInteger('age');
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para edad entera.');
    }


    public function testValidationPast()
    {
        $data = ['date' => '2023-01-01'];
        $validator = new StringValidator($data);
        $validator->validatePast('date', null);
        $this->assertNotEmpty($validator->getErrors(), 'La validación debería fallar para una fecha pasada.');
    }


    public function testValidationTimepicker()
    {
        $data = ['start_time' => '09:00', 'end_time' => '17:00'];
        $validator = new StringValidator($data);
        $validator->validateTimepicker('start_time', 'end_time');
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para un timepicker válido.');
    }


    public function testValidationDistinct()
    {
        $data = ['field1' => 'value1', 'field2' => 'value2'];
        $validator = new StringValidator($data);
        $validator->validateDistinct('field1', 'field2');
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para campos distintos.');
    }
}
