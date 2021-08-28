@extends('layouts.simulador')

@push('title')
  Simulador
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
            data-bs-content="Nome da Receita">
            <button 
              class="btn texto-secondario" 
              type="button" 
              disabled
              style="opacity:100;">
              Nome
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
            data-bs-content="Simular">
            <button 
              class="btn text-decoration-none texto-secondario" 
              type="button" 
              disabled
              style="opacity:100;">
              <i class="nav-icon fas fa-fw fa-calculator"></i>
            </button>
          </span>             
        </th>        
      </tr>
    </thead>

    <tbody>
      @foreach ($receitas as $receita)
        <tr class="py-2 texto-secondario">
          <td id="td_receita_id" valor="{{ $receita->id }}" hidden></td>
          <td class="align-middle px-0">{{ $receita->nome }}</td>
          <td class="align-middle px-0" id="td_custo"><?php echo round($receita->custo,0); ?></td>

          <?php 
            $margem = 0; 
            $valor = $receita->custo;
            $lucro = 0; 
          ?>      
          @foreach ($catalogos as $catalogo)
              @if ($catalogo->receita_id == $receita->id)
                <?php 
                  $margem = $catalogo->margem; 
                  $lucro = $catalogo->lucro; 
                  $valor = $catalogo->valor; 
                ?>
                @break
              @endif
          @endforeach

          <td id="td_margem" valor="<?php echo $margem;?>" hidden></td>
          <td 
            class="align-middle px-0 " 
            valor="<?php echo round($lucro,0);?>" 
            id="td_lucro">
            <div class="segunda pdy-1 h-100">
              <?php echo round($lucro,0);?>
            </div>
          </td>

          <td class="align-middle px-0 " id="td_valor"><?php echo round($valor,0);?></td>
          <td class="align-middle px-0 ">        
            <button 
              type="submit"
              class="btn simulador" 
              data-bs-toggle="modal"
              href="#modalSimulador"
              role="button"
              style="background-color: transparent">
              <i class="nav-icon fas fa-fw fa-calculator texto-secondario"></i>
            </button>
          </td>
        </tr>
        
      @endforeach      
    </tbody>
  </table>
</div>


<div class="modal fade texto" id="modalSimulador" aria-hidden="true" aria-labelledby="modalSimuladorLabel" tabindex="-1">
  <div class="modal-dialog modal-xl">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalSimuladorLabel">Simulador</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('insert_simulador_route') }}" method="POST">
              @csrf
              <div class="modal-body">

                  <input type="text" name="receita_id" id="receita_id" class="form-control"  hidden>
                  <input type="text" name="custo" id="custo" class="form-control"  hidden>

                  <input type="number" name="lucro" id="lucro" class="form-control"  hidden>
                  <input type="number" name="valor" id="valor" class="form-control"  hidden>                  
                  <div class="row">

                    <div class="col-2 text-center">
                        <div>ML</div>
                        <input type="range" min="0" max="500" value="0" class="slider" name="margem" id="margem" step="10">
                        <div id="demo"></div>                    
                    </div>

                    <div class="col-8 text-center">

                      <div class="card">
                        <div class="card-body">
                          <div class="card mb-3">
                            <div class="card-header">
                              <span>LUCRO</span>
                            </div>
                            <div class="card-text">
                              <span name="lucro_span" id="lucro_span" value="0"></span>
                            </div>                                              
                          </div>
    
                          <div class="card my-0">
                            <div class="card-header ">
                              <span>PREÇO</span>
                            </div>
                            <div class="card-text">
                              <span name="valor_span" id="valor_span" value="0"></span>
                            </div>                                              
                          </div>                              
                        </div>
                      </div>
                  
                    </div>
                    
                    <div class="col-2 text-center px-0">
                      <span class="text-center">ML</span>
                      <div class="card mt-2">
                          
                          <div class="btn btn-primary" id="up">
                            <i class="fa fa-angle-up" aria-hidden="true"></i>
                          </div>

                          <div id="demo2" class="py-3"></div> 

                          <div class="btn btn-primary" id="down">
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                          </div>                                                    

                      </div>

                    </div>
                  </div>

                  
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Registrar</button>
              </div>
          </form>
      </div>
  </div>
</div>

@endsection

@push('script_simulador')

    <script>

      var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
      })

      $('.simulador').click(function(){
          var $td_receita_id = $(this).closest('tr').find('#td_receita_id').attr('valor');
          var $td_custo = $(this).closest('tr').find('#td_custo').html();
          var $td_margem = $(this).closest('tr').find('#td_margem').attr('valor');
          var $td_lucro = $(this).closest('tr').find('#td_lucro').attr('valor');
          var $td_valor = $(this).closest('tr').find('#td_valor').html();

          $('#receita_id').attr('value', $td_receita_id);
          $('#custo').attr('value', $td_custo);
          $('#margem').attr('value', $td_margem);
          $('#lucro').attr('value', $td_lucro);
          $('#lucro_span').html($td_lucro);
          $('#valor').attr('value', $td_valor);
          $('#valor_span').html($td_valor);
          atualiza_simulador($td_margem);
      });

      function atualiza_simulador(td_margem){
        var up = document.getElementById("up");
        var down = document.getElementById("down");
        var slider = document.getElementById("margem");
        slider.value = td_margem;
        var output = document.getElementById("demo");
        var output2 = document.getElementById("demo2");
        var custo = document.getElementById("custo");
        var custo_inicial = custo.value;
        var lucro = document.getElementById("lucro");
        var lucro_span = document.getElementById("lucro_span");
        var valor = document.getElementById("valor");
        var valor_span = document.getElementById("valor_span");

        output.innerHTML = slider.value + '%';
        output2.innerHTML = slider.value + '%';

        slider.oninput = function() {
          output.innerHTML = this.value + '%';
          output2.innerHTML = this.value + '%';
          var custo_atualizado = Math.round(((parseInt(this.value) / 100) + 1) * parseInt(custo_inicial));
          valor.value = custo_atualizado;
          valor_span.innerHTML = custo_atualizado;
          lucro.value = custo_atualizado - custo_inicial;
          lucro_span.innerHTML = custo_atualizado - custo_inicial;
        }          
        
        up.onclick = function(){
          slider.value = parseInt(slider.value) + 10;
          output.innerHTML = slider.value + '%';
          output2.innerHTML = slider.value + '%';
          var custo_atualizado = Math.round(((parseInt(slider.value) / 100) + 1) * parseInt(custo_inicial));
          valor.value = custo_atualizado;
          valor_span.innerHTML = custo_atualizado;
          lucro.value = custo_atualizado - custo_inicial;     
          lucro_span.innerHTML = custo_atualizado - custo_inicial;        
        }

        down.onclick = function(){
          slider.value = parseInt(slider.value) - 10;
          output.innerHTML = slider.value + '%';
          output2.innerHTML = slider.value + '%';
          var custo_atualizado = Math.round(((parseInt(slider.value) / 100) + 1) * parseInt(custo_inicial));
          valor.value = custo_atualizado;
          valor_span.innerHTML = custo_atualizado;
          lucro.value = custo_atualizado - custo_inicial; 
          lucro_span.innerHTML = custo_atualizado - custo_inicial; 
        }

      }
       

    </script>

@endpush