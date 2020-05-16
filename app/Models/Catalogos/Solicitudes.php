<?php















namespace App\Models\Catalogos;















use Illuminate\Database\Eloquent\Model;















class Solicitudes extends Model {



	public $timestamps = false;



    protected $table = 'solicitud';



    protected $primaryKey = 'id_solicitud';



	protected $fillable = ['id_solicitud', 'id_washer','id_usuario','id_paquete', 'id_auto', 'fragancia', 'latitud', 'longitud', 'direccion','foto', 'foto_washer', 'fecha', 'calificacion', 'comentario', 'forma_pago', 'cambio', 'status', 'fecha_atendida'];



}