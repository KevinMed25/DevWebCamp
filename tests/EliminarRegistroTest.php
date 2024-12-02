<?php
// tests/CalculadoraTest.php

use PHPUnit\Framework\TestCase;
use Model\Registro;
use Dotenv\Dotenv;
use Exceptions\InvalidDataException;
use Exceptions\InvalidIdException;
use Exceptions\InvalidTableException;

class EliminarRegistroTest extends TestCase
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

    public function testEliminarRegistro()
    {
        $activeRegistro = new Registro();
        $activeRegistro::setDB($this::$db);
        $registro = $activeRegistro->find(38);
        $registro::setDB($this::$db);
        $resultado = $registro->eliminar();
        $this->assertTrue($resultado);
    }

    public function testIdentificadorIncorrecto()
    {
        $registro = new Registro([
            'id' => '',
            'paquete_id' => 1,
            'pago_id' => '6YP54551SX317731N',
            'token' => 'a3e7f336',
            'usuario_id' => 11,
            'regalo_id' => 7
        ]);
        $registro::setDB($this::$db);
        $this->expectException(InvalidIdException::class);
        $registro->eliminar();
    }

    public function testTablaIncorrecta()
    {
        $registro = new Registro([
            'id' => '',
            'paquete_id' => 1,
            'pago_id' => '6YP54551SX317731N',
            'token' => 'a3e7f336',
            'usuario_id' => 11,
            'regalo_id' => 7
        ]);
        $registro::setDB($this::$db);
        $registro::$tabla = "dummy";
        $this->expectException(InvalidTableException::class);
        $registro->eliminar();
    }
}
