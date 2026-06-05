@extends('layouts.auth-modulo-inscripcion')
@section('content')
<div class="">
    <div class="container-xxl py-10">
        <div class="mt-5">
            <div class="d-flex justify-content-around align-items-center">
                <div class="d-flex flex-column align-items-center">
                    <h1 class="text-center" style="font-size: 2.3rem; font-weight: 700;">
                        Universidad Nacional de Ucayali
                    </h1>
                    <span class="fw-bolder text-center mb-3" style="font-size: 2rem; font-weight: 700;">
                        Escuela de Posgrado
                    </span>
                    <span class="fw-bolder fs-1 text-center">
                        Proceso de {{ formatearAdmisionVisual($admision) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="app-container d-flex flex-row flex-column-fluid">
    <div class="container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    @livewire('modulo-inscripcion.auth', ['admision_year' => $admision_year])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    window.addEventListener('modal_registro_pago', event => {
        $('#modal_registro_pago').modal(event.detail.action);
    })
    window.addEventListener('registro_pago', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            buttonsStyling: false,
            confirmButtonText: event.detail.confirmButtonText,
            customClass: {
                confirmButton: "btn btn-"+event.detail.color,
            }
        });
    })
</script>
@endsection
