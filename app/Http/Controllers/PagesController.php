<?php

namespace App\Http\Controllers;
use App\Models\Produto;
use App\Models\Receita;
use App\Models\Formula;
use App\Models\Catalogo;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function simulador()
    {
        $receitas = DB::table('formulas')
        ->join('receitas', 'formulas.receita_id', '=', 'receitas.id')
        ->select('receitas.id','receitas.nome', DB::raw('sum(formulas.custo) as custo'))
        ->groupBy('receitas.id','receitas.nome')
        ->get();

        $catalogos = Catalogo::all();

        return view('simulador', ['receitas'  => $receitas, 'catalogos'  => $catalogos]);
    }

    public function insert_simulador(Request $request){
        
        $this->delete_catalogo($request->receita_id);

        $catalogo = new Catalogo();
        $catalogo->receita_id = $request->receita_id;
        $catalogo->custo = $request->custo;
        $catalogo->margem = $request->margem;
        $catalogo->lucro = $request->lucro;
        $catalogo->valor = $request->valor;
        $catalogo->save();

        return redirect('/');
    }

    public function delete_catalogo($id){
        DB::table('catalogos')->where('receita_id', $id)->delete();
    }

    public function receitas()
    {   
        $receitas = Receita::all();
        $produtos = DB::table('produtos')->select('*')->get();
        $formulas = DB::table('formulas')
            ->join('receitas','formulas.receita_id', '=', 'receitas.id')
            ->join('produtos','formulas.produto_id', '=', 'produtos.id')
            ->select('formulas.id' , 'formulas.receita_id', 'receitas.nome', 'formulas.produto_id', 
                    'produtos.produto', 'formulas.quantidade', 'formulas.unidade', 'formulas.custo', 
                    'produtos.unidade_mili', 'produtos.quantidade_mili', 'produtos.custo_mili')
            ->orderBy('id')
            ->get();
        return view('receitas', ['formulas' => $formulas, 'receitas' => $receitas, 'produtos' => $produtos]);

    }

    public function insert_receitas(Request $request)
    {
        $request->validate([
            'nome' => 'required|unique:receitas'
        ]);
   
        $receita = new Receita();
        $receita->nome = $request->nome;
        $receita->save();

        return redirect()->route('receitas_route', ['item' => $request->nome]);
    }

    public function delete_receitas(Request $request)
    {
        Receita::where('id', $request->receita_id)->delete();
        return redirect()->route('receitas_route', ['item' => $request->receita_item]);
    }    

    public function insert_formulas(Request $request)
    {

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
        $formula->custo = $custo;

        $formula->save();

        $receitas = DB::table('formulas')
        ->rightJoin('catalogos', 'formulas.receita_id', '=', 'catalogos.receita_id')
        ->select('catalogos.receita_id')->where('catalogos.receita_id', $request->receita_id)->groupBy('catalogos.receita_id')->get();

        $this->produto_update_catalogos($receitas);        
        
        return redirect()->route('receitas_route', ['item' => $request->item]);
        
    }

    public function delete_formulas(Request $request)
    {
        
        Formula::where('id', $request->id)->delete();

        $receitas = DB::table('formulas')
        ->rightJoin('catalogos', 'formulas.receita_id', '=', 'catalogos.receita_id')
        ->select('catalogos.receita_id')->where('catalogos.receita_id', $request->receita_id)->groupBy('receita_id')->get();

        
        $this->produto_update_catalogos($receitas);        
          
        return redirect()->route('receitas_route', ['item' => $request->item]);
    }

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
        ->select('catalogos.receita_id')->where('produto_id', $id)->groupBy('receita_id')->get();

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
