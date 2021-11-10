@extends('layouts.add')

@push('title')
  Nova Simulação
@endpush

@push('back_to')
  /simulador_bolos
@endpush
@section('content')

<style>
  /* The slider itself */
  .slider {
    -webkit-appearance: slider-vertical;
    width: 20px;
    height: 130px;
  }

</style>

@if ($errors->any())
  <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
          <span>{{ $error }}</span>
      @endforeach
  </div>
@endif   

@php
    $massa_json = array();
@endphp

@foreach ($bolos as $bolo)
  <form action="{{ route('insert_simulador_bolos_route') }}" method="POST">
    @csrf
    <div class="text-center container card">
      <div class="px-1 card-body">
        
          <input type="text" class="form-control card-title mb-3 text-center" name='nome' value="{{ $bolo->nome }}" style="font-size: 20px; background-color: rgba(0,0,0,.03);">          
          <input type="text" class="form-control" name="bolo_id" value="{{ $bolo->id }}" hidden>
          <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Componentes
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
      
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-header">Massa</h5>
            
                      <div class="row">
                        <div class="col-6">
                          <span class="form-text">Nome</span>
                        </div>
                        <div class="col-3 pl-0">
                          <span class="form-text">Peso</span>
                        </div>
                        <div class="col-3 pl-0">
                          <span class="form-text">Un</span>   
                        </div>
                      </div>    

                      @foreach ($massas as $massa)
                        
                        <div class="row mb-2">
                          <div class="col-6"> 
                            <span
                            data-bs-placement="top" 
                            tabindex="0" 
                            data-bs-toggle="popover" 
                            data-bs-trigger="hover focus" 
                            data-bs-content="{{ $massa->nome }}"                        
                            >
                            <input type="text" class="form-control texto" value="{{ $massa->nome }}" disabled="disabled">                         
                            </span>               
                          </div>
                          <div class="col-3 pl-0">
                            <input type="text" class="form-control texto peso" value="{{ $massa->peso }}" step="any">  
                          </div>
                          <div class="col-3 pl-0">
                            <select class="form-select texto unidade"value="">
                              @switch($massa->unidade_peso)
                                  @case('Kg')
                                      <option value="Kg" selected>Kg</option>
                                      <option value="g">g</option>
                                      @break
                                  @case('g')
                                      <option value="Kg">Kg</option>
                                      <option value="g" selected>g</option>
                                      @break
                                  @default
                                    <option value="Kg">Kg</option>
                                    <option value="g">g</option>
                              @endswitch
                              
                            </select>          
                          </div>

                          <input type="text" class="form-control texto" value="{{ $massa->receita_custo }}" step="any" hidden>
                          <input type="text" class="form-control texto" value="{{ $massa->peso_mili }}" step="any" hidden>
                        </div>    
                        @php                            
                            array_push($massa_json, ['tipo'=>'massa', 'nome'=>"$massa->nome", 'peso'=>$massa->peso_mili / 1000 , 'custo' => $massa->receita_custo]);
                        @endphp
                      @endforeach      
                    </div>
                  </div>
            
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-header">Recheio</h5>
            
                      <div class="row">
                        <div class="col-6">
                          <span class="form-text">Nome</span>
                        </div>
                        <div class="col-3 pl-0">
                          <span class="form-text">Peso</span>
                        </div>
                        <div class="col-3 pl-0">
                          <span class="form-text">Un</span>   
                        </div>
                      </div>    

                      @foreach ($recheios as $recheio)
                        
                      <div class="row mb-2">
                          <div class="col-6"> 
                            <span
                            data-bs-placement="top" 
                            tabindex="0" 
                            data-bs-toggle="popover" 
                            data-bs-trigger="hover focus" 
                            data-bs-content="{{ $recheio->nome }}"                        
                            >
                            <input type="text" class="form-control texto" value="{{ $recheio->nome }}" disabled="disabled">                         
                            </span>               
                        </div>
                        <div class="col-3 pl-0">
                          <input type="text" class="form-control texto peso" value="{{ $recheio->peso }}" step="any">  
                        </div>
                        <div class="col-3 pl-0">
                          <select class="form-select texto unidade"value="">
                            @switch($recheio->unidade_peso)
                                @case('Kg')
                                    <option value="Kg" selected>Kg</option>
                                    <option value="g">g</option>
                                    @break
                                @case('g')
                                    <option value="Kg">Kg</option>
                                    <option value="g" selected>g</option>
                                    @break
                                @default
                                  <option value="Kg">Kg</option>
                                  <option value="g">g</option>
                            @endswitch
                            
                          </select>          
                        </div>
                        <input type="text" class="form-control texto" value="{{ $recheio->receita_custo }}" step="any" hidden>
                        <input type="text" class="form-control texto" value="{{ $recheio->peso_mili }}" step="any" hidden>
                      </div>    
                      @php                            
                          array_push($massa_json, ['tipo'=>'recheio', 'nome'=>"$recheio->nome", 'peso'=>$recheio->peso_mili/1000 , 'custo' => $recheio->receita_custo]);
                      @endphp            
                      @endforeach      
                    </div>
                  </div> 
                  
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-header">Cobertura</h5>
            
                      <div class="row">
                        <div class="col-6">
                          <span class="form-text">Nome</span>
                        </div>
                        <div class="col-3 pl-0">
                          <span class="form-text">Peso</span>
                        </div>
                        <div class="col-3 pl-0">
                          <span class="form-text">Un</span>   
                        </div>
                      </div>  

                      @foreach ($coberturas as $cobertura)
                        
                      <div class="row mb-2">
                        <div class="col-6"> 
                          <span
                          data-bs-placement="top" 
                          tabindex="0" 
                          data-bs-toggle="popover" 
                          data-bs-trigger="hover focus" 
                          data-bs-content="{{ $cobertura->nome }}"                        
                          >
                          <input type="text" class="form-control texto" value="{{ $cobertura->nome }}" disabled="disabled">                         
                          </span>               
                        </div>
                        <div class="col-3 pl-0">
                          <input type="text" class="form-control texto peso" value="{{ $cobertura->peso }}" step="any">  
                        </div>
                        <div class="col-3 pl-0">
                          <select class="form-select texto unidade"value="">
                            @switch($cobertura->unidade_peso)
                                @case('Kg')
                                    <option value="Kg" selected>Kg</option>
                                    <option value="g">g</option>
                                    @break
                                @case('g')
                                    <option value="Kg">Kg</option>
                                    <option value="g" selected>g</option>
                                    @break
                                @default
                                  <option value="Kg">Kg</option>
                                  <option value="g">g</option>
                            @endswitch
                            
                          </select>          
                        </div>
                        <input type="text" class="form-control texto" value="{{ $cobertura->receita_custo }}" step="any" hidden>
                        <input type="text" class="form-control texto" value="{{ $cobertura->peso_mili }}" step="any" hidden>
                      </div>    
                      @php                            
                          array_push($massa_json, ['tipo'=>'cobertura','nome'=>"$cobertura->nome", 'peso'=>$cobertura->peso_mili/1000 , 'custo' => $cobertura->receita_custo]);
                      @endphp                      
                      @endforeach      
                    </div>
                  </div>    

                </div>
              </div>
            </div>
          </div>

          <div class="card my-3">
            <div class="card-body">
              <h5 class="card-header">Total</h5>
    
              <div class="row">
                <div class="col-8">
                  <span class="form-text">Peso</span>
                  <input type="text" class="form-control texto text-center" name="peso" value="{{ $bolo->peso}}" readonly >
                </div>
                <div class="col-4 pl-0">
                  <span class="form-text">Un</span>
                  <input type="text" class="form-control texto text-center" name="unidade_peso" value="{{ $bolo->unidade_peso}}" readonly >
                </div>
              </div> 
              
              <span class="form-text" for='margem'>Margem de Lucro</span>
              <input type="number" class="form-control text-center" name='margem' value="100" step="any">  
              
              <span class="form-text" for='custo'>Valor de Custo</span>
              <input type="number" class="form-control text-center" name='custo' value="{{ $bolo->bolo_custo }}" readonly  step="any">    
    
              <span class="form-text" for='lucro'>Lucro</span>
              <input type="number" class="form-control text-center" name='lucro' value="{{ $bolo->bolo_custo }}" step="any">    
    
              <span class="form-text" for='valor'>Valor de Venda</span>
              <input type="number" class="form-control text-center" name='valor' value="{{ $bolo->bolo_custo * 2 }}" step="any">
              <input type="text" class="form-control" name="componentes" value="[]" hidden>
            </div>
          </div>
                        
          <button type="submit" class="btn my-3 primeira w-100">
            <i class="fas fa-plus-circle mr-1"></i>
            <span>Registrar</span>
          </button>  

      </div>
    </div>  
  </form>


  @push('script_simulador')

      <script>

        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
          return new bootstrap.Popover(popoverTriggerEl)
        })
        
      </script>

      <script>
        
        var peso =  {{ $bolo->peso }};
        var unidade_peso = "{{ $bolo->unidade_peso }}";
        var custo = {{ $bolo->bolo_custo }};
        var custo_peso = custo / peso;
        let componentes_array = <?php echo json_encode($massa_json); ?>; 

        $('input[name="margem"]').change(function(){
          var margem = parseFloat($('input[name="margem"]').val());
          var lucro = parseFloat($('input[name="lucro"]').val());
          var valor = parseFloat($('input[name="valor"]').val());
          var custo_local = parseFloat($('input[name="custo"]').val());

          margem = margem / 100;
          lucro = (custo_local * margem);
          valor = (custo_local + lucro);

          lucro = lucro.toFixed(2);
          valor = valor.toFixed(2);
          $('input[name="lucro"]').val(lucro);
          $('input[name="valor"]').val(valor);          
        });
    
        $('input[name="lucro"]').change(function(){
            var margem = parseFloat($('input[name="margem"]').val());
            var lucro = parseFloat($('input[name="lucro"]').val());
            var valor = parseFloat($('input[name="valor"]').val());
            var custo_local = parseFloat($('input[name="custo"]').val()); 

            margem = lucro / custo_local;
            valor = (custo_local + lucro);

            valor = valor.toFixed(2);
            $('input[name="margem"]').val(Math.round(margem * 100));
            $('input[name="valor"]').val(valor);          
        });

        $('input[name="valor"]').change(function(){
            var margem = parseFloat($('input[name="margem"]').val());
            var lucro = parseFloat($('input[name="lucro"]').val());
            var valor = parseFloat($('input[name="valor"]').val());
            var custo_local = parseFloat($('input[name="custo"]').val()); 

            lucro = (valor - custo_local);
            margem = lucro / custo_local;

            lucro = lucro.toFixed(2);
            $('input[name="margem"]').val(Math.round(margem * 100));
            $('input[name="lucro"]').val(lucro);
        
        });   

        $('.unidade').change(function(){
          var margem = parseFloat($('input[name="margem"]').val());

          var nome = $(this).closest('.row').find('div').eq(0).find('span').eq(0).find('input').eq(0).val();
          var unidade = $(this).val();
          var custo_componente = parseFloat($(this).closest('.row').find('input').eq(2).val());
          var peso_mili = parseFloat($(this).closest('.row').find('input').eq(3).val());
          var peso_componente = parseFloat($(this).closest('.row').find('div').eq(1).find('input').val());
          var custo_local = parseFloat($('input[name="custo"]').val());
          peso_mili = peso_mili / 1000;
          var custo_peso_mili = custo_componente / peso_mili;

          if (unidade=='g') {
            custo_local = custo_local - (peso_componente * custo_peso_mili);         
            peso = peso - peso_componente;
            peso_componente = peso_componente /1000;
          } else {
            peso_componente = peso_componente /1000;
            custo_local = custo_local - (peso_componente * custo_peso_mili);              
            peso = peso - peso_componente;
            peso_componente = peso_componente * 1000;

          }

          peso = peso + peso_componente;
          custo_local = custo_local + (peso_componente * custo_peso_mili);  

          componentes(nome, peso_componente, peso_componente * custo_peso_mili);

          margem = margem / 100;
          var lucro = (custo_local * margem);
          var valor = (custo_local + lucro);

          peso = peso.toFixed(2);
          custo_local = custo_local.toFixed(2);
          lucro = lucro.toFixed(2);
          valor = valor.toFixed(2);    

          $('input[name="peso"]').val(peso);     
          $('input[name="custo"]').val(custo_local);    
          $('input[name="lucro"]').val(lucro);
          $('input[name="valor"]').val(valor);              
        });

        $('.peso').on('focus', function(){
           peso_antes = parseFloat($(this).val());

        }).change(function(){
          var margem = parseFloat($('input[name="margem"]').val());

          var nome = $(this).closest('.row').find('div').eq(0).find('span').eq(0).find('input').eq(0).val();
          var peso_depois = parseFloat($(this).val());
          var unidade = $(this).closest('div').nextAll('div').first().find('select option:selected').val();
          var custo_componente = parseFloat($(this).closest('.row').find('input').eq(2).val());
          var peso_mili = parseFloat($(this).closest('.row').find('input').eq(3).val());
          var custo_local = parseFloat($('input[name="custo"]').val());
          peso_mili = peso_mili / 1000;
          var custo_peso_mili = custo_componente / peso_mili;

          if (unidade=='g') {
            
            peso_antes = peso_antes / 1000;
            peso_depois = peso_depois / 1000;
            peso =  peso - peso_antes;
            peso = peso + peso_depois;        
               
          }else{
            peso =  peso - peso_antes;
            peso = peso + peso_depois;       
          }                

          custo_local = custo_local - (peso_antes * custo_peso_mili);
          custo_local = custo_local + (peso_depois * custo_peso_mili);

          componentes(nome, peso_depois, peso_depois * custo_peso_mili);

          margem = margem / 100;
          var lucro = (custo_local * margem);
          var valor = (custo_local + lucro);

          peso = peso.toFixed(2);
          custo_local = custo_local.toFixed(2);
          lucro = lucro.toFixed(2);
          valor = valor.toFixed(2);

          $('input[name="peso"]').val(peso);     
          $('input[name="custo"]').val(custo_local);    
          $('input[name="lucro"]').val(lucro);
          $('input[name="valor"]').val(valor);   

        });        

        function componentes(nome, peso, custo){ 
 
          objIndex = componentes_array.findIndex((componentes_array => componentes_array.nome == nome));
          componentes_array[objIndex].peso = peso;
          componentes_array[objIndex].custo = custo;
          $('input[name="componentes"]').attr('value',JSON.stringify(componentes_array));
        };

        $(document).ready(function(){
          $('input[name="componentes"]').attr('value', JSON.stringify(componentes_array));
        });

      </script>
  @endpush

@endforeach

@endsection