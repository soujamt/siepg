@extends('layouts.modulo-administrador')

@section('content')

@livewire('modulo-administrador.gestion-admision.inscripcion.index')

@endsection

@section('javascript')
<script>

    window.addEventListener('modal', event => {   
        $(event.detail.titleModal).modal('hide');
    })

    // Alerta para confirmacion
	window.addEventListener('alerta-inscripcion', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            buttonsStyling: false,
            confirmButtonText: event.detail.confirmButtonText,
            customClass: {
                confirmButton: "btn btn-"+event.detail.color+" hover-elevate-up", // Color del boton de confirmación y Hover
            }
        });
    });

    // Notificacion breve que no interrumpe (acciones dentro de los modales)
    window.addEventListener('toast-inscripcion', event => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: event.detail.icon,
            title: event.detail.title,
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
        });
    });

    //alerta
    window.addEventListener('alertaConfirmacion', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            showCancelButton: true,
            confirmButtonText: event.detail.confirmButtonText,
            cancelButtonText: event.detail.cancelButtonText,
            customClass: {
                confirmButton: "btn btn-"+event.detail.confimrColor+" hover-elevate-up", //Hover y color del boton Confirmar
                cancelButton: "btn btn-"+event.detail.cancelColor+" hover-elevate-up", //Hover y color del boton Cancel
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emitTo('modulo-administrador.gestion-admision.inscripcion.index', event.detail.metodo, event.detail.id);
            }
        })
    })

</script>
@endsection