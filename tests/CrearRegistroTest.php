<?php

use PHPUnit\Framework\TestCase;
use Model\Registro;
use Dotenv\Dotenv;
use Exceptions\InvalidDataException;
use Exceptions\InvalidTableException;

class CrearRegistroTest extends TestCase
{
    protected static $db;

    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        $this::$db = mysqli_connect(
            $_ENV['DB_HOST'], 
            $_ENV['DB_USER'], 
            $_ENV['DB_PASS'], 
            $_ENV['DB_NAME'], 
            $_ENV['DB_PORT']
        );
    }
    public function testCrearRegistro() {

        $registro = new Registro([
            'id' => 0,
            'paquete_id' => 2,
            'pago_id' => '6YP54551SX317731N',
            'token' => 'a3e7f336',
            'usuario_id' => 12,
            'regalo_id' => 8
        ]);
        $registro::setDB($this::$db);
        $resultado = $registro->crear();
        $estado = $resultado["resultado"];
        $this->assertTrue($estado); 
    }
    public function testDatosInvalidos(){
        $registro = new Registro([
            'id' => '',
            'paquete_id' => '',
            'pago_id' => '',
            'token' => '',
            'usuario_id' => '',
            'regalo_id' => ''
        ]);
        $registro::setDB($this::$db);
        $this->expectException(InvalidDataException::class);
        $registro->crear();
    }

    public function testTablaInvalida () {
        $registro = new Registro([
            'id' => '',
            'paquete_id' => 2,
            'pago_id' => '6YP54551SX317731N',
            'token' => 'a3e7f336',
            'usuario_id' => 12,
            'regalo_id' => 8
        ]);
        $registro::$tabla = '';
        $registro::setDB($this::$db);
        $this->expectException(InvalidTableException::class);
        $registro->crear();
    }
}