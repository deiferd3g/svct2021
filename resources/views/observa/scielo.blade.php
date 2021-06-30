<div class="toolbar">
    <h3>{{$resultados}}</h3>
    <!--        Here you can write extra buttons/actions for the toolbar              -->
</div>
<div class="material-datatables">
    <table id="datatables" class="table table-striped table-no-bordered table-hover text-center text-uppercase" cellspacing="0" width="100%"
        style="width:100%">
        <thead class="bg-info white">
            {{-- <th></th> --}}
            <th>Título</th>
            {{-- <th>País</th> --}}
            <th>Autor</th>
            <th>Idioma</th>
            <th>Datos del recurso</th>
            <th>Url</th>
            {{-- <th>Clave</th> --}}
        </thead>
        <tbody>
            @foreach ($r as $pag)
            @foreach ($pag as $item)
            {{-- @if ($item['titulo'] != 'Próxima') --}}
            <tr>
                {{-- <td>{{$item['n']}}</td> --}}
                <td>{{$item['titulo']}}</td>
                {{-- <td>{{$item['pais']}}</td> --}}
                <td>{{$item['autor']}}</td>
               <td>{{str_replace("Abstract", "Resumen", $item['idioma'])}}</td>
               <td>{{ $item['tipo']}}</td>
                {{-- <td>{{str_replace("Journal Metrics Sobre o periódico SciELO Analytics", "", $item['tipo'])}}</td> --}}
                <td><a href="{{$item['href']}}">{{$item['href']}}</a></td>
                {{-- <td>{{$item['clave']}}</td> --}}
            </tr>
            {{-- {{$item->html()}} --}}
            {{-- @endif --}}
            @endforeach

            @endforeach
        </tbody>
    </table>
</div>
@push('js')
<script>

</script>
@endpush

