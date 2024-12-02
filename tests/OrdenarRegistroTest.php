<?php
// tests/CalculadoraTest.php

use PHPUnit\Framework\TestCase;
use Model\Registro;
use Dotenv\Dotenv;
use Exceptions\InvalidColumnException;
use Exceptions\InvalidCredentialException;
use Exceptions\InvalidDataException;
use Exceptions\InvalidIdException;
use Exceptions\InvalidOrderException;
use Model\Ponente;

class OrdenarRegistroTest extends TestCase
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
    public function testOrdenarRegistro()
    {
        $ponente = new Ponente();
        $ponente::setDB($this::$db);
        $listaPonente = $ponente::ordenar('id', 'DESC');
        $ultimoRegistro = array_pop($listaPonente);
        $nombre = trim($ultimoRegistro->nombre);
        $this->assertEquals('Julian', $nombre);

    }
    public function testOrdenInvalido()
    {
        $ponente = new Ponente();
        $ponente::setDB($this::$db);
        $this->expectException(InvalidOrderException::class);
        $ponente::ordenar('id', 'a');
    }

    
    public function testColumnaInvalida()
    {
        $ponente = new Ponente();
        $ponente::setDB($this::$db);
        $this->expectException(InvalidColumnException::class);
        $ponente::ordenar('p', 'DESC');
    }
}