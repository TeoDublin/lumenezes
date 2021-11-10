@extends('layouts.simulador')
@section('content')
    
    @push('title')
        Receitas
    @endpush


    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link" href="/receitas">Doce</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/receitas_bolos">Bolo</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/receitas_massas">Mass</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active fw-bold" aria-current="page" href="#">Recheios</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/receitas_coberturas">Cobe</a>
        </li>       
    </ul>
  
    <form action="{{ route('insert_receitas_route') }}" method="POST">
        @csrf
        <div class="row mb-2">

            <div class="col-6 pr-1">
                <input type="text" name="nome" placeholder="Nome da receita" class="form-control">
            </div>
            <div class="col-2 pr-1 pl-0">
                <input type="text" name="peso" placeholder="Peso" class="form-control">
            </div>
            <div class="col-2 pr-1 pl-0">
                <select name="unidade"    placeholder="Un" class="form-select">
                    <option selected></option>
                    <option value="Kg">Kg</option>
                    <option value="g">g</option>
                </select>      
            </div>

            <input type="text" name="tipo" value="recheios" class="form-control" hidden>       
            <input type="text" name="route" value="receitas_recheios_route" class="form-control" hidden>      

            <div class="col-2 pl-0">
                <button 
                    type="submit"
                    class="btn primeira w-100">
                    <span>ADD</span>
                </button>     
            </div>

        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <span>{{ $error }}</span>
                @endforeach
            </div>
        @endif   
    </form>
 
    <div class="accordion" id="accordionPanelsStayOpen">
        <div class="row">
            @foreach ($receitas as $receita)
                @if ( app('request')->input('item') == $receita->nome)
                    <?php $c1 = "accordion-button texto-secondario"; $c2 = "accordion-collapse collapse show texto-secondario"; ?>
                @else
                    <?php $c1 = "accordion-button collapsed texto-secondario"; $c2 = "accordion-collapse collapse texto-secondario"; ?>
                @endif
    
                <div class="col-11 pr-0">
                    <div class="accordion-item" id="{{ $receita->id }}">
                        <h2 class="accordion-header">
                        <button 
                            class="<?php echo $c1;?>" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#db_{{ $receita->id }}" 
                            aria-controls="db_{{ $receita->id }}">
                            {{ $receita->nome }} ({{ $receita->peso }}{{ $receita->unidade_peso }})
                        </button>
                        </h2>
                        <div id="db_{{ $receita->id }}" class="<?php echo $c2;?> texto-secondario" aria-labelledby="{{ $receita->id }}">
                            <div class="accordion-body">
                                
                                @foreach ($formulas as $formula)
                                    @if ($formula->receita_id == $receita->id)
                                        <div class="row text-center py-2 texto-terciario">
                                            <div class="col-4">{{ $formula->produto }}</div> 

                                            @switch($formula->quantidade)
                                                @case(0.50)
                                                    <div class="col-1">1/2</div>
                                                    @break
                                    
                                                @case(0.34)
                                                    <div class="col-1">1/3</div>
                                                    @break     
                                    
                                                @case(0.67)
                                                    <div class="col-1">2/3</div>
                                                    @break            
                                    
                                                @case(0.25)
                                                    <div class="col-1">1/4</div>
                                                    @break                          
                                                    
                                                @case(0.75)
                                                    <div class="col-1">3/4</div>
                                                    @break   

                                                @case(0.12)
                                                    <div class="col-1">1/8</div>
                                                    @break                                                       
                                                    
                                                @default
                                                <div class="col-1">{{ $formula->quantidade }}</div> 

                                            @endswitch

                                            <div class="col-3">{{ $formula->unidade }}</div>       
                                            <div class="col-2"><?php echo round( $formula->custo,1); ?></div> 
                                            <div class="col-1">
                                                <form action="{{ route('delete_formulas_route')}}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="text" name="id" value="{{ $formula->id }}" hidden>
                                                    <input type="text" name="item" value="{{ $receita->nome }}" hidden>
                                                    <input type="text" name="receita_id" value="{{ $receita->id }}" hidden>
                                                    <input type="text" name="route_delete_formulas" value="receitas_recheios_route" class="form-control" hidden>
                                                    <button type="submit" class="border-0" style="background-color:transparent" >
                                                        <i class="fas fa-times-circle text-danger"></i> 
                                                    </button>                                              
                                                </form>
                                            </div>                                    
                                        </div>
                                    @endif
                                @endforeach     
                                
                                <div class="row btn_add_produto " referencia="{{ $receita->id }}">
                                    <div class="btn mt-2 text-start">
                                        <i class="fas fa-plus-circle mr-1 fa-medium text-primary"></i>
                                        <span class="texto-secondario">Novo Produto</span>
                                    </div>                                
                                </div>
        
                                <form action="{{ route('insert_formulas_route') }}"  method="POST">
                                    @csrf      
                                    <div class="row text-center mt-3" id="_{{ $receita->id }}" hidden>
                                        
                                        <input type="text" class="form-control" name="item" value="{{ $receita->nome }}" hidden>
                                        <input type="text" class="form-control" name="receita_id" value="{{ $receita->id }}" hidden>
                                        <input type="text" class="form-control" id="produto_unidade_mili_{{ $receita->id }}" name="produto_unidade_mili" value="" hidden>
                                        <input type="text" class="form-control" id="produto_custo_mili_{{ $receita->id }}" name="produto_custo_mili" value="" hidden>
                                        <input type="text" name="route_insert" value="receitas_recheios_route" class="form-control" hidden>  

                                        <div class="col-5 p-1" receita_id="{{ $receita->id }}">
                                            <select class="form-select controla_unidades" name="produto_id" id="produto_id_{{ $receita->id }}" >
                                                <option selected></option>
                                                @foreach ($produtos as $produto)
                                                    <option 
                                                        value="{{ $produto->id }}" 
                                                        unidade="{{ $produto->unidade_mili }}" 
                                                        custo="{{ $produto->custo_mili }}"
                                                        quantidade="{{ $produto->quantidade_mili }}">
                                                        {{ $produto->produto }}
                                                    </option>
                                                @endforeach
                                            </select>                                       
                                        </div>     
                                        <div class="col-2 p-1"><input type="text" name = "produto_quantidade" placeholder="qtde" class="form-control"></div>
                                        <div class="col-3 p-1">
                                            <select class="form-select" name="produto_unidade" id="produto_unidade_{{ $receita->id }}">
                                                <option grupo="NA" selected></option>
                                                <option grupo="g" value="Kg" hidden>Kg</option>
                                                <option grupo="g" value="g" hidden>g</option>
                                                <option grupo="ml" value="L" hidden>L</option>
                                                <option grupo="ml" value="ml" hidden>ml</option>
                                                <option grupo="KL" value="xícara" hidden>xícara</option>
                                                <option grupo="KL" value="c. de sopa" hidden>c. de sopa</option> 
                                                <option grupo="KL" value="c. de chá" hidden>c. de chá</option>
                                                <option grupo="KL" value="pitada" hidden>pitada</option>
                                            </select>                                       
                                        </div>         
                                        <div class="col-2 p-1">
                                            <button type="submit" class="border-0 btn-submit" style="background-color:transparent" >
                                                <i class="fas fa-check text-primary fa-2x"></i> 
                                            </button>                                        
                                        </div>                          
                                    </div>  
                                </form>  
                            </div>               
                        </div>
                    </div>                    
                </div>

                <div class="col-1 pl-0 m-0">
                    <form action="{{ route('delete_receitas_route')}}" method="POST">
                    @csrf
                    @method('delete')
                        <input type="text" name="receita_id" value="{{ $receita->id }}" hidden>
                        <input type="text" name="reita_item" value="{{ $receita->nome }}" hidden>
                        <input type="text" name="route_delete" value="receitas_recheios_route" class="form-control" hidden> 
                        <button type="submit" class="border-0" style="background-color:transparent" >
                            <i class="fas fa-times-circle text-danger fa-lg pt-3"></i> 
                        </button>  
                    </form>                                            
                </div>

            @endforeach
        
        </div>
    </div>        
    



    @push('script_simulador')
        <script>
            $(".btn_add_produto").click(function(){
                
                $(this).attr('hidden', true);
                var referencia = "#_" + $(this).attr('referencia');
                $(referencia).removeAttr("hidden");
            });

            $(".controla_unidades").change(function(){

                var receita_id = $(this).parent().attr('receita_id');

                var produto_id = "#produto_id_" + receita_id + " option:selected";
                var unidade = $(produto_id).attr('unidade');
                var custo = $(produto_id).attr('custo');
                var quantidade = $(produto_id).attr('quantidade');

                $('#produto_unidade_mili_' + receita_id).attr('value', unidade);
                $('#produto_custo_mili_' + receita_id).attr('value', custo);
                $('#quantidade_matriz_' + receita_id).attr('value', quantidade);

                
                $('#produto_unidade_' + receita_id + ' option').each(function(){
                    var grupo = $(this).attr('grupo');

                    if (grupo == 'KL' || grupo == unidade) {
                        $(this).removeAttr('hidden');
                    } else {
                        $(this).attr('hidden', true);
                    }
                });
            });
        </script>  

    @endpush


@endsection