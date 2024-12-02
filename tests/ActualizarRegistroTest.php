<?php
// tests/CalculadoraTest.php

use PHPUnit\Framework\TestCase;
use Model\Registro;
use Dotenv\Dotenv;
use Exceptions\InvalidCredentialException;
use Exceptions\InvalidDataException;
use Exceptions\InvalidIdException;

class ActualizarRegistroTest extends TestCase
{
    protected static $db;

    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');  // Ajusta la ruta si es necesario
        $dotenv->load();
        $this::$db = mysqli_connect(
            $_ENV['DB_HOST'], 
            $_ENV['DB_USER'], 
            $_ENV['DB_PASS'], 
            $_ENV['DB_NAME'], 
            $_ENV['DB_PORT']
        );
    }
    
    public function testActualizarRegistro(){
        $registro = new Registro([
            'id' => 38,
            'paquete_id' => 2,
            'pago_id' => '6YP54551SX317731N',
            'token' => 'a3e7f336',
            'usuario_id' => 12,
            'regalo_id' => 7
        ]);

        $registro::setDB($this::$db);
        $resultado = $registro->guardar();
        $this->assertTrue($resultado);
    }

    public function testIdentificadorIncorrecto(){
        $registro = new Registro([
            'id' => '',
            'paquete_id' => 2,
            'pago_id' => '6YP54551SX317731N',
            'token' => 'a3e7f336',
            'usuario_id' => 12,
            'regalo_id' => 7
        ]);

        $registro::setDB($this::$db); 
        $this->expectException(InvalidIdException::class);
        $resultado = $registro->guardar();
    }

    public function testCredencialesIncorrectar(){
        $registro = new Registro([
            'id' => '',
            'paquete_id' => 2,
            'pago_id' => 'a',
            'token' => 'b',
            'usuario_id' => 'c',
            'regalo_id' => 'd'
        ]);

        $registro::setDB($this::$db);
        $this->expectException(InvalidCredentialException::class);
        $resultado = $registro->guardar();
    } 
}