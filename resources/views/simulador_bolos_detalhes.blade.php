@extends('layouts.add')

@section('content')
    
    @push('back_to')
        /simulador_bolos
    @endpush
    @push('title')
        Detalhes da Simulação
    @endpush     
          
    <div class="container-fluid">
        <form action="{{ route('simulador_update_bolos_route') }}" method="POST">
            @csrf
            @method('put')

            @foreach ($detalhes as $detalhe)
                <div class="card mb-1">
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="input-group mb-2  text-center">
                                <input type="text" name="id" value="{{ $detalhe->id }}" hidden>
                                <input type="text" name="nome" class="form-control text-center" value="{{ $detalhe->nome }}" aria-describedby="button-addon2">
                                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">
                                    <i class="fas fa-fw fa-sync-alt text-primary"></i>
                                </button>                            
                            </div>  
                        </div>
        
                        
                            <div class="mb-3 text-center">
                                <span>Margem de Lucro</span>
                                <input type="text" class="form-control text-center" value="{{ $detalhe->margem }}%" disabled="disabled">
                            </div>

                            <div class="accordion mb-3" id="totais">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTotais">
                                        <button 
                                            class="accordion-button collapsed" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapseTotais" 
                                            aria-expanded="true" 
                                            aria-controls="collapseTotais">
                                            Totais
                                        </button>
                                    </h2>
                                    <div 
                                        id="collapseTotais" 
                                        class="accordion-collapse collapse" 
                                        aria-labelledby="headingTotais" 
                                        data-bs-parent="#totais">
                                        <div class="accordion-body">
                                            <fieldset disabled>
                                                <div class="row mb-3 px-3">
                                                    <span>Peso</span>
                                                    <input type="text" class="form-control text-center" value="{{ $detalhe->peso }}({{ $detalhe->unidade_peso }})">
                                                </div>
                                                <div class="card">
                                                    <div class="card-body py-1">
                                                        <div class="row mb-1">
                                                            <label class="card-header mb-2 w-100">Custo de Produção</label>
                                                            <div class="col">
                                                                <span>Total</span>
                                                                <input type="text" class="form-control text-center" value=<?php echo number_format((float)$detalhe->custo, 2, '.', ''); ?>>
                                                            </div>
                                                            <div class="col pl-0">
                                                                <span>Por Kg</span>
                                                                <input type="text" class="form-control text-center" value=<?php echo number_format((float)$detalhe->custo_kg, 2, '.', ''); ?>>                                                    
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body py-1">
                                                        <div class="row mb-1">
                                                            <label class="card-header mb-2 w-100">Lucro</label>
                                                            <div class="col">
                                                                <span>Total</span>
                                                                <input type="text" class="form-control text-center" value=<?php echo number_format((float)$detalhe->lucro, 2, '.', ''); ?>>
                                                            </div>
                                                            <div class="col pl-0">
                                                                <span>Por Kg</span>
                                                                <input type="text" class="form-control text-center" value=<?php echo number_format((float)$detalhe->lucro_kg, 2, '.', ''); ?>>                                                    
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>

                                                <div class="card">
                                                    <div class="card-body py-1">
                                                        <div class="row mb-1">
                                                            <label class="card-header mb-2 w-100">Valor de Venda</label>
                                                            <div class="col">
                                                                <span>Total</span>
                                                                <input type="text" class="form-control text-center" value=<?php echo number_format((float)$detalhe->valor, 2, '.', ''); ?>>
                                                            </div>
                                                            <div class="col pl-0">
                                                                <span>Por Kg</span>
                                                                <input type="text" class="form-control text-center" value=<?php echo number_format((float)$detalhe->valor_kg, 2, '.', ''); ?>>                                                    
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>                                                
                                            </fieldset>                                                                                                                                  
                                        </div>
                                    </div>
                                </div>
                            </div>                                                    

                            <div class="accordion" id="componentes">

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingComponentes">
                                    <button 
                                        class="accordion-button collapsed" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapseComponentes" 
                                        aria-expanded="false" 
                                        aria-controls="collapseComponentes">
                                        Componentes
                                    </button>
                                    </h2>
                                    <div id="collapseComponentes" 
                                    class="accordion-collapse collapse" 
                                    aria-labelledby="headingComponentes" 
                                    data-bs-parent="#componentes">
                                    <div class="accordion-body">
                                        
                                        @foreach ($componentes as $componente)
                                            <div class="accordion" id="<?php echo preg_replace('/[[:space:]]+/', '_', $componente->nome);?>">

                                                <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading<?php echo preg_replace('/[[:space:]]+/', '_', $componente->nome);?>">
                                                    <button 
                                                        class="accordion-button collapsed" 
                                                        type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse<?php echo preg_replace('/[[:space:]]+/', '_', $componente->nome);?>" 
                                                        aria-expanded="false" 
                                                        aria-controls="collapse<?php echo preg_replace('/[[:space:]]+/', '_', $componente->nome);?>">
                                                        {{ $componente->nome }}
                                                    </button>
                                                </h2>
                                                <div id="collapse<?php echo preg_replace('/[[:space:]]+/', '_', $componente->nome);?>" 
                                                    class="accordion-collapse collapse" 
                                                    aria-labelledby="heading<?php echo preg_replace('/[[:space:]]+/', '_', $componente->nome);?>" 
                                                    data-bs-parent="#<?php echo preg_replace('/[[:space:]]+/', '_', $componente->nome);?>">
                                                    <div class="accordion-body">
                                                        <fieldset disabled>
                                                            <div class="row mb-3 px-3">
                                                                <span>Peso</span>
                                                                <input type="text" class="form-control text-center" value="{{ $componente->peso }}(Kg)">
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-body py-1">
                                                                    <div class="row mb-1">
                                                                        <label class="card-header mb-2 w-100">Custo de Produção</label>
                                                                        <div class="col">
                                                                            <span>Total</span>
                                                                            <input type="text" class="form-control text-center" value=<?php echo number_format((float)$componente->custo, 2, '.', ''); ?>>
                                                                        </div>
                                                                        <div class="col pl-0">
                                                                            <span>Por Kg</span>
                                                                            <input type="text" class="form-control text-center" value=<?php echo number_format((float)($componente->custo/$componente->peso), 2, '.', ''); ?>>                                                    
                                                                        </div>
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-body py-1">
                                                                    <div class="row mb-1">
                                                                        <label class="card-header mb-2 w-100">Lucro</label>
                                                                        <div class="col">
                                                                            <span>Total</span>
                                                                            <input 
                                                                                type="text" 
                                                                                class="form-control text-center" 
                                                                                value=<?php 
                                                                                    echo number_format((float)(($componente->custo/$detalhe->custo)*$detalhe->lucro), 2, '.', ''); 
                                                                                ?>
                                                                            >
                                                                        </div>
                                                                        <div class="col pl-0">
                                                                            <span>Por Kg</span>
                                                                            <input type="text" class="form-control text-center" value=<?php echo number_format((float)((($componente->custo/$detalhe->custo)*$detalhe->lucro)/$componente->peso), 2, '.', ''); ?>>                                                    
                                                                        </div>
                                                                    </div> 
                                                                </div>
                                                            </div>
            
                                                            <div class="card">
                                                                <div class="card-body py-1">
                                                                    <div class="row mb-1">
                                                                        <label class="card-header mb-2 w-100">Valor de Venda</label>
                                                                        <div class="col">
                                                                            <span>Total</span>
                                                                            <input type="text" class="form-control text-center" value=<?php echo number_format((float)(($componente->custo/$detalhe->custo)*$detalhe->valor), 2, '.', ''); ?>>
                                                                        </div>
                                                                        <div class="col pl-0">
                                                                            <span>Por Kg</span>
                                                                            <input type="text" class="form-control text-center" value=<?php echo number_format((float)((($componente->custo/$detalhe->custo)*$detalhe->valor)/$componente->peso), 2, '.', ''); ?>>                                                    
                                                                        </div>
                                                                    </div> 
                                                                </div>
                                                            </div>                                                
                                                        </fieldset>                                                            
                                                    </div>
                                                </div>
                                                </div>
                                            </div>                                              
                                        @endforeach

                                    </div>
                                    </div>
                                </div>

                            </div>                            
                    </div>            
                </div>
            @endforeach                            
        </form>
        <div class="card">
            <div class="card-body">
                <div id="container"></div>
            </div>
        </div>        
    </div>        


    

    @push('script_simulador')
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-3d.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>    
        <script>
            Highcharts.setOptions({
                colors: ['#3a5674', '#3c3a74', '#623a74', '#743a5f', '#743a3a', '#74713a', '#3a743d']
            });         
            Highcharts.chart('container', {
                chart: {
                    type: 'pie',
                },
                title: {
                    text: 'Custo de Produção'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,                        
                        dataLabels: {
                            distance: -70,
                            color: 'white',
                            format: 'R$ {point.y}'
                        }                     
                    }
                },                
                exporting: {
                    enabled: false
                },   
                credits: {
                    enabled: false
                },                             
                series: [{
                    type: 'pie',
                    name: 'Representatividade',
                    data: [
                        @foreach ($componentes as $componente)
                            {
                                name: '{{ $componente->nome }}',
                                y: <?php echo number_format((float)$componente->custo, 2, '.', ''); ?>,
                                sliced: false,
                                selected: false
                            },
                        @endforeach
                    ]
                }]
            });            
        </script>
    @endpush

@endsection