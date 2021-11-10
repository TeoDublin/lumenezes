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

<ul class="nav nav-tabs mb-4">
  <li class="nav-item">
    <a class="nav-link" href="/simulador">Doces</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active fw-bold" aria-current="page" href="#">Bolos</a>
  </li>     
</ul>
<div class="container mx-0">
  <form action="{{ route('simulador_bolos_add_route') }}" method="POST">
    @csrf
    <div class="row">
      <div class="col-10 mx-0 p-0">
        <select class="form-select text-center" name="receita">
          @foreach ($receitas as $receita)
            <option value="{{ $receita->id }}">{{ $receita->nome }}</option>
          @endforeach
        </select>          
      </div>
  
      <div class="col-2 mx-0 pr-0 pl-1">
        <button type="submit" class="btn mb-2 primeira w-100">
          <span>ADD</span>
        </button>     
      </div>
    </div>
  </form>
</div>




@if ($errors->any())
  <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
          <span>{{ $error }}</span>
      @endforeach
  </div>
@endif   

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
            data-bs-content="Detalhes">
            
            <button 
              class="btn text-decoration-none texto-secondario" 
              type="button" 
              disabled
              style="opacity:100;">
              <i class="nav-icon fas fa-fw fa-search"></i>
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
      @foreach ($catalogos as $catalogo )
        <tr class="py-2 texto-secondario">
          <td id="td_receita_id" valor="{{ $catalogo->id }}" hidden></td>
          <td class="align-middle px-0">{{ $catalogo->nome }}</td>
          <td class="align-middle px-0" id="td_custo"><?php echo round($catalogo->custo,0); ?></td>
          <td class="align-middle px-0" id="td_lucro">
            <div class="segunda pdy-1 h-100"><?php echo round($catalogo->lucro,0);?></div>
          </td>
          <td class="align-middle px-0 " id="td_valor"><?php echo round($catalogo->valor,0);?></td>

          <form action="{{ route('simulador_detalhe_bolos_route') }}" method="POST">
            @csrf
            <td class="align-middle px-0 ">        
              <input type="text" name="id" value="{{ $catalogo->id }}" hidden>
              <button 
                type="submit"
                class="btn" 
                style="background-color: transparent">
                <i class="fas fa-search text-warning"></i>
              </button>
            </td>
          </form>

          <form action="{{ route('delete_simulador_bolos_route') }}" method="POST">
            @csrf
            @method('delete')
            <td class="align-middle px-0 ">   
              <input type="text" name="id" value="{{ $catalogo->id }}" hidden>     
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
    </tbody>
  </table>
</div>




@endsection

@push('script_simulador')

    <script>

      var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
      })
      
    </script>

@endpush