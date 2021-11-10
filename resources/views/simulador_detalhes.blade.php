@extends('layouts.add')
@section('content')
    
    @foreach ($detalhes as $detalhe)
        @push('back_to')
            /simulador
        @endpush
        @push('title')
            Detalhes da Simulação
        @endpush     
        
<div class="container-fluid">
    <form action="{{ route('simulador_update_route') }}" method="POST">
        @csrf
        @method('put')

        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="input-group mb-3  text-center">
                        <input type="text" name="id" value="{{ $detalhe->id }}" hidden>
                        <input type="text" name="nome" class="form-control text-center" value="{{ $detalhe->nome }}" aria-describedby="button-addon2">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">
                            <i class="fas fa-fw fa-sync-alt text-primary"></i>
                        </button>                            
                    </div>  
  
                </div>
 
                <fieldset disabled>
                    <div class="mb-3 text-center">
                        <span>Peso</span>
                        <input type="text" class="form-control text-center" value="{{ $detalhe->peso }}({{ $detalhe->unidade_peso }})">
                    </div>  
                    <div class="mb-3 text-center">
                        <span>Unidades</span>
                        <input type="text" class="form-control text-center" value="{{ $detalhe->quantidade }}">
                    </div>  
                    <div class="mb-3 text-center">
                        <span>Margem de Lucro</span>
                        <input type="text" class="form-control text-center" value="{{ $detalhe->margem }}%">
                    </div>
                    <div class="row">
                        <div class="col-6 text-center">
                            <span>Custo de Produção</span>
                            <input type="text" class="form-control text-center" value="{{ $detalhe->custo }}">                            
                        </div>
                        <div class="col-3 pl-0">
                            <div class="mb-3 text-center">
                                <span>Por Kg</span>
                                <input type="text" class="form-control text-center" value="{{ $detalhe->custo_kg }}">
                            </div>  
                        </div>
                        <div class="col-3 pl-0">
                            <div class="mb-3 text-center">
                                <span>Unidade</span>
                                <input type="text" class="form-control text-center" value="{{ $detalhe->custo_unidade }}">
                            </div>                                
                        </div>
                    </div>      

                    <div class="row">
                        <div class="col-6 text-center">
                            <span>Lucro</span>
                            <input type="text" class="form-control text-center" value="{{ $detalhe->lucro }}">                            
                        </div>
                        <div class="col-3 pl-0">
                            <div class="mb-3 text-center">
                                <span>Por Kg</span>
                                <input type="text" class="form-control text-center" value="{{ $detalhe->lucro_kg }}">
                            </div>  
                        </div>
                        <div class="col-3 pl-0">
                            <div class="mb-3 text-center">
                                <span>Unidade</span>
                                <input type="text" class="form-control text-center" value="{{ $detalhe->lucro_unidade }}">
                            </div>                                
                        </div>
                    </div>  

                    <div class="row">
                        <div class="col-6 text-center">
                            <span>Valor de Venda</span>
                            <input type="text" class="form-control text-center" value="{{ $detalhe->valor }}">                            
                        </div>
                        <div class="col-3 pl-0">
                            <div class="mb-3 text-center">
                                <span>Por Kg</span>
                                <input type="text" class="form-control text-center" value="{{ $detalhe->valor_kg }}">
                            </div>  
                        </div>
                        <div class="col-3 pl-0">
                            <div class="mb-3 text-center">
                                <span>Unidade</span>
                                <input type="text" class="form-control text-center" value="{{ $detalhe->valor_unidade }}">
                            </div>                                
                        </div>
                    </div>  
                                                                
                </fieldset>              
            </div>            
        </div>



                        
    </form>
</div>        

    @endforeach


@endsection