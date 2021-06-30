@extends('layouts.app', ['activePage' => 'observa', 'titlePage' => __('Observa')])
@section('titulo', 'Observa')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header card-header-info">
                <i class="material-icons">search</i>
                {{-- <h4 class="card-title">Metabuscador de vigilancia tecnológica</h4> --}}
                {{-- <p class="card-category">Created using Roboto Font Family</p> --}}
            </div>
            <div class="card-body">
                <form method="POST" action="{{route('buscarScielo')}}" class="form-horizontal" id="buscar">
                    @csrf
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Buscar</label>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <input type="text" class="form-control" name="palabra">
                                {{-- <span class="bmd-help">Ingrese la palabra que desea buscar.</span> --}}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <input type="submit" value="Buscar" class="btn btn-info">
                        {{-- <button class="btn btn-info" action="">Scielo</button> --}}
                    </div>
                </form>
                {{-- <div class="d-flex justify-content-center">
                    <button class="btn btn-info" action="">Scielo</button>
                </div> --}}
                <div class="d-flex justify-content-center">
                    <div class="loader">
                        <img src="{{asset('img/Magnify-1s-200px.gif')}}" alt="w-75">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div id="resultados"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    //EVENTO PARA EL REGISTRO
    $(document).ready(function (e) {
        $('.loader').hide();
        $("#buscar").on('submit', (function (e) {
            e.preventDefault();
            let datos = new FormData(this);
            $.ajax({
                url: "{{route('buscarScielo')}}",
                type: "POST",
                data: datos,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    // utilizo before Send para activar el loader ya que se ejecuta antes de la petición
                    $('.loader').show();
                    // limpio contenedor de resultados
                    $('#resultados').html('');

                },
                success: function (data) {
                    $('.loader').hide();

                    $("#resultados").empty().html(data);
                    tabla();
                },
                error: function (data) {
                    $('.loader').hide();
                    if (data.status === 422) {
                        // let errores = data.responseJSON;
                        let errores = $.parseJSON(data.responseText);
                        $.each(errores.errors, function (key, val) {
                            showNotification('danger', val);
                        });
                    }
                },
            });
        }));
    });

    function exportarScielo(_this) {
        let _url = $(_this).data('href');
        window.location.href = _url;
    }
    //    $(document).ready(function() {


    //       var table = $('#datatable').DataTable();


    //     });

</script>
@endpush
