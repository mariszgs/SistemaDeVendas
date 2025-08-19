<?php
namespace Application\Shared;

use Laminas\Db\Adapter\Adapter;

class Db
{
    private static ?Adapter $adapter = null;

    public static function adapter(): Adapter
    {
        if (!self::$adapter) {
            self::$adapter = new Adapter([
                'driver'   => 'Pdo_Pgsql',
                'host'     => 'localhost',  ## alterar "localhost" para "host.docker.internal" na utilização do docker
                'dbname'   => 'sdv',
                'username' => 'postgres',
                'password' => 'QWE123',
                'port'     => 5433        
            ]);
        }

        return self::$adapter;
    }
}


