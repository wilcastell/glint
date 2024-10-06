<?php

use PHPUnit\Framework\TestCase;
use Lib\Validators\DatabaseValidator;
use Illuminate\Database\Capsule\Manager as DB;


class DatabaseValidatorTest extends TestCase
{
    protected $capsule;

    protected function setUp(): void
    {
        $this->capsule = new DB;
        $this->capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();

        // Crear tabla para pruebas
        DB::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('email')->unique();
        });

        // Insertar datos de prueba
        DB::table('users')->insert([
            ['email' => 'test@example.com'],
        ]);
    }


    public function testValidateUnique()
    {
        $data = ['email' => 'test@example.com'];
        $validator = new DatabaseValidator($data);
        $validator->validateUnique('email', 'users,email');
        $this->assertNotEmpty($validator->getErrors(), 'La validación debería fallar para un email no único.');

        $data = ['email' => 'unique@example.com'];
        $validator = new DatabaseValidator($data);
        $validator->validateUnique('email', 'users, email');
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para un email único.');
    }


    public function testValidateExists()
    {
        $data = ['email' => 'test@example.com'];
        $validator = new DatabaseValidator($data);
        $validator->validateExists('email', 'users,email');
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para un email existente.');

        $data = ['email' => 'nonexistent@example.com'];
        $validator = new DatabaseValidator($data);
        $validator->validateExists('email', 'users,email');
        $this->assertNotEmpty($validator->getErrors(), 'La validación debería fallar para un email no existente.');
    }
}
