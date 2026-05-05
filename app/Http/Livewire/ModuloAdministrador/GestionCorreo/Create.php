<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionCorreo;

use App\Jobs\EnviarCorreos;
use App\Jobs\EnviarCorreosMasivo;
use App\Models\Admision;
use App\Models\Correo;
use App\Models\Modalidad;
use App\Models\Persona;
use App\Models\Programa;
use App\Models\ProgramaProceso;
use DOMDocument;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $tipo_envio;
    public $buscar_dni;
    public $correo_electronico;
    public $persona;
    public $tipo_envio_tabla;
    public $proceso;
    public $modalidad;
    public Collection $modalidades;
    public $programa;
    public Collection $programas;
    public $cantidad_correos = 0;
    public $archivo;

    public $asunto;
    public $mensaje;

    public function mount()
    {
        $this->modalidades = collect();
        $this->programas = collect();
        $this->cantidad_correos = 0;
    }

    public function updatedTipoEnvio($value)
    {
        if ($value == 1) {
            $this->reset(['buscar_dni', 'persona']);
        } else {
            $this->tipo_envio_tabla = null;
            $this->reset(['proceso', 'modalidad', 'programa']);
            $this->modalidades = collect();
            $this->programas = collect();
        }

        $this->cantidad_correos = 0;
    }

    public function buscar_persona()
    {
        $this->validate([
            'buscar_dni' => 'required|numeric|digits:8'
        ]);

        $this->persona = Persona::where('numero_documento', $this->buscar_dni)->first();
    }

    public function updatedProceso($value)
    {
        if ($value != null) {
            $this->modalidades = Modalidad::where('modalidad_estado', 1)->get();
            $this->modalidad = null;
            $this->programas = collect();
        } else {
            $this->modalidades = collect();
            $this->modalidad = null;
            $this->programas = collect();
        }

        $this->cantidad_correos = calcularCantidadDePersonas($this->tipo_envio_tabla, $value, null, null)['cantidad'];
    }

    public function updatedModalidad($value)
    {
        if ($value != null) {
            $this->programas = Programa::where('id_modalidad', $value)
                ->where('programa_estado', 1)
                ->get();
            $this->programa = null;
        } else {
            $this->programas = collect();
            $this->programa = null;
        }

        $this->cantidad_correos = calcularCantidadDePersonas($this->tipo_envio_tabla, $this->proceso, $value, null)['cantidad'];
    }

    public function updatedPrograma($value)
    {
        $this->cantidad_correos = calcularCantidadDePersonas($this->tipo_envio_tabla, $this->proceso, $this->modalidad, $value)['cantidad'];
    }

    public function enviar_correo()
    {
        $this->validate([
            'tipo_envio' => 'required',
            'asunto' => 'required',
            'mensaje' => 'required'
        ]);

        // si hay imagenes en el mensaje con summer note
        $mensaje = $this->mensaje;

        $mensaje = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>' . $mensaje . '</body></html>';

        // $dom = new DOMDocument();
        // @$dom->loadHTML($mensaje, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
        // $body = $dom->getElementsByTagName('body')->item(0);
        // $newHtml = '';
        // foreach ($body->childNodes as $child) {
        //     $newHtml .= $dom->saveHTML($child);
        // }
        // dd($newHtml);
        // $newHtml = mb_convert_encoding($newHtml, 'UTF-8', 'HTML-ENTITIES');
        // dd($newHtml);

        // $dom = new DOMDocument();
        // $dom->encoding = 'utf-8';
        // @$dom->loadHTML($newHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
        // $images = $dom->getElementsByTagName('img');
        // foreach ($images as $k => $img) {
        //     Crear directorios para guardar los archivos
        //     $base_path = 'Posgrado/';
        //     $folders = [
        //         'files',
        //         'media',
        //     ];
        //     Asegurar que se creen los directorios con los permisos correctos
        //     $path = asignarPermisoFolders($base_path, $folders);

        //     Nombre del archivo
        //     $data = $img->getAttribute('src');
        //     list($type, $data) = explode(';', $data);
        //     list(, $data) = explode(',', $data);
        //     $data = base64_decode($data);

        //     Nombre del archivo
        //     $filename = time() . uniqid() . '.png';

        //     Guardar el archivo
        //     $image_name = $filename;

        //     file_put_contents(public_path($path . $filename), $data);
        //     $img->removeAttribute('src');
        //     $img->setAttribute('src', asset($path . $filename));
        // }

        $dom = new DOMDocument();
        // Convertir y cargar el contenido HTML en UTF-8
        $utf8Html = mb_convert_encoding($mensaje, 'HTML-ENTITIES', 'UTF-8');
        @$dom->loadHTML($utf8Html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $img) {
            $data = $img->getAttribute('src');
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                // Obtener el tipo y decodificar
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif

                $data = base64_decode($data);
                if ($data === false) {
                    continue;
                }

                // Crear y guardar el archivo
                $filename = time() . uniqid() . ".$type";
                $filePath = public_path('Posgrado/files/media/' . $filename);
                file_put_contents($filePath, $data);

                // Actualizar la fuente de la imagen en el HTML
                $img->removeAttribute('src');
                $img->setAttribute('src', asset('Posgrado/files/media/' . $filename));
            }
        }

        $this->mensaje = $dom->saveHTML();

        if ($this->tipo_envio == 1) {
            $this->validate([
                'buscar_dni' => 'nullable|numeric|digits:8',
                'correo_electronico' => 'nullable|email',
            ]);

            // veriifcamos si el cambo buscar_dni y correo_electronico estan vacios
            if ($this->buscar_dni == null && $this->correo_electronico == null) {
                $this->addError('buscar_dni', 'El campo DNI o Correo Electrónico es requerido');
                $this->addError('correo_electronico', 'El campo DNI o Correo Electrónico es requerido');
                return;
            }

            // verificamos si el campo buscar_dni y correo_electronico estan llenos
            if ($this->buscar_dni != null && $this->correo_electronico != null) {
                $this->addError('buscar_dni', 'Solo debe llenar un campo');
                $this->addError('correo_electronico', 'Solo debe llenar un campo');
                return;
            }

            // verificamos si el campo buscar_dni esta lleno y correo_electronico esta vacio
            if ($this->buscar_dni != null && $this->correo_electronico == null) {
                $this->persona = Persona::where('numero_documento', $this->buscar_dni)->first();
                if ($this->persona == null) {
                    $this->addError('buscar_dni', 'El DNI no se encuentra registrado');
                    return;
                }

                $correos_db = json_encode([$this->persona->correo]);
                $correos = [$this->persona->correo];
            }

            // verificamos si el campo buscar_dni esta vacio y correo_electronico esta lleno
            if ($this->buscar_dni == null && $this->correo_electronico != null) {
                $correos_db = json_encode([$this->correo_electronico]);
                $correos = [$this->correo_electronico];
            }
        }
        if ($this->tipo_envio == 2 && $this->tipo_envio_tabla != 3){
            $this->validate([
                'proceso' => 'required',
            ]);

            $correos_db = json_encode(calcularCantidadDePersonas($this->tipo_envio_tabla, $this->proceso, $this->modalidad, $this->programa)['correos']);
            $correos = calcularCantidadDePersonas($this->tipo_envio_tabla, $this->proceso, $this->modalidad, $this->programa)['correos'];
        }
        if ($this->tipo_envio == 2 && $this->tipo_envio_tabla == 3) {
            $this->validate([
                'archivo' => 'required|file|mimes:txt',
            ]);

            $correos_db = json_encode([]);
            $correos = [];

            $file = $this->archivo;

            $file = fopen($file->getRealPath(), 'r');
            while (($data = fgetcsv($file, 1000, ',')) !== false) {
                $correos[] = $data[0];
            }
            fclose($file);

            $correos_db = json_encode($correos);
        }

        $asunto = $this->asunto;
        $mensaje = $this->mensaje;
        $correo_json = $correos_db;

        $correo = new Correo();
        $correo->correo_asunto = $asunto;
        $correo->correo_mensaje = $mensaje;
        $correo->correo_enviados = $correo_json;
        $correo->correo_estado = 1;
        $correo->save();

        // ejecutar el envio de correos masivos o individuales con un job
        // EnviarCorreosMasivo::dispatch($asunto, $mensaje, $correos);
        foreach ($correos as $correo) {
            EnviarCorreos::dispatch($asunto, $mensaje, $correo);
        }

        // redireccionar a la vista de correos
        return redirect()->route('administrador.gestion-correo');
    }

    public function render()
    {
        $procesos = Admision::query()
            ->orderBy('admision', 'desc')
            ->get();

        return view('livewire.modulo-administrador.gestion-correo.create', [
            'procesos' => $procesos
        ]);
    }
}
