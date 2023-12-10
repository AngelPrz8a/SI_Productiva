<?php

namespace App\Models;

use App\Models\Aprendiz;
use App\Models\Instructor;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function instructor(){
        foreach( DB::select('SELECT instructor.* FROM usuario
        INNER JOIN instructor on usuario.id = instructor.id_usuario
        where usuario.id = ?', [$this->id]) as $sql){
            return Instructor::find($sql->id);
        }
    }

    public function aprendiz(){
        foreach( DB::select('SELECT aprendiz.* FROM usuario
        INNER JOIN aprendiz on usuario.id = aprendiz.id_usuario
        where usuario.id = ?', [$this->id]) as $sql){
            return Aprendiz::find($sql->id);
        }
    }


    public function rol(){
        return $this
       ->hasManyThrough(
           Rol::class,         //Primero el modelo al que quiero llegar
           UsuarioRol::class,  //Segundo el modelo intermedio
           'id_usuario',   //FK on the 2 table
           'id',           //FK on the 1 table
           'id',           //PK on the model principal
           'id_rol'            //PD on the 2 table
       )
       ;
   }

}
