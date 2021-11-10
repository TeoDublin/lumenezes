@extends('layouts.add')

@push('title')
  Nova Simulação
@endpush

@push('back_to')
  /simulador
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

<form action="{{ route('insert_simulador_route') }}" method="POST">
  @csrf

  <div class="px-3 text-center">
    @foreach ($receitas as $receita)

      <span class="form-text" for='nome'>Nome</span>
      <input type="text" class="form-control text-center" name='nome' value="{{ $receita->nome }}">
    
      <span class="form-text" for='peso'>Peso</span>
      <input type="number" class="form-control text-center" name='peso' value="{{ $receita->peso }}" step="any">
    
      <span class="form-text" for='unidade'>Unidade Peso</span>
      <select class="form-select text-center" name="unidade" value="">
        @switch($receita->unidade_peso)
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

      <span class="form-text" for='quantidade'>Unidades</span>
      <input type="number" class="form-control text-center" name='quantidade' value="100" step="any">      

      <span class="form-text" for='margem'>Margem de Lucro</span>
      <input type="number" class="form-control text-center" name='margem' value="100" step="any">  
      
      <span class="form-text" for='custo'>Valor de Custo</span>
      <input type="number" class="form-control text-center" name='custo' value="{{ $receita->custo }}" disabled="disabled" step="any">    
      <input type="number" class="form-control" name='custo_interno' value="{{ $receita->custo }}" hidden> 

      <span class="form-text" for='lucro'>Lucro</span>
      <input type="number" class="form-control text-center" name='lucro' value="{{ $receita->custo }}" step="any">    

      <span class="form-text" for='valor'>Valor de Venda</span>
      <input type="number" class="form-control text-center" name='valor' value="{{ $receita->custo * 2}}" step="any">

      <input type="text" name="id" value={{ $receita->id }} hidden>
    @endforeach

    <button type="submit" class="btn my-3 primeira w-100">
      <i class="fas fa-plus-circle mr-1"></i>
      <span>Registrar</span>
    </button>  

  </div>


</form>


@push('script_simulador')

  
@foreach ($receitas as $receita)
  <script>
      
      var custo = {{ $receita->custo }};
      var peso =  {{ $receita->peso }};
      var custo_peso = custo / peso;
      var unidade_peso =  '{{ $receita->unidade_peso }}';
      var fator = 1;

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

      $('input[name="peso"]').change(function(){
          peso = parseFloat($('input[name="peso"]').val());
          var unidade = $('select[name="unidade"] option:selected').val();
          var margem = parseFloat($('input[name="margem"]').val());
          var custo = parseFloat($('input[name="custo"]').val());
          var lucro = parseFloat($('input[name="lucro"]').val());
          var valor = parseFloat($('input[name="valor"]').val());


          var peso_local = peso * fator;
          custo = custo_peso * peso_local;
          margem = margem / 100;
          lucro = (custo * margem);
          valor = (custo + lucro);

          lucro = lucro.toFixed(2);
          valor = valor.toFixed(2);
          custo = custo.toFixed(2);

          $('input[name="custo"]').val(custo);
          $('input[name="custo_interno"]').attr('value', custo);
          $('input[name="lucro"]').val(lucro);
          $('input[name="valor"]').val(valor);          
       
      });      

      $('select[name="unidade"]').change(function(){
          peso = parseFloat($('input[name="peso"]').val());
          var unidade = $('select[name="unidade"] option:selected').val();
          var margem = parseFloat($('input[name="margem"]').val());
          var custo = parseFloat($('input[name="custo"]').val());
          var lucro = parseFloat($('input[name="lucro"]').val());
          var valor = parseFloat($('input[name="valor"]').val());

          if(unidade == unidade_peso ){
            fator = 1;
          }else if(unidade == 'Kg'){
            fator = 1000;
          }else{
            fator = 0.001;
          }

          var peso_local = peso * fator;
          custo = custo_peso * peso_local;
          margem = margem / 100;
          lucro = (custo * margem);
          valor = (custo + lucro);

          lucro = lucro.toFixed(2);
          valor = valor.toFixed(2);
          custo = custo.toFixed(2);

          $('input[name="custo"]').val(custo);
          $('input[name="custo_interno"]').attr('value', custo);
          $('input[name="lucro"]').val(lucro);
          $('input[name="valor"]').val(valor);          
       
      });           
  </script>
@endforeach
  

@endpush

@endsection