@extends('layouts.simulador')

@push('title')
    Produtos
@endpush 

@section('content')

    <div 
        class="btn mb-2 primeira w-100"
        data-bs-toggle="modal"
        href="#modalInsert"
        role="button">
        <i class="fas fa-plus-circle mr-1"></i>
        <span>Adicionar Produto</span>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                  data-bs-content="Nome do Produto">
                  <button 
                    class="btn texto-secondario" 
                    type="button" 
                    disabled
                    style="opacity:100;">
                    Produto
                  </button>
                </span>               
              </th>
              <th class=" px-0">
                <span 
                  data-bs-placement="top" 
                  tabindex="0" 
                  data-bs-toggle="popover" 
                  data-bs-trigger="hover focus" 
                  data-bs-content="Quantidade">
                  <button 
                    class="btn texto-secondario" 
                    type="button" 
                    disabled
                    style="opacity:100;">
                    QTD
                  </button>
                </span>               
              </th>        
              <th class=" px-0">
                <span 
                  data-bs-placement="top" 
                  tabindex="0" 
                  data-bs-toggle="popover" 
                  data-bs-trigger="hover focus" 
                  data-bs-content="Unidade de medida">
                  <button 
                    class="btn texto-secondario" 
                    type="button" 
                    disabled
                    style="opacity:100;">
                    UN
                  </button>
                </span>               
              </th>
              <th class=" px-0">
                <span 
                  data-bs-placement="top" 
                  tabindex="0" 
                  data-bs-toggle="popover" 
                  data-bs-trigger="hover focus" 
                  data-bs-content="Custo em R$">
                  <button 
                    class="btn text-decoration-none texto-secondario" 
                    type="button" 
                    disabled
                    style="opacity:100;">
                    R$
                  </button>
                </span>             
              </th>
              <th class=" px-0">
                <span 
                  data-bs-placement="top" 
                  tabindex="0" 
                  data-bs-toggle="popover" 
                  data-bs-trigger="hover focus" 
                  data-bs-content="Editar Produto">
                  <button 
                    class="btn text-decoration-none texto-secondario" 
                    type="button" 
                    disabled
                    style="opacity:100;">
                    <i class="nav-icon fas fa-fw fa-edit"></i>
                  </button>
                </span>             
              </th>      
              <th class=" px-0">
                <span 
                  data-bs-placement="top" 
                  tabindex="0" 
                  data-bs-toggle="popover" 
                  data-bs-trigger="hover focus" 
                  data-bs-content="Excluir Produto">
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
            @foreach ($produtos as $produto)
                <tr class="texto-secondario">
                    
                    <td id="td_id" hidden valor="{{ $produto->id }}"></td>
                    <td id="td_produto">{{ $produto->produto }}</td>
                    <td id="td_quantidade">{{ $produto->quantidade }}</td>
                    <td id="td_unidade">{{ $produto->unidade }}</td>
                    <td id="td_custo">{{ $produto->custo }}</td>
                    <td>                   
                        <button 
                            type="submit" 
                            class="border-0 btn_up" 
                            style="background-color:transparent" 
                            data-bs-toggle="modal"
                            href="#modalUpdate"
                            role="button">
                            <i class="fas fa-pen text-warning "></i> 
                        </button>
                    </td>                    
                    <td>
                        <form method="POST" action="{{ route('delete_produtos_route', [ 'id'=> $produto->id ]) }}">
                            @csrf
                            @method('delete')      
                                <button type="submit" class="border-0" style="background-color:transparent" >
                                    <i class="fas fa-times-circle text-danger"></i> 
                                </button>
                        </form>
                    </td>
                </tr>
                
            @endforeach 
        </tbody>
        </table>
    </div>
    <div class="modal fade texto" id="modalInsert" aria-hidden="true" aria-labelledby="modalInsertLabel" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInsertLabel">Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('insert_produtos_route') }}" method="POST">
                    @csrf
                    <div class="modal-body">        

                        <span class="form-text" for="produto">Produto</span>
                        <input type="text" name="produto" id="produto" class="form-control">
                        <span class="form-text" for="quantidade">Quantidade</span>
                        <input type="text" name="quantidade" id="quantidade" class="form-control">
                        <span class="form-text" for="unidade">Unidade</span>
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="unidade" id="unidade">
                            <option selected></option>
                            <option value="Kg">Kg</option>
                            <option value="g">g</option>
                            <option value="L">L</option>
                            <option value="ml">ml</option>
                        </select>                   
                        <span class="form-text" for="custo">Custo</span>
                        <input type="number" name="custo" id="custo" class="form-control" step="any">                                                            
                   
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn primeira" data-bs-dismiss="modal">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
      

    <div class="modal fade texto" id="modalUpdate" aria-hidden="true" aria-labelledby="modalUpdateLabel" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUpdateLabel">Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('update_produtos_route') }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <input type="text" name="id_up" id="id_up" class="form-control" hidden>
                        <span class="form-text" for="produto_up">Produto</span>
                        <input type="text" name="produto_up" id="produto_up" class="form-control">
                        <span class="form-text" for="quantidade_up">Quantidade</span>
                        <input type="text" name="quantidade_up" id="quantidade_up" class="form-control">
                        <span class="form-text" for="unidade_up">Unidade</span>
                        <select class="form-select" aria-label="Floating label select example" name="unidade_up" id="unidade_up">
                            <option selected></option>
                            <option value="Kg">Kg</option>
                            <option value="g">g</option>
                            <option value="L">L</option>
                            <option value="ml">ml</option>
                        </select>                   
                        <span class="form-text" for="custo_up">Custo</span>
                        <input type="number" name="custo_up" id="custo_up" class="form-control" step="any">                                                            
                   
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn primeira" data-bs-dismiss="modal">Alterar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script_simulador')
        
        <script>


            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            })

            $('.btn_up').click(function(){
                var $td_id = $(this).closest('tr').find('#td_id').attr('valor');
                var $td_produto = $(this).closest('tr').find('#td_produto').html();
                var $td_quantidade = $(this).closest('tr').find('#td_quantidade').html();
                var $td_unidade = $(this).closest('tr').find('#td_unidade').html();
                var $td_custo = $(this).closest('tr').find('#td_custo').html();

                $('#id_up').val($td_id);
                $('#produto_up').val($td_produto);
                $('#quantidade_up').val($td_quantidade);
                $('#unidade_up > option').each(function(){
                    if($(this).val() == $td_unidade){
                        $(this).attr('selected', 'selected');
                    }else{
                        $(this).attr('teste', $(this).val());
                    }
                    
                });
                $('#custo_up').val($td_custo);

            });
        </script>

    @endpush

@endsection