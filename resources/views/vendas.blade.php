@extends('layouts.simulador')
@section('content')
    
    @push('title')
        Vendas
    @endpush

    <style>
    /* The slider itself */
    .slider {
    -webkit-appearance: slider-vertical;
    width: 20px;
    height: 130px;
    }

    </style>


    
   
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button 
                class="accordion-button collapsed" 
                type="button" data-bs-toggle="collapse" 
                data-bs-target="#collapseOne" 
                aria-expanded="true" 
                aria-controls="collapseOne">
                Adicionar Venda
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="container mx-0">
                    <form action="{{ route('insert_vendas_route') }}" method="POST">
                    @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mx-0 pl-0">
                                        <span class="form-text text-center texto">Item no Simulador</span>
                                        <select class="form-select text-center texto" name="receita_id">
                                            @foreach ($simulados as $simulado)
                                            <option value="{{ $simulado->id }}">{{ $simulado->nome }}</option>
                                            @endforeach
                                        </select>          
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-5 mx-0 pl-0">
                                        <span class="form-text text-center texto">Quantidade</span>
                                        <input type="number" class="form-control text-center texto" name="quantidade">
                                    </div>
                                    <div class="col-7 mx-0 pl-0">
                                        <span class="form-text text-center texto">Data</span>
                                        <input type="date" class="form-control text-center texto" name="data">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12 mx-0 pr-0 pl-1">
                                        <button type="submit" class="btn mb-2 primeira w-100">
                                            <span>ADD</span>
                                        </button>     
                                    </div>
                                </div>
            
                            </div>
                        </div>
                    </form>
                </div>
            </div>
          </div>
        </div>
    </div>

    <div class="row">
        <div class="col"></div>
    </div>

    <ul class="nav nav-tabs mt-3">
        @foreach ($vendas_por_ano as $ano)
            @if($ano->ano == $ano_selecionado)
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('vendas_route', ["ano"=>$ano->ano]) }}">{{ $ano->ano }}</a>
                </li>     
            @else
                <li class="nav-item">
                    <a class="nav-link"href="{{ route('vendas_route', ["ano"=>$ano->ano]) }}">{{ $ano->ano }}</a>
                </li>   
            @endif
         
        @endforeach
    </ul>

    <div class="row mt-3 text-center">
        @foreach ($vendas_por_mes as $venda)
            
            @if($venda->mes_a == $mes_selecionado)
                <div class="col">
                    <a class="text-decoration-none texto-secondario" href="{{ route('vendas_route', ["mes"=>$venda->mes_a]) }}">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <h6>{{ $venda->mes }}</h6>
                            </div>
                            <div class="card-body">
                                <h6>{{ $venda->lucro }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @else
            <div class="col">
                <a class="text-decoration-none texto-secondario" href="{{ route('vendas_route', ["mes"=>$venda->mes_a]) }}">
                    <div class="card">
                        <div class="card-header">
                            <h6>{{ $venda->mes }}</h6>
                        </div>
                        <div class="card-body">
                            <h6>{{ $venda->lucro }}</h6>
                        </div>
                    </div>
                </a>
            </div>
            @endif
        @endforeach

    </div>

    <div class="mt-3 text-center">
        <div class="">
            <table class="table text text-center">
              <thead class="texto-secondario">
                <tr>
                    <th class=" px-0">
                        <span 
                            data-bs-placement="top" 
                            tabindex="0" 
                            data-bs-toggle="popover" 
                            data-bs-trigger="hover focus" 
                            data-bs-content="Data da venda">
                            <button 
                                class="btn texto-secondario" 
                                type="button" 
                                disabled
                                style="opacity:100;">
                                DT
                            </button>
                        </span>               
                    </th>       
                    <th class=" px-0">
                        <span 
                            data-bs-placement="top" 
                            tabindex="0" 
                            data-bs-toggle="popover" 
                            data-bs-trigger="hover focus" 
                            data-bs-content="Quantidade Vendida">
                            <button 
                                class="btn texto-secondario" 
                                type="button" 
                                disabled
                                style="opacity:100;">
                                QT
                            </button>
                        </span>               
                    </th>                                   
                    <th class=" px-0">
                        <span 
                            data-bs-placement="top" 
                            tabindex="0" 
                            data-bs-toggle="popover" 
                            data-bs-trigger="hover focus" 
                            data-bs-content="Receita Vendida">
                            <button 
                                class="btn texto-secondario" 
                                type="button" 
                                disabled
                                style="opacity:100;">
                                RC
                            </button>
                        </span>               
                    </th>
                    <th class=" px-0">
                        <span 
                            data-bs-placement="top" 
                            tabindex="0" 
                            data-bs-toggle="popover" 
                            data-bs-trigger="hover focus" 
                            data-bs-content="Valor de Custo">
                            <button 
                                class="btn texto-secondario" 
                                type="button" 
                                disabled
                                style="opacity:100;">
                                VC
                            </button>
                        </span>               
                    </th>        
                    <th class=" px-0">
                        <span 
                            data-bs-placement="top" 
                            tabindex="0" 
                            data-bs-toggle="popover" 
                            data-bs-trigger="hover focus" 
                            data-bs-content="Lucro em R$">
                            <button 
                            class="btn texto-secondario" 
                            type="button" 
                            disabled
                            style="opacity:100;">
                            L
                            </button>
                        </span>               
                    </th>
                    <th class=" px-0">
                        <span 
                            data-bs-placement="top" 
                            tabindex="0" 
                            data-bs-toggle="popover" 
                            data-bs-trigger="hover focus" 
                            data-bs-content="Valor de Venda">
                            <button 
                            class="btn text-decoration-none texto-secondario" 
                            type="button" 
                            disabled
                            style="opacity:100;">
                            VV
                            </button>
                        </span>             
                    </th>

                    <th class=" px-0">
                        <span 
                            data-bs-placement="top" 
                            tabindex="0" 
                            data-bs-toggle="popover" 
                            data-bs-trigger="hover focus" 
                            data-bs-content="Excluir">
                            <button 
                            class="btn text-decoration-none texto-secondario" 
                            type="button" 
                            disabled
                            style="opacity:100;">
                            <i class="nav-icon fas fa-fw fa-trash-alt"></i>
                            </button>
                        </span>             
                    </th>            
                </tr>
              </thead>
          
              <tbody>
                @foreach ($vendas as $venda )
                    <tr class="py-2 texto-secondario">
                        <td id="id" valor="{{ $venda->id }}" hidden></td>
                        <td class="align-middle px-0">{{ $venda->data }}</td>
                        <td class="align-middle px-0">{{ $venda->quantidade }}</td>
                        <td class="align-middle px-0">{{ $venda->nome }}</td>
                        <td class="align-middle px-0" id="td_custo"><?php echo round($venda->custo,0); ?></td>
                        <td class="align-middle px-0" id="td_lucro"><?php echo round($venda->lucro,0);?></td>
                        <td class="align-middle px-0 " id="td_valor"><?php echo round($venda->valor,0);?></td>
                        <form action="{{ route('delete_vendas_route') }}" method="POST">
                            @csrf
                            @method('delete')
                            <td class="align-middle px-0 ">   
                            <input type="text" name="id" value="{{ $venda->id }}" hidden>     
                            <button 
                                type="submit"
                                class="btn" 
                                style="background-color: transparent">
                                <i class="fas fa-times-circle text-danger"></i>
                            </button>
                            </td>            
                        </form>
                    </tr>
                @endforeach  
                <tr>
                    <td colspan=3>TOTAL</td>
                    <td class="align-middle px-0"><?php echo round($total_vendas->custo,0);?></div></td>  
                    <td class="align-middle px-0">
                        <div class="segunda pdy-1 h-100"><?php echo round($total_vendas->lucro,0);?></div>
                    </td>
                    <td class="align-middle px-0"><?php echo round($total_vendas->valor,0);?></div></td>  
                    <td></td>
                </tr>    
              </tbody>
            </table>
          </div>
    </div>

@push('script_simulador')

    <script>

        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
        })
        
    </script>

@endpush

@endsection