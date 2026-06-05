<?php

namespace App\Http\Controllers\ModuloInscripcion;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessEnvioCredenciales;
use App\Jobs\ProcessEnvioCredencialesDocentes;
use App\Jobs\ProcessRegistroFichaInscripcion;
use App\Jobs\ProcessRegistroFichaInscripcion2;
use App\Models\Admision;
use App\Models\Docente;
use App\Models\ExpedienteAdmision;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\ProgramaProceso;
use App\Models\Trabajador;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class InscripcionController extends Controller
{
    public function auth()
    {
        $admision = Admision::where('admision_estado', 1)->first()->admision;
        $admision_year = Admision::where('admision_estado', 1)->first()->admision_año;
        $admision = ucwords(strtolower(formatearAdmisionVisual($admision)));
        return view('modulo-inscripcion.auth', [
            'admision' => $admision,
            'admision_year' => $admision_year
        ]);
    }

    public function registro()
    {
        // $id_pago = auth('inscripcion')->user()->id_pago;
        // $inscripcion = Inscripcion::where('id_pago', $id_pago)->first();
        // $id_inscripcion = $inscripcion->id_inscripcion;
        // return view('modulo-inscripcion.registro', [
        //     'id_inscripcion' => $id_inscripcion
        // ]);
        return view('modulo-inscripcion.registro');
    }

    public function registro_alumnos()
    {
        return view('modulo-inscripcion.registro-alumnos.index');
    }

    public function gracias($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        if (!$inscripcion) {
            abort(404);
        }
        return view('modulo-inscripcion.gracias', [
            'id_inscripcion' => $id_inscripcion
        ]);
    }

    public function gracias_registro($id)
    {
        $id_persona = $id;


        $persona = Persona::find($id_persona);
        // dd($persona);
        if (!$persona) {
            abort(404);
        }
        return view('modulo-inscripcion.registro-alumnos.gracias', [
            'id_persona' => $id_persona
        ]);
    }

    public function ficha_inscripcion_email($id)
    {
        $inscripcion = Inscripcion::where('id_inscripcion', $id)->first(); // Datos de la inscripcion

        $pago = Pago::where('id_pago', $inscripcion->id_pago)->first();
        $pago_monto = $pago->pago_monto; // Monto del pago

        $admision = $inscripcion->programa_proceso->admision->admision; // Admision de la inscripcion

        $fecha_actual = date('h:i:s a d/m/Y', strtotime($inscripcion->inscripcion_fecha)); // Fecha de inscripcion
        $fecha_actual2 = date('d-m-Y', strtotime($inscripcion->inscripcion_fecha)); // Fecha de inscripcion
        $programa = ProgramaProceso::where('id_programa_proceso', $inscripcion->id_programa_proceso)->first(); // Programa de la inscripcion
        $inscripcion_codigo = Inscripcion::where('id_inscripcion', $id)->first()->inscripcion_codigo;
        $tiempo = 6;
        $valor = '+ ' . intval($tiempo) . ' month';
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
        $final = strftime('%d de %B del %Y', strtotime($fecha_actual2 . $valor));
        $persona = Persona::where('id_persona', $inscripcion->id_persona)->first();
        $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $id)->get();
        $expediente = ExpedienteAdmision::join('expediente', 'expediente.id_expediente', '=', 'expediente_admision.id_expediente')
            ->join('admision', 'admision.id_admision', '=', 'expediente_admision.id_admision')
            ->where('expediente_admision.expediente_admision_estado', 1)
            ->where('expediente.expediente_estado', 1)
            ->where('admision.admision_estado', 1)
            ->where(function ($query) use ($inscripcion) {
                $query->where('expediente.expediente_tipo', 0)
                    ->orWhere('expediente.expediente_tipo', $inscripcion->inscripcion_tipo_programa);
            })
            ->get();

        // verificamos si tiene expediente en seguimientos
        $seguimiento_count = ExpedienteInscripcionSeguimiento::join('expediente_inscripcion', 'expediente_inscripcion.id_expediente_inscripcion', '=', 'expediente_inscripcion_seguimiento.id_expediente_inscripcion')
            ->where('expediente_inscripcion.id_inscripcion', $id)
            ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', 1)
            ->where('expediente_inscripcion_seguimiento.expediente_inscripcion_seguimiento_estado', 1)
            ->count();

        $data = [
            'persona' => $persona,
            'fecha_actual' => $fecha_actual,
            'programa' => $programa,
            'admision' => $admision,
            'pago' => $pago,
            'inscripcion' => $inscripcion,
            'inscripcion_codigo' => $inscripcion_codigo,
            'pago_monto' => $pago_monto,
            'expediente_inscripcion' => $expediente_inscripcion,
            'expediente' => $expediente,
            'seguimiento_count' => $seguimiento_count
        ];

        // Crear directorios para guardar los archivos
        $base_path = 'Posgrado/';
        $folders = [
            $admision,
            $persona->numero_documento,
            'Expedientes'
        ];

        // Asegurar que se creen los directorios con los permisos correctos
        $path = asignarPermisoFolders($base_path, $folders);

        // Nombre del archivo
        $nombre_pdf = 'ficha-inscripcion-' . Str::slug($persona->nombre_completo, '-') . '.pdf';
        $nombre_db = $path . $nombre_pdf;


        // Generar el pdf de inscripcion
        PDF::loadView('modulo-inscripcion.ficha-inscripcion', $data)->save(public_path($path . $nombre_pdf));

        $inscripcion = Inscripcion::find($id);
        $inscripcion->inscripcion_ficha_url = $nombre_db;
        $inscripcion->save();

        // Asignar todos los permisos al archivo
        chmod($nombre_db, 0777);

        // Proceso para generar el pdf de inscripcion y enviarlo al correo
        $inscripcion = Inscripcion::find($id); // Datos de la inscripcion
        // Proceso para generar el pdf de inscripcion y enviarlo al correo
        ProcessRegistroFichaInscripcion2::dispatch($inscripcion, 'create'); // Proceso para generar el pdf de inscripcion y enviarlo al correo

        // redireccionar a la pagina final
        return redirect()->route('inscripcion.gracias', ['id' => $id]);
    }

    public function credenciales_email($id)
    {
        ProcessEnvioCredenciales::dispatch($id);

        // redireccionar a la pagina final
        return redirect()->route('posgrado.gracias', ['id' => $id]);
    }

    public function registro_docente()
    {
        return view('modulo-inscripcion.registro-docente.index');
    }

    public function gracias_registro_docente($id)
    {
        $id_trabajador = $id;

        $trabajador = Trabajador::find($id_trabajador);
        // dd($trabajador);
        if (!$trabajador) {
            abort(404);
        }
        return view('modulo-inscripcion.registro-docente.gracias', [
            'id_trabajador' => $id_trabajador
        ]);
    }

    public function credenciales_email_docente($id)
    {
        ProcessEnvioCredencialesDocentes::dispatch($id, 'create');

        $docente = Docente::find($id);

        // redireccionar a la pagina final
        return redirect()->route('posgrado.gracias.docente', ['id' => $docente->id_trabajador]);
    }
}
