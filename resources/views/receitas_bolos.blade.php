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
            <a class="nav-link active fw-bold" aria-current="page" href="#">Bolos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/receitas_massas">Mass</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/receitas_recheios">Rech</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/receitas_coberturas">Cobe</a>
        </li>        
    </ul>
  
    <form action="{{ route('insert_bolos_route') }}" method="POST">
        @csrf
        <div class="row mb-2">

            <div class="col-10 pr-1">
                <input type="text" name="nome" placeholder="Nome do Bolo" class="form-control">
            </div> 

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
  
    @foreach ($bolos as $bolo)

        @if ( app('request')->input('bolo') == $bolo->nome)
            @if ( app('request')->input('item')=='massa')
                <?php $c3 = "accordion-button texto-secondario"; $c4 = "accordion-collapse collapse show texto-secondario"; ?>
                <?php $c5 = "accordion-button collapsed texto-secondario"; $c6 = "accordion-collapse collapse texto-secondario"; ?>
                <?php $c7 = "accordion-button collapsed texto-secondario"; $c8 = "accordion-collapse collapse texto-secondario"; ?>
            @elseif( app('request')->input('item')=='recheio')
                <?php $c3 = "accordion-button collapsed texto-secondario"; $c4 = "accordion-collapse collapse texto-secondario"; ?>
                <?php $c5 = "accordion-button texto-secondario"; $c6 = "accordion-collapse collapse show texto-secondario"; ?>
                <?php $c7 = "accordion-button collapsed texto-secondario"; $c8 = "accordion-collapse collapse texto-secondario"; ?>
            @elseif( app('request')->input('item')=='cobertura')
                <?php $c3 = "accordion-button collapsed texto-secondario"; $c4 = "accordion-collapse collapse texto-secondario"; ?>
                <?php $c5 = "accordion-button collapsed texto-secondario"; $c6 = "accordion-collapse collapse texto-secondario"; ?>            
                <?php $c7 = "accordion-button texto-secondario"; $c8 = "accordion-collapse collapse show texto-secondario"; ?>      
            @endif
            <?php $c1 = "accordion-button texto-secondario"; $c2 = "accordion-collapse collapse show texto-secondario"; ?>
        @else
            <?php $c1 = "accordion-button collapsed texto-secondario"; $c2 = "accordion-collapse collapse texto-secondario"; ?>
            <?php $c3 = "accordion-button collapsed texto-secondario"; $c4 = "accordion-collapse collapse texto-secondario"; ?>
            <?php $c5 = "accordion-button collapsed texto-secondario"; $c6 = "accordion-collapse collapse texto-secondario"; ?>
            <?php $c7 = "accordion-button collapsed texto-secondario"; $c8 = "accordion-collapse collapse texto-secondario"; ?>                  
        @endif

        <div class="accordion" id="accordion_{{ $bolo->id }}">

            <div class="row">
                <div class="col-11 pr-0">
                    <div class="accordion-item">

                        <h2 class="accordion-header" id="heading_{{ $bolo->id }}">
                            <button 
                                class="<?php echo $c1;?>" 
                                type="button" data-bs-toggle="collapse" 
                                aria-expanded="false" 
                                aria-controls="collapseTwo"
                                data-bs-target="#collapse_{{ $bolo->id }}" >
                                {{ $bolo->nome }}
                            </button>
                        </h2>
        
                        <div id="collapse_{{ $bolo->id }}" 
                            class="<?php echo $c2;?>" 
                            aria-labelledby="heading_{{ $bolo->id }}"
                            data-bs-parent="#accordion_{{ $bolo->id }}">
        
                            <div class="accordion-body">
        
                                <div class="accordion" id="accordion_massa_{{ $bolo->id }}">
        
                                    <div class="accordion-item">
                        
                                        <h2 class="accordion-header" id="heading_massa_{{ $bolo->id }}">
                                            <button 
                                                class="<?php echo $c3;?>" 
                                                type="button" data-bs-toggle="collapse" 
                                                aria-expanded="false" 
                                                aria-controls="collapseTwo"
                                                data-bs-target="#collapse_massa_{{ $bolo->id }}" >
                                                Massa
                                            </button>
                                        </h2>
                        
                                        <div id="collapse_massa_{{ $bolo->id }}" 
                                            class="<?php echo $c4;?>" 
                                            aria-labelledby="heading_massa_{{ $bolo->id }}"
                                            data-bs-parent="#accordion_massa_{{ $bolo->id }}">
                        
                                            <div class="accordion-body container">
                        
                                                @foreach ($massas as $massa)
                                                    @if ($massa->bolo_id == $bolo->id)
                                                        <div class="row">
                                                            <div class="col-10">{{ $massa->nome }}</div>
                                                            <div class="col-2">
                                                                <form action="{{ route('delete_bolo_formulas_route')}}" method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <input type="text" name="id" value="{{ $massa->id }}" hidden>
                                                                    <input type="text" name="bolo" value="{{ $bolo->nome }}" hidden>
                                                                    <input type="text" name="item" value="massa" hidden>
                                                                    <input type="text" name="route_delete_formulas" value="receitas_bolos_route" class="form-control" hidden>
                                                                    <button type="submit" class="border-0" style="background-color:transparent" >
                                                                        <i class="fas fa-times-circle text-danger"></i> 
                                                                    </button>                                              
                                                                </form>
                                                            </div>    
                                                        </div>                                                        
                                                    @endif

                                               
                                                @endforeach
                        
                                                <form action="{{ route('insert_bolo_formulas_route') }}" method="POST">
                                                     @csrf
                                                     <div class="row" id="{{ $bolo->id }}_massa" hidden>
                                                        <div class="col-10 p-1" >
                                                            <select class="form-select" name="receita_id">
                                                                @foreach ($add_massas as $add_massa)
                                                                    <option value="{{ $add_massa->id }}">{{ $add_massa->nome }}</option>
                                                                @endforeach
                                                            </select>
                                                         </div>
                                                         <div class="col-2 p-1">
                                                            <button type="submit" class="border-0 btn-submit" style="background-color:transparent" >
                                                                <i class="fas fa-check text-primary fa-2x"></i> 
                                                            </button>                                        
                                                        </div>  
                                                     </div>
                                                    <input type="text" name="bolo" value="{{ $bolo->nome }}" hidden>
                                                    <input type="text" name="item" value="massa" hidden>                                             
                                                    <input type="text" name="bolo_id" value="{{ $bolo->id }}" hidden>
                                                    
                                                </form>
                                                <div class="row btn_add_produto " referencia="{{ $bolo->id }}_massa">
                                                    <div class="btn mt-2 text-start">
                                                        <i class="fas fa-plus-circle mr-1 fa-medium text-primary"></i>
                                                        <span class="texto-secondario">Add massa</span>
                                                    </div>                                
                                                </div>                                        
                                            </div>
                                        </div>
                        
                                    </div>
                        
                                </div>   
        
                                <div class="accordion" id="accordion_recheio_{{ $bolo->id }}">
        
                                    <div class="accordion-item">
                        
                                        <h2 class="accordion-header" id="heading_recheio_{{ $bolo->id }}">
                                            <button 
                                                class="<?php echo $c5;?>" 
                                                type="button" data-bs-toggle="collapse" 
                                                aria-expanded="false" 
                                                aria-controls="collapseTwo"
                                                data-bs-target="#collapse_recheio_{{ $bolo->id }}" >
                                                Recheio
                                            </button>
                                        </h2>
                        
                                        <div id="collapse_recheio_{{ $bolo->id }}" 
                                            class="<?php echo $c6;?>" 
                                            aria-labelledby="heading_recheio_{{ $bolo->id }}"
                                            data-bs-parent="#accordion_recheio_{{ $bolo->id }}">
                        
                                            <div class="accordion-body container">
                        
                                                @foreach ($recheios as $recheio)
                                                    @if ($recheio->bolo_id == $bolo->id)
                                                        <div class="row">
                                                            <div class="col-10">{{ $recheio->nome }}</div>
                                                            <div class="col-2">
                                                                <form action="{{ route('delete_bolo_formulas_route')}}" method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <input type="text" name="id" value="{{ $recheio->id }}" hidden>
                                                                    <input type="text" name="bolo" value="{{ $bolo->nome }}" hidden>
                                                                    <input type="text" name="item" value="recheio" hidden>
                                                                    <button type="submit" class="border-0" style="background-color:transparent" >
                                                                        <i class="fas fa-times-circle text-danger"></i> 
                                                                    </button>                                              
                                                                </form>
                                                            </div>    
                                                        </div>
                                                    @endif
                                                @endforeach
                        
                                                <form action="{{ route('insert_bolo_formulas_route') }}" method="POST">
                                                    @csrf
                                                    <div class="row" id="{{ $bolo->id }}_recheio" hidden>
                                                       <div class="col-10 p-1" >
                                                           <select class="form-select" name="receita_id">
                                                               @foreach ($add_recheios as $add_recheio)
                                                                   <option value="{{ $add_recheio->id }}">{{ $add_recheio->nome }}</option>
                                                               @endforeach
                                                           </select>
                                                        </div>
                                                        <div class="col-2 p-1">
                                                           <button type="submit" class="border-0 btn-submit" style="background-color:transparent" >
                                                               <i class="fas fa-check text-primary fa-2x"></i> 
                                                           </button>                                        
                                                       </div>  
                                                    </div>
                                                   <input type="text" name="bolo" value="{{ $bolo->nome }}" hidden>
                                                   <input type="text" name="item" value="recheio" hidden>                                             
                                                   <input type="text" name="bolo_id" value="{{ $bolo->id }}" hidden>
                                                   
                                               </form>
                                               <div class="row btn_add_produto " referencia="{{ $bolo->id }}_recheio">
                                                   <div class="btn mt-2 text-start">
                                                       <i class="fas fa-plus-circle mr-1 fa-medium text-primary"></i>
                                                       <span class="texto-secondario">Add recheio</span>
                                                   </div>                                
                                               </div>                                          
                                            </div>
                                        </div>
                        
                                    </div>
                        
                                </div>   
        
                                <div class="accordion" id="accordion_cobertura_{{ $bolo->id }}">
        
                                    <div class="accordion-item">
                        
                                        <h2 class="accordion-header" id="heading_cobertura_{{ $bolo->id }}">
                                            <button 
                                                class="<?php echo $c7;?>" 
                                                type="button" data-bs-toggle="collapse" 
                                                aria-expanded="false" 
                                                aria-controls="collapseTwo"
                                                data-bs-target="#collapse_cobertura_{{ $bolo->id }}" >
                                                Cobertura
                                            </button>
                                        </h2>
                        
                                        <div id="collapse_cobertura_{{ $bolo->id }}" 
                                            class="<?php echo $c8;?>" 
                                            aria-labelledby="heading_cobertura_{{ $bolo->id }}"
                                            data-bs-parent="#accordion_cobertura_{{ $bolo->id }}">
                        
                                            <div class="accordion-body container">
                        
                                                @foreach ($coberturas as $cobertura)
                                                    @if ($cobertura->bolo_id == $bolo->id)
                                                        <div class="row">
                                                            <div class="col-10">{{ $cobertura->nome }}</div>
                                                            <div class="col-2">
                                                                <form action="{{ route('delete_bolo_formulas_route')}}" method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <input type="text" name="id" value="{{ $cobertura->id }}" hidden>
                                                                    <input type="text" name="bolo" value="{{ $bolo->nome }}" hidden>
                                                                    <input type="text" name="item" value="cobertura" hidden>
                                                                    <button type="submit" class="border-0" style="background-color:transparent" >
                                                                        <i class="fas fa-times-circle text-danger"></i> 
                                                                    </button>                                              
                                                                </form>
                                                            </div>    
                                                        </div>
                                                    @endif
                                                @endforeach
                        
                                                <form action="{{ route('insert_bolo_formulas_route') }}" method="POST">
                                                    @csrf
                                                    <div class="row" id="{{ $bolo->id }}_cobertura" hidden>
                                                       <div class="col-10 p-1" >
                                                           <select class="form-select" name="receita_id">
                                                               @foreach ($add_coberturas as $add_cobertura)
                                                                   <option value="{{ $add_cobertura->id }}">{{ $add_cobertura->nome }}</option>
                                                               @endforeach
                                                           </select>
                                                        </div>
                                                        <div class="col-2 p-1">
                                                           <button type="submit" class="border-0 btn-submit" style="background-color:transparent" >
                                                               <i class="fas fa-check text-primary fa-2x"></i> 
                                                           </button>                                        
                                                       </div>  
                                                    </div>
                                                   <input type="text" name="bolo" value="{{ $bolo->nome }}" hidden>
                                                   <input type="text" name="item" value="cobertura" hidden>                                             
                                                   <input type="text" name="bolo_id" value="{{ $bolo->id }}" hidden>
                                                   
                                               </form>
                                               <div class="row btn_add_produto " referencia="{{ $bolo->id }}_cobertura">
                                                   <div class="btn mt-2 text-start">
                                                       <i class="fas fa-plus-circle mr-1 fa-medium text-primary"></i>
                                                       <span class="texto-secondario">Add cobertura</span>
                                                   </div>                                
                                               </div>                                       
                                            </div>
                                        </div>
                        
                                    </div>
                        
                                </div>   
        
                            </div>
                        </div>
        
                    </div>      
                </div>

                <div class="col-1 pl-0 m-0">
                    <form action="{{ route('delete_bolos_route')}}" method="POST">
                    @csrf
                    @method('delete')
                        <input type="text" name="id" value="{{ $bolo->id }}" hidden>
                        <button type="submit" class="border-0" style="background-color:transparent" >
                            <i class="fas fa-times-circle text-danger fa-lg pt-3"></i> 
                        </button>  
                    </form>                                            
                </div>                
            </div>


        </div> 

    @endforeach

    @push('script_simulador')
        <script>
            $(".btn_add_produto").click(function(){
                
                $(this).attr('hidden', true);
                var referencia = "#" + $(this).attr('referencia');
                $(referencia).removeAttr("hidden");
            });
        </script>  

    @endpush


@endsection