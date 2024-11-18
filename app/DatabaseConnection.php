<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Dotenv\Dotenv;

class DatabaseConnection
{
    protected static $instance = null;

    // Método para obtener la instancia de conexión
    public static function getConnection()
    {
        if (self::$instance === null) {
            // Cargar el archivo .env
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../'); // Ajusta la ruta si es necesario
            $dotenv->load();
            
            // Probar la conexión
            try {
                self::$instance = DB::connection()->getPdo();
            } catch (\Exception $e) {
                die("Error en la conexión: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
    // Método para seleccionar todo los registros
    public static function selectAll($table)
    {
        return DB::table($table)->get();
    }

    // Método para seleccionar registros con condiciones
    public static function selectWithConditions($table, $conditions)
    {
        return DB::table($table)->where($conditions)->get();
    }

    // Método para seleccionar un solo registro basado en condiciones
    public static function selectOne($table, $conditions)
    {
        return DB::table($table)->where($conditions)->first();
    }

    // Método para seleccionar un solo registro basado en condiciones (utilizando strings e ignorando mayusculas/minusculas)
    public static function selectOneStr($table, $conditions)
    {
        $query = DB::table($table);

        foreach($conditions as $column => $value){
            $query->whereRaw("LOWER($column) = ?",[strtolower($value)]);
        }
        return $query->first();
    }

    // Método para insertar registros
    public static function insert($table, $data)
    {
        return DB::table($table)->insert($data);
    }

    // Método para insertar registros y obtener el último ID insertado
    public static function insertGetId($table, $data)
    {
        return DB::table($table)->insertGetId($data);
    }

    // Método para eliminar registros
    public static function delete($table, $conditions)
    {
        return DB::table($table)->where($conditions)->delete();
    }

    // Método para actualizar registros
    public static function update($table, $data, $conditions)
    {
        return DB::table($table)->where($conditions)->update($data);
    }


}
