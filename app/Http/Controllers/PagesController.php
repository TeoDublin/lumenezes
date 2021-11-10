<?php

namespace App\Http\Controllers;
use App\Models\Produto;
use App\Models\Receita;
use App\Models\Bolo;
use App\Models\Bolo_formula;
use App\Models\Formula;
use App\Models\Catalogo;
use App\Models\Catalogo_bolo;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Spatie\Backup\Helpers\Format;

class PagesController extends Controller
{
    //Vendas
    public function vendas(Request $request)
    {   
        if($request->ano){
            $ano_selecionado = $request->ano;
        }else{
            $ano_selecionado = date_format(Carbon::now(),'Y');
        }

        if($request->mes){
            $mes_selecionado = $request->mes;
        }else{
            $mes_selecionado = date_format(Carbon::now(),'m');
        }

        $data_selecionada =  $ano_selecionado . '-' . $mes_selecionado;

        $data_inicio = Carbon::createFromFormat('Y-m', $data_selecionada)->firstOfMonth()->format('Y-m-d');
        $data_fim = Carbon::createFromFormat('Y-m', $data_selecionada)->endOfMonth()->format('Y-m-d');

        $catalogo_bolos = DB::table('catalogo_bolos')->select('id', 'nome')->get();
        $catalogos = DB::table('catalogos')->select('id', 'nome')->get();
        $simulados = $catalogo_bolos->merge($catalogos);

        $vendas_por_mes = DB::table(function ($query) {
            $query->selectRaw('
                DATE_FORMAT(data, "%Y") as ano, 
                DATE_FORMAT(data, "%m") as mes_a,
                CASE DATE_FORMAT(data, "%m") 
                        WHEN 1 THEN "jan" 
                        WHEN 2 THEN "fev" 
                        WHEN 3 THEN "mar" 
                        WHEN 4 THEN "abr" 
                        WHEN 5 THEN "mai" 
                        WHEN 6 THEN "jun" 
                        WHEN 7 THEN "jul" 
                        WHEN 8 THEN "ago" 
                        WHEN 9 THEN "set" 
                        WHEN 10 THEN "out" 
                        WHEN 11 THEN "nov" 
                        WHEN 12 THEN "des" 
                    END as mes,
                    SUM(custo) as custo,
                    SUM(lucro) as lucro,
                    SUM(valor) as valor
                ')
                ->from('vendas')
                ->groupBy('data')
                ->orderBy('mes_a');
        }, 'query_vendas')->select('ano', 'mes_a', 'mes', DB::raw('SUM(custo) as custo, SUM(lucro) as lucro, SUM(valor) as valor'))
        ->where('ano', '=', $ano_selecionado)
        ->groupBy('ano', 'mes', 'mes_a')->get();

        $vendas_por_ano = DB::table('vendas')->select(DB::raw('DATE_FORMAT(data, "%Y") as ano'))->groupBy('ano')->orderByDesc('ano')->get();
        
        $vendas = DB::table('vendas')->select('id', DB::raw('DATE_FORMAT(data, "%d/%m") as data'), 'quantidade', 'nome', 'custo', 'lucro', 'valor')
        ->where('data','>=',$data_inicio)->where('data','<=',$data_fim)->orderBy('data')->get();
        
        $total_vendas = DB::table('vendas')->select(DB::raw('SUM(custo) as custo, SUM(lucro) as lucro, SUM(valor) as valor'))
        ->where('data','>=',$data_inicio)->where('data','<=',$data_fim)->orderBy('data')->get()->first();

        return view('vendas', [
            'simulados' => $simulados, 
            'vendas' => $vendas, 
            'total_vendas' => $total_vendas,
            'vendas_por_mes' =>$vendas_por_mes, 
            'vendas_por_ano' =>$vendas_por_ano,
            'ano_selecionado' =>$ano_selecionado,
            'mes_selecionado' =>$mes_selecionado
        ]);
    }

    public function insert_vendas(Request $request)
    {
        $request->validate([
            'receita_id' => 'required',
            'quantidade' => 'required',
            'data' => 'required' 
        ]);
        
        $catalogo_bolos = DB::table('catalogo_bolos')->select('id', 'nome', 'custo', 'lucro', 'valor')->where('id', "=", $request->receita_id)->get();
        $catalogos = DB::table('catalogos')->select('id', 'nome', 'custo', 'lucro', 'valor')->where('id', "=", $request->receita_id)->get();
        $simulados = $catalogo_bolos->merge($catalogos)->first();
    
        $venda = new Venda();
        $venda->data = $request->data;
        $venda->quantidade = $request->quantidade;
        $venda->nome = $simulados->nome;
        $venda->custo = $simulados->custo * $request->quantidade;
        $venda->lucro = $simulados->lucro * $request->quantidade;
        $venda->valor = $simulados->valor * $request->quantidade;

        $venda->save();

        return redirect()->route('vendas_route');
    }

    public function delete_vendas(Request $request)
    {
        Venda::where('id', $request->id)->delete();
        return redirect()->route('vendas_route');
    }        

    //Simulador
    public function simulador()
    {
        $receitas = DB::table('receitas')->select('*')->where('tipo', '=', 'unitarios')->get();
        $catalogos = Catalogo::all();
        return view('simulador', ['catalogos'  => $catalogos, 'receitas'  => $receitas]);
    }

    public function simulador_add(Request $request)
    {
        $request->validate([
            'receita' => 'required'
        ]);

        $id = $request->receita;
        
        $receitas = DB::table('receitas')->join('formulas', 'receitas.id', '=', 'formulas.receita_id')
        ->select('receitas.id','receitas.nome', 'receitas.peso' ,'receitas.unidade_peso', DB::raw('sum(custo) as custo') )
        ->where('receitas.id' , '=', $id)
        ->groupBy('receitas.id')
        ->get();

        return view('simulador_add', ['receitas'  => $receitas]);
    }

    public function delete_simulador(Request $request)
    {
        DB::table('catalogos')->where('id', $request->id)->delete();
        return redirect()->route('simulador_route');
    }

    public function insert_simulador(Request $request){
        
        $request->validate([
            'nome' => 'required',
            'peso' => 'required',
            'unidade' => 'required',
            'quantidade' => 'required',
            'margem' => 'required',
            'lucro' => 'required',
            'valor' => 'required'
        ]);

        $catalogo = new Catalogo();
        $catalogo->nome = $request->nome;
        $catalogo->receita_id =$request->id;
        $catalogo->peso = $request->peso;
        $catalogo->unidade_peso = $request->unidade;
        $catalogo->quantidade = $request->quantidade;
        $catalogo->margem = $request->margem;
        $catalogo->custo = $request->custo_interno;
        $catalogo->lucro = $request->lucro;
        $catalogo->valor = $request->valor;
        switch ($request->unidade) {
            case 'Kg':
                $peso = $request->peso;
                break;
            case 'g':
                $peso = ($request->peso / 1000);
                break;            
            default:
                # code...
                break;
        }
        $catalogo->custo_kg = $request->custo_interno / $peso;
        $catalogo->lucro_kg = $request->lucro / $peso;
        $catalogo->valor_kg = $request->valor / $peso;
        $catalogo->custo_unidade =  $request->custo_interno / $request->quantidade;
        $catalogo->lucro_unidade =  $request->lucro / $request->quantidade;
        $catalogo->valor_unidade =  $request->valor / $request->quantidade;
        
        $catalogo->save();

        return redirect()->route('simulador_route');
    }

    public function detalhe_simulador(Request $request)
    {
        $detalhes = DB::table('catalogos')->select('*')->where('id', '=',  $request->id)->get();

        return view('simulador_detalhes', ['detalhes' => $detalhes]);
    }

    public function update_simulador(Request $request)
    {
        $detalhe = Catalogo::find($request->id);
        $detalhe->nome = $request->nome;
        $detalhe->save();
        return redirect()->route('simulador_route');
    }

    //Simulador_bolos

    public function simulador_bolos()
    {
        $receitas = Bolo::all();
        $catalogos = Catalogo_bolo::all();
        return view('simulador_bolos', ['catalogos'  => $catalogos, 'receitas'  => $receitas]);
    }

    public function simulador_bolos_add(Request $request)
    {
        $request->validate([
            'receita' => 'required'
        ]);

        $id = $request->receita;
        
        $bolo = DB::table('bolos')
        ->join('bolo_formulas', 'bolos.id', '=', 'bolo_formulas.bolo_id')
        ->join('receitas', 'receitas.id', '=', 'bolo_formulas.receita_id')
        ->select('bolos.id', 'bolos.nome', DB::raw('sum(bolo_formulas.receita_custo) as bolo_custo'), DB::raw('sum(receitas.peso_mili) as peso'))
        ->where('bolos.id', '=', $id)
        ->groupBy('bolos.id')
        ->get();

        
        if($bolo[0]->peso < 1000){
            $bolo[0]->unidade_peso = "g";
        }else{
            $bolo[0]->peso = $bolo[0]->peso/1000;
            $bolo[0]->unidade_peso = "Kg";
        }

        $massas = DB::table('bolos')
        ->join('bolo_formulas', 'bolos.id', '=', 'bolo_formulas.bolo_id')
        ->join('receitas', 'receitas.id', '=', 'bolo_formulas.receita_id')
        ->select('receitas.id','receitas.nome', 'receitas.peso' ,'receitas.unidade_peso', 'receitas.peso_mili', 'bolo_formulas.receita_custo' )        
        ->where('bolos.id' , '=', $id)
        ->where('bolo_formulas.tipo_formula', '=', 'massa')
        ->groupBy('bolo_formulas.id')
        ->get();

        $recheios = DB::table('bolos')
        ->join('bolo_formulas', 'bolos.id', '=', 'bolo_formulas.bolo_id')
        ->join('receitas', 'receitas.id', '=', 'bolo_formulas.receita_id')
        ->select('receitas.id','receitas.nome', 'receitas.peso' ,'receitas.unidade_peso', 'receitas.peso_mili', 'bolo_formulas.receita_custo' )      
        ->where('bolos.id' , '=', $id)
        ->where('bolo_formulas.tipo_formula', '=', 'recheio')
        ->groupBy('bolo_formulas.id')
        ->get();

        $coberturas = DB::table('bolos')
        ->join('bolo_formulas', 'bolos.id', '=', 'bolo_formulas.bolo_id')
        ->join('receitas', 'receitas.id', '=', 'bolo_formulas.receita_id')
        ->select('receitas.id','receitas.nome', 'receitas.peso' ,'receitas.unidade_peso', 'receitas.peso_mili', 'bolo_formulas.receita_custo' )    
        ->where('bolos.id' , '=', $id)
        ->where('bolo_formulas.tipo_formula', '=', 'cobertura')
        ->groupBy('bolo_formulas.id')
        ->get();  

       
        return view('simulador_bolos_add', ['massas'  => $massas,'recheios'  => $recheios,'coberturas'  => $coberturas, 'bolos'  => $bolo]);
    }

    public function delete_simulador_bolos(Request $request)
    {
        DB::table('catalogo_bolos')->where('id', $request->id)->delete();
        return redirect()->route('simulador_bolos_route');
    }

    public function insert_simulador_bolos(Request $request){
    
        $request->validate([
            'nome' => 'required',
            'margem' => 'required',
            'lucro' => 'required',
            'valor' => 'required'
        ]);

        $catalogo = new Catalogo_bolo();
        $catalogo->nome = $request->nome;
        $catalogo->bolo_id = $request->bolo_id;
        $catalogo->peso = $request->peso;
        $catalogo->unidade_peso = $request->unidade_peso;
        $catalogo->margem = $request->margem;
        $catalogo->custo = $request->custo;
        $catalogo->lucro = $request->lucro;
        $catalogo->valor = $request->valor;
        $catalogo->custo_kg = $request->custo / $request->peso;
        $catalogo->lucro_kg = $request->lucro / $request->peso;
        $catalogo->valor_kg = $request->valor / $request->peso;
        $catalogo->componentes = $request->componentes;
        $catalogo->save();

        return redirect()->route('simulador_bolos_route');
    }

    public function detalhe_simulador_bolos(Request $request)
    {
        $detalhes = DB::table('catalogo_bolos')
        ->select('id', 'nome', 'bolo_id', 'peso', 'unidade_peso', 'margem', 'custo', 'lucro', 'valor', 'custo_kg', 'lucro_kg', 'valor_kg')
        ->where('id', '=',  $request->id)->get();

        $array_componentes = DB::table('catalogo_bolos')
        ->select('componentes')
        ->where('id', '=',  $request->id)->get();

        $componentes = json_decode($array_componentes[0]->componentes);
      
        return view('simulador_bolos_detalhes', ['detalhes' => $detalhes, 'componentes' =>$componentes]);
    }

    public function update_simulador_bolos(Request $request)
    {
        $detalhe = Catalogo_bolo::find($request->id);
        $detalhe->nome = $request->nome;
        $detalhe->save();
        return redirect()->route('simulador_bolos_route');
    }    

    //Receitas

    public function receitas()
    {   
        $receitas = DB::table('receitas')->select('*')->where('tipo', '=', 'unitarios')->get();
        $produtos = DB::table('produtos')->select('*')->get();
        $formulas = DB::table('formulas')
            ->join('receitas','formulas.receita_id', '=', 'receitas.id')
            ->join('produtos','formulas.produto_id', '=', 'produtos.id')
            ->select('formulas.id' , 'formulas.receita_id', 'receitas.nome', 'receitas.peso', 'receitas.unidade_peso', 'formulas.produto_id', 
                    'produtos.produto', 'formulas.quantidade', 'formulas.unidade', 'formulas.custo', 
                    'produtos.unidade_mili', 'produtos.quantidade_mili', 'produtos.custo_mili')
            ->orderBy('formulas.id')
            ->get();
        return view('receitas', ['formulas' => $formulas, 'receitas' => $receitas, 'produtos' => $produtos]);

    }    

    public function receitas_bolos()
    {   
        $bolos = Bolo::all();

        $add_massas = DB::table('receitas')->select('*')->where('tipo', '=', 'massas')->get();
        $add_recheios = DB::table('receitas')->select('*')->where('tipo', '=', 'recheios')->get();
        $add_coberturas = DB::table('receitas')->select('*')->where('tipo', '=', 'coberturas')->orWhere('tipo', '=', 'unitarios')->get();

        $massas = DB::table('bolo_formulas')
        ->join('receitas', 'bolo_formulas.receita_id', '=', 'receitas.id')
        ->select('bolo_formulas.id','bolo_formulas.bolo_id','receitas.nome')
        ->where('bolo_formulas.tipo_formula', '=', 'massa')
        ->orderBy('bolo_formulas.id')
        ->get();

        $recheios = DB::table('bolo_formulas')
        ->join('receitas', 'bolo_formulas.receita_id', '=', 'receitas.id')
        ->select('bolo_formulas.id','bolo_formulas.bolo_id','receitas.nome')
        ->where('bolo_formulas.tipo_formula', '=', 'recheio')
        ->orderBy('bolo_formulas.id')
        ->get();
        
        $coberturas = DB::table('bolo_formulas')
        ->join('receitas', 'bolo_formulas.receita_id', '=', 'receitas.id')
        ->select('bolo_formulas.id','bolo_formulas.bolo_id','receitas.nome')
        ->where('bolo_formulas.tipo_formula', '=', 'cobertura')
        ->orderBy('bolo_formulas.id')
        ->get();        

        return view('receitas_bolos', [
            'bolos' => $bolos, 'massas' => $massas, 'recheios' => $recheios, 'coberturas' => $coberturas,
            'add_massas' => $add_massas, 'add_recheios' => $add_recheios, 'add_coberturas' => $add_coberturas
        ]);

    }

    public function receitas_massas()
    {   
        $receitas = DB::table('receitas')->select('*')->where('tipo', '=', 'massas')->get();
        $produtos = DB::table('produtos')->select('*')->get();
        $formulas = DB::table('formulas')
            ->join('receitas','formulas.receita_id', '=', 'receitas.id')
            ->join('produtos','formulas.produto_id', '=', 'produtos.id')
            ->select('formulas.id' , 'formulas.receita_id', 'receitas.nome', 'receitas.peso', 'receitas.unidade_peso', 'formulas.produto_id', 
                    'produtos.produto', 'formulas.quantidade', 'formulas.unidade', 'formulas.custo', 
                    'produtos.unidade_mili', 'produtos.quantidade_mili', 'produtos.custo_mili')
            ->orderBy('formulas.id')
            ->get();
        return view('receitas_massas', ['formulas' => $formulas, 'receitas' => $receitas, 'produtos' => $produtos]);

    }
    
    public function receitas_recheios()
    {   
        $receitas = DB::table('receitas')->select('*')->where('tipo', '=', 'recheios')->get();
        $produtos = DB::table('produtos')->select('*')->get();
        $formulas = DB::table('formulas')
            ->join('receitas','formulas.receita_id', '=', 'receitas.id')
            ->join('produtos','formulas.produto_id', '=', 'produtos.id')
            ->select('formulas.id' , 'formulas.receita_id', 'receitas.nome', 'receitas.peso', 'receitas.unidade_peso', 'formulas.produto_id', 
                    'produtos.produto', 'formulas.quantidade', 'formulas.unidade', 'formulas.custo', 
                    'produtos.unidade_mili', 'produtos.quantidade_mili', 'produtos.custo_mili')
            ->orderBy('formulas.id')
            ->get();
        return view('receitas_recheios', ['formulas' => $formulas, 'receitas' => $receitas, 'produtos' => $produtos]);

    }    

    public function receitas_coberturas()
    {   
        $receitas = DB::table('receitas')->select('*')->where('tipo', '=', 'coberturas')->get();
        $produtos = DB::table('produtos')->select('*')->get();
        $formulas = DB::table('formulas')
            ->join('receitas','formulas.receita_id', '=', 'receitas.id')
            ->join('produtos','formulas.produto_id', '=', 'produtos.id')
            ->select('formulas.id' , 'formulas.receita_id', 'receitas.nome', 'receitas.peso', 'receitas.unidade_peso', 'formulas.produto_id', 
                    'produtos.produto', 'formulas.quantidade', 'formulas.unidade', 'formulas.custo', 
                    'produtos.unidade_mili', 'produtos.quantidade_mili', 'produtos.custo_mili')
            ->orderBy('formulas.id')
            ->get();
        return view('receitas_coberturas', ['formulas' => $formulas, 'receitas' => $receitas, 'produtos' => $produtos]);

    }    

    public function insert_receitas(Request $request)
    {
        $request->validate([
            'nome' => 'required|unique:receitas',
            'peso' => 'required',
            'unidade' => 'required' 
        ]);
   
        $receita = new Receita();
        $receita->nome = $request->nome;
        $receita->peso = $request->peso;
        $receita->unidade_peso = $request->unidade;

        if ($request->unidade == 'Kg') {
            $peso_mili = $request->peso * 1000;
        }else{
            $peso_mili = $request->peso;
        }
        $receita->peso_mili = $peso_mili;
        $receita->tipo = $request->tipo;
        $receita->save();

        $route = $request->route;
        return redirect()->route($route, ['item' => $request->nome]);
    }

    public function delete_receitas(Request $request)
    {
        $route = $request->route_delete;
        Receita::where('id', $request->receita_id)->delete();
        return redirect()->route($route, ['item' => $request->receita_item]);
    }    

    public function insert_receitas_bolos(Request $request)
    {
        $request->validate([
            'nome' => 'required|unique:bolos',
        ]);
   
        $bolo = new Bolo();
        $bolo->nome = $request->nome;
        $bolo->save();

        return redirect()->route('receitas_bolos_route', ['bolo' => $request->nome, 'item' => 'massa']);
    }

    public function delete_receitas_bolos(Request $request)
    {
        Bolo::where('id', $request->id)->delete();
        return redirect()->route('receitas_bolos_route');
    }   

    public function insert_bolo_formulas(Request $request)
    {
        $bolo_formula = new Bolo_formula;
        $bolo_formula->tipo_formula = $request->item;
        $bolo_formula->bolo_id = $request->bolo_id;
        $bolo_formula->receita_id = $request->receita_id;
        
        $custo = DB::table('formulas')->select(DB::raw('sum(custo) as receita_custo'))
        ->where('receita_id', '=', $request->receita_id)->groupBy('receita_id')->first();
        $bolo_formula->receita_custo = $custo->receita_custo;
        $bolo_formula->save();

        return redirect()->route('receitas_bolos_route', ['bolo' => $request->bolo, 'item' => $request->item]);
    }

    public function delete_bolo_formulas(Request $request)
    {

        Bolo_formula::where('id', $request->id)->delete();    
          
        return redirect()->route('receitas_bolos_route', ['bolo' => $request->bolo, 'item' => $request->item]);
    }

    public function insert_formulas(Request $request)
    {

        $route = $request->route_insert;
        $formula = new Formula;
    
        $formula->receita_id = $request->receita_id;
        $formula->produto_id = $request->produto_id;

        $quantidade = $request->produto_quantidade;

        switch($quantidade){
            case('1/2'):
                $quantidade = 0.50;
            break;

            case('1/3'):
                $quantidade = 0.34;
                break;      

            case('2/3'):
                $quantidade = 0.67;
                break;            

            case('1/4'):
                $quantidade = 0.25;
                break;                         

            case('2/4'):
                $quantidade = 0.50;
                break;     
                
            case('3/4'):
                $quantidade = 0.75;
                break;    

            case('1/8'):
                $quantidade = 0.12;
                break;                    
                
            default:
            $quantidade = $quantidade;
        }

        $formula->quantidade = $quantidade;

        $unidade = $request->produto_unidade;
        $formula->unidade = $unidade;

        $produto_unidade_mili = $request->produto_unidade_mili;
        $formula->unidade_mili = $produto_unidade_mili;

        //1 xícara          240 ml  150 g
        //1 colher (sopa) 	15 ml   10 g
        //1 colher (chá) 	5 ml    4 g
        //pitada            0.12    0.12
        if($produto_unidade_mili == 'g'){
            switch($unidade){
                case('Kg'):
                    $quantidade_mili = $quantidade * 1000; 
                    break;                 
                case('g'):
                    $quantidade_mili = $quantidade; 
                    break; 
                case('xícara'):
                    $quantidade_mili = $quantidade * 150; 
                    break; 
                case('c. de sopa'):
                    $quantidade_mili = $quantidade * 10; 
                    break; 
                case('c. de chá'):
                    $quantidade_mili = $quantidade * 4; 
                    break; 
                case('pitada'):
                    $quantidade_mili = $quantidade * 0.125; 
                    break; 

                default:
                    $quantidade_mili = 0;
            }

        }else if($produto_unidade_mili=='ml'){

            switch($unidade){
                case('L'):
                    $quantidade_mili = $quantidade * 1000; 
                    break;                      
                case('ml'):
                    $quantidade_mili = $quantidade; 
                    break; 
                case('xícara'):
                    $quantidade_mili = $quantidade * 240; 
                    break; 
                case('c. de sopa'):
                    $quantidade_mili = $quantidade * 15; 
                    break; 
                case('c. de chá'):
                    $quantidade_mili = $quantidade * 5; 
                    break; 
                case('pitada'):
                    $quantidade_mili = $quantidade * 0.125; 
                    break; 

                default:
                $quantidade_mili = 0;
            }

        }
        
        $formula->quantidade_mili = $quantidade_mili;

        $produto_custo_mili = $request->produto_custo_mili;
        $custo = $quantidade_mili * $produto_custo_mili;
        $formula->custo = number_format((float) $custo, 2, '.', '');

        $formula->save();

        $receitas = DB::table('formulas')
        ->rightJoin('catalogos', 'formulas.receita_id', '=', 'catalogos.receita_id')
        ->select('catalogos.receita_id')->where('catalogos.receita_id', $request->receita_id)->groupBy('catalogos.receita_id')->get();

        $this->produto_update_catalogos($receitas);        
        
        return redirect()->route($route, ['item' => $request->item]);
        
    }

    public function delete_formulas(Request $request)
    {
        $route = $request->route_delete_formulas;

        Formula::where('id', $request->id)->delete();

        $receitas = DB::table('formulas')
        ->rightJoin('catalogos', 'formulas.receita_id', '=', 'catalogos.receita_id')
        ->select('catalogos.receita_id')->where('catalogos.receita_id', $request->receita_id)->groupBy('catalogos.receita_id')->get();

        
        $this->produto_update_catalogos($receitas);        
          
        return redirect()->route($route, ['item' => $request->item]);
    }

    //Produtos
    public function produtos()
    {
        $produtos = Produto::all();
        return view('produtos', ['produtos' => $produtos]);

    }    

    public function insert_produtos(Request $request)
    {
        $request->validate([
            'produto' => 'required',
            'quantidade' => 'required',
            'unidade' => 'required',
            'custo' => 'required',
        ]);

        $produto = new Produto;
        $produto->produto = $request->input('produto');

        $quantidade = $request->input('quantidade');
        $produto->quantidade = $quantidade;
        
        $unidade =$request->input('unidade'); 
        $produto->unidade = $unidade;

        $custo = $request->input('custo');
        $produto->custo = $custo;  

        if($unidade=='Kg'){
            $unidade_mili = 'g';
            $quantidade_mili = $quantidade * 1000;
            $custo_mili = $custo / $quantidade_mili;
        }else if($unidade=='L'){
            $unidade_mili = 'ml';
            $quantidade_mili = $quantidade * 1000;
            $custo_mili = $custo / $quantidade_mili;       
        }else{
            $unidade_mili = $unidade;
            $quantidade_mili = $quantidade;
            $custo_mili = $custo / $quantidade_mili;    
        }
   
        $produto->unidade_mili = $unidade_mili;
        $produto->quantidade_mili = $quantidade_mili;
        $produto->custo_mili = $custo_mili;

        $produto->save();
        return redirect('/produtos');
    }

    public function delete_produtos(Request $request)
    {
        $produto = Produto::find($request->input('id'));        
        $produto->delete();
        return redirect('/produtos');
    }

    public function update_produtos(Request $request)
    {
        $id = $request->input('id_up');

        $produto = Produto::find($id);
        $produto->produto = $request->input('produto_up');

        $quantidade = $request->input('quantidade_up');
        $produto->quantidade = $quantidade;
        
        $unidade =$request->input('unidade_up'); 
        $produto->unidade = $unidade;

        $custo = $request->input('custo_up');
        $produto->custo = $custo;  

        if($unidade=='Kg'){
            $unidade_mili = 'g';
            $quantidade_mili = $quantidade * 1000;
            $custo_mili = $custo / $quantidade_mili;
        }else if($unidade=='L'){
            $unidade_mili = 'ml';
            $quantidade_mili = $quantidade * 1000;
            $custo_mili = $custo / $quantidade_mili;       
        }else{
            $unidade_mili = $unidade;
            $quantidade_mili = $quantidade;
            $custo_mili = $custo / $quantidade_mili;   
        }
  
        $produto->unidade_mili = $unidade_mili;
        $produto->quantidade_mili = $quantidade_mili;
        $produto->custo_mili = $custo_mili;

        $produto->save();

        $formulas = DB::table('formulas')->where('produto_id', $id)->get();

        $this->produto_update_formulas($formulas, $unidade_mili, $custo_mili);

        $receitas = DB::table('formulas')
        ->rightJoin('catalogos', 'formulas.receita_id', '=', 'catalogos.receita_id')
        ->select('catalogos.receita_id')->where('formulas.produto_id', $id)->groupBy('catalogos.receita_id')->get();

        $this->produto_update_catalogos($receitas);

        return redirect('/produtos');
    }

    public function produto_update_formulas ($formulas, $produto_unidade_mili, $produto_custo_mili) {
  
        foreach ($formulas as $formula) {
            
            $quantidade = $formula->quantidade; //1
            $unidade = $formula->unidade; //Kg
            $unidade_mili = $produto_unidade_mili; //g
            
            if( ($produto_unidade_mili == 'g' && $unidade == 'ml') ||
                ($produto_unidade_mili == 'ml' && $unidade == 'g')){    
                    $unidade = $produto_unidade_mili;
            }else if($produto_unidade_mili == 'g' && $unidade == 'L') {
                    $unidade = 'Kg';
            }else if($produto_unidade_mili == 'ml' && $unidade == 'Kg') {
                $unidade = 'L';
            }

            

            if($produto_unidade_mili == 'g'){
                switch($unidade){
                    case('Kg'):
                        $quantidade_mili = $quantidade * 1000; 
                        break;                 
                    case('g'):
                        $quantidade_mili = $quantidade; 
                        break; 
                    case('xícara'):
                        $quantidade_mili = $quantidade * 150; 
                        break; 
                    case('c. de sopa'):
                        $quantidade_mili = $quantidade * 10; 
                        break; 
                    case('c. de chá'):
                        $quantidade_mili = $quantidade * 4; 
                        break; 
                    case('pitada'):
                        $quantidade_mili = $quantidade * 0.125; 
                        break; 
    
                    default:
                        $quantidade_mili = 0;
                }
    
            }else if($produto_unidade_mili=='ml'){
    
                switch($unidade){
                    case('L'):
                        $quantidade_mili = $quantidade * 1000; 
                        break;                      
                    case('ml'):
                        $quantidade_mili = $quantidade; 
                        break; 
                    case('xícara'):
                        $quantidade_mili = $quantidade * 240; 
                        break; 
                    case('c. de sopa'):
                        $quantidade_mili = $quantidade * 15; 
                        break; 
                    case('c. de chá'):
                        $quantidade_mili = $quantidade * 5; 
                        break; 
                    case('pitada'):
                        $quantidade_mili = $quantidade * 0.125; 
                        break; 
    
                    default:
                    $quantidade_mili = 0;
                }
    
            }
    
            $custo = $quantidade_mili * $produto_custo_mili;         

            DB::table('formulas')
                ->where('id', $formula->id)
                ->update([
                            'unidade' => $unidade,
                            'custo' => $custo,
                            'quantidade_mili' => $quantidade_mili,
                            'unidade_mili' => $unidade_mili
                        ]);
        }   
    }  

    public function produto_update_catalogos($receitas) {
        
        foreach ($receitas as $receita){
            
            $custo = DB::table('formulas')->select(DB::raw('sum(custo) as custo'))->where('receita_id', $receita->receita_id)->get();
            $margem = DB::table('catalogos')->select('margem')->where('receita_id', $receita->receita_id)->get();
            
            if($custo[0]->custo==null){
                $custo = 0;
            }else{
                $custo = round($custo[0]->custo,0);
            }            
            
            $margem = $margem[0]->margem;
            $margem = ($margem / 100) + 1;
            $valor = round($custo * $margem,0);
            $lucro = $valor - $custo;

            DB::table('catalogos')
            ->where('receita_id', $receita->receita_id)
            ->update([
                        'custo' => $custo,
                        'lucro' => $lucro,
                        'valor' => $valor
                    ]);
        }
        
    }
    
}
