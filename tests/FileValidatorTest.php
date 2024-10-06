<?php

use PHPUnit\Framework\TestCase;
use Lib\Validators\FileValidator;
use Lib\Validators\FinfoWrapper;

class FileValidatorTest extends TestCase
{
    const TEXTPLAIN = 'text/plain';
    const TMPNAME = '/tmp/phpYzdqkD';

    protected function setUp(): void
    {
        $_FILES = [];
    }

    public function testValidateFile()
    {
        $data = [
            'test_file' => [
                'name' => 'test.txt',
                'type' => self::TEXTPLAIN,
                'tmp_name' => self::TMPNAME,
                'error' => UPLOAD_ERR_OK,
                'size' => 123
            ]
        ];

        $validator = new FileValidator($data);
        $validator->validateFile('test_file');
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para un archivo válido.');

        $data['test_file']['error'] = UPLOAD_ERR_NO_FILE;
        $validator = new FileValidator($data);
        $validator->validateFile('test_file');
        $this->assertNotEmpty($validator->getErrors(), 'La validación debería fallar para un archivo faltante.');
    }

    public function testValidateFileSize()
    {
        $data = [
            'test_file' => [
                'name' => 'test.txt',
                'type' => self::TEXTPLAIN,
                'tmp_name' => self::TMPNAME,
                'error' => UPLOAD_ERR_OK,
                'size' => 123
            ]
        ];

        $validator = new FileValidator($data);
        $validator->validateFileSize('test_file', 200);
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para un archivo dentro del límite de tamaño.');

        $validator->validateFileSize('test_file', 100);
        $this->assertNotEmpty($validator->getErrors(), 'La validación debería fallar para un archivo que excede el límite de tamaño.');
    }

    public function testValidateMimes()
    {
        $data = [
            'test_file' => [
                'name' => 'test.txt',
                'type' => self::TEXTPLAIN,
                'tmp_name' => self::TMPNAME,
                'error' => UPLOAD_ERR_OK,
                'size' => 123
            ]
        ];

        $finfoWrapper = $this->createMock(FinfoWrapper::class);
        $finfoWrapper->method('file')->willReturn('text/plain');

        $validator = new FileValidator($data, [], [], $finfoWrapper);
        $validator->validateMimes('test_file', 'text/plain,application/pdf');
        $this->assertEmpty($validator->getErrors(), 'La validación debería pasar para un archivo con tipo MIME permitido.');

        $finfoWrapper = $this->createMock(FinfoWrapper::class);
        $finfoWrapper->method('file')->willReturn('application/pdf');

        $validator = new FileValidator($data, [], [], $finfoWrapper);
        $validator->validateMimes('test_file', 'text/plain,image/jpeg');
        $this->assertNotEmpty($validator->getErrors(), 'La validación debería fallar para un archivo con tipo MIME no permitido.');
    }

    public function testCustomErrorMessages()
    {
        $data = [
            'test_file' => [
                'name' => 'test.txt',
                'type' => self::TEXTPLAIN,
                'tmp_name' => self::TMPNAME,
                'error' => UPLOAD_ERR_OK,
                'size' => 3000000 // 3MB
            ]
        ];

        $messages = [
            'test_file.fileSize' => 'El {field} no debe ser mayor a {size} MB'
        ];

        $attributes = [
            'test_file' => 'Archivo de prueba'
        ];

        $validator = new FileValidator($data, $messages, $attributes);
        $validator->validateFileSize('test_file', 2000000); // 2MB limit
        $errors = $validator->getErrors();

        $this->assertNotEmpty($errors);
        $this->assertStringStartsWith('El Archivo de prueba no debe ser mayor a', $errors['test_file'][0]);
        $this->assertStringEndsWith('MB', $errors['test_file'][0]);

        // Verificar que el tamaño en el mensaje está cerca de 2 MB
        preg_match('/(\d+(\.\d+)?)/', $errors['test_file'][0], $matches);
        $sizeInMessage = floatval($matches[1]);
        $this->assertGreaterThan(1.9, $sizeInMessage);
        $this->assertLessThan(2.1, $sizeInMessage);
    }
}
