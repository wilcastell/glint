<?php

use PHPUnit\Framework\TestCase;
use Lib\Validator;

class ValidatorTest extends TestCase
{
    const NAME = 'John Doe';
    const EMAIL = 'john@email.com';
    const RQE = 'required|email';
    const RQA = 'required|number|lte:18';

    public function testValidationPasses()
    {
        $data = [
            'name' => self::NAME,
            'email' => self::EMAIL,
            'age' => 21
        ];

        $rules = [
            'name' => 'required',
            'email' => self::RQE,
            'age' => 'required|number|gt:18',
        ];

        $validator = new Validator($data, $rules);

        $this->assertFalse($validator->fails(), 'La validación debe pasar para datos válidos.');
    }
    public function testValidationGt()
    {
        $data = ['age' => 20];
        $rules = ['age' => 'gt:18'];
        $validator = new Validator($data, $rules);
        $this->assertFalse($validator->fails(), 'La validación debe pasar para mayores de 18 años.');
    }

    public function testValidationLt()
    {
        $data = ['age' => 17];
        $rules = ['age' => 'lt:18'];
        $validator = new Validator($data, $rules);
        $this->assertFalse($validator->fails(), 'La validación debe aprobarse para menor a 18');
    }

    public function testValidationGte()
    {
        $data = ['age' => 18];
        $rules = ['age' => 'gte:18'];
        $validator = new Validator($data, $rules);
        $this->assertFalse($validator->fails(), 'La validación debe pasar para edad mayor o igual a 18 años.');
    }

    public function testValidationLte()
    {
        $data = ['age' => 18];
        $rules = ['age' => 'lte:18'];
        $validator = new Validator($data, $rules);
        $this->assertFalse($validator->fails(), 'La validación debe pasar si es menor o igual a 18');
    }

    public function testValidationFailsForRequired()
    {
        $data = [
            'name' => '',
            'email' => self::EMAIL,
            'age' => 25,
        ];

        $rules = [
            'name' => 'required',
            'email' => self::RQE,
            'age' => 'required|number|lte:18',
        ];

        $validator = new Validator($data, $rules);

        $this->assertTrue($validator->fails(), 'La validación debería fallar porque falta un campo obligatorio.');
        $this->assertArrayHasKey('name', $validator->errors());
    }

    public function testValidationFailsForEmail()
    {
        $data = [
            'name' => self::NAME,
            'email' => 'invalid-email',
            'age' => 25,
        ];

        $rules = [
            'name' => 'required',
            'email' => self::RQE,
            'age' => self::RQA,
        ];

        $validator = new Validator($data, $rules);

        $this->assertTrue($validator->fails(), 'La validación debería fallar por correo electrónico no válido.');
        $this->assertArrayHasKey('email', $validator->errors());
    }

    public function testValidationFailsForMin()
    {
        $data = [
            'name' => self::NAME,
            'email' => self::EMAIL,
            'age' => 19,
        ];

        $rules = [
            'name' => 'required',
            'email' => self::RQE,
            'age' => self::RQA,
        ];

        $validator = new Validator($data, $rules);

        $this->assertTrue($validator->fails(), 'La validación debería fallar para edades inferiores al mínimo.');
        $this->assertArrayHasKey('age', $validator->errors());
    }

    public function testValidationFailsForMax()
    {
        $data = [
            'name' => self::NAME,
            'email' => self::EMAIL,
            'age' => 19,
            'description' => str_repeat('a', 256),
        ];

        $rules = [
            'name' => 'required',
            'email' => self::RQE,
            'age' => self::RQA,
            'description' => 'max:255',
        ];

        $validator = new Validator($data, $rules);

        $this->assertTrue($validator->fails(), 'La validación debería fallar si la descripción excede la longitud máxima.');
        $this->assertArrayHasKey('description', $validator->errors());
    }

    public function testValidationFailsForNumber()
    {
        $data = [
            'name' => self::NAME,
            'email' => self::EMAIL,
            'age' => 'twenty-five',
        ];

        $rules = [
            'name' => 'required',
            'email' => self::RQE,
            'age' => self::RQA,
        ];

        $validator = new Validator($data, $rules);

        $this->assertTrue($validator->fails(), 'La validación debería fallar para la edad no numérica.');
        $this->assertArrayHasKey('age', $validator->errors());
    }

    public function testValidationFailsForRegex()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'username' => 'invalid username',
        ];

        $rules = [
            'name' => 'required',
            'email' => self::RQE,
            'username' => 'required|regex:/^[a-zA-Z0-9_]+$/',
        ];

        $validator = new Validator($data, $rules);

        $this->assertTrue($validator->fails(), 'La validación debería fallar si el nombre de usuario no coincide con la expresión regular.');
        $this->assertArrayHasKey('username', $validator->errors());
    }
}
