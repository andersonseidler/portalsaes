<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Mail\SendEmail;
use App\Models\Adiantamento;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;
use Carbon\Carbon;
use setasign\Fpdi\PdfParser\PdfParser;

class AdController extends Controller
{   

    protected $model;

    public function __construct(Adiantamento $pag)
    {
        $this->model = $pag;
        Carbon::setLocale('pt_BR');
    }

    public function index(Request $request){
        
        $search = $request->search;
        //dd($request->colaborador);
        $pags = $this->model->getPagamentos(colaborador: $request->colaborador, mes: $request->mes, status: $request->status ?? '');
        //dd($pags);
        $users = User::all();
        //dd($pags);
        $title = 'Excluir!';
        $text = "Deseja excluir esse adiantamento?";
        confirmDelete($title, $text);
        

        /* $text = (new Pdf('/usr/bin/pdftotext'))
            ->setPdf('pdf_dividido/Google.pdf')
            ->text(); */

        return view('adiantamento.index', compact('pags','users'));
    }

    public function create(){
        $users = User::all();
        return view('adiantamento.create', compact('users','users'));
    }
    
    public function store(Request $request){
        $data = $request->all();
        dd($data);
        $caminhoArquivoPDF = $request->file('arquivo')->path();
        //dd($caminhoArquivoPDF);
        $diretorioDividido = $this->dividirPDF($caminhoArquivoPDF);
        //dd($diretorioDividido);
        //$data['arquivo'] = $diretorioDividido;
        
        //dd($ler);
        $extension = $request->arquivo->getClientOriginalExtension();

        if($extension == ""){
            alert()->error('Carregue um arquivo em PDF!');
            return redirect()->route('adiantamento.index');
        }
        if(!$request->colaborador){
            alert()->error('Selecione o colaborador!');
            return redirect()->route('adiantamento.index');
        }elseif(!$request->email){
            alert()->error('Preencha o campo e-mail');
            return redirect()->route('adiantamento.index');  
        }elseif(!$request->arquivo){
            alert()->error('Carregue um arquivo em PDF!');
            return redirect()->route('adiantamento.index');    
        }elseif($extension != "pdf"){
            alert()->error('Carregue um arquivo PDF!');
            return redirect()->route('adiantamento.index');    
        }else{
            
            $data['arquivo'] = $request->arquivo->storeAs('contracheques', 'adiantamento-' . $request->colaborador . ".{$extension}");
            
        }
        
        //Mail::to( config('mail.from.address'))->send(new SendEmail($data));

        if($this->model->create($data)){
            alert()->success('Contracheque cadastrado com sucesso!');
            return redirect()->route('adiantamento.index');
        }
    }

    public function destroy($id){
        if(!$pag = $this->model->find($id)){
            alert()->error('Erro ao excluír o adiantamento!');
        }
        
        if($pag->delete()){
            alert()->success('Adiantamento excluído com sucesso!');
        }

        return redirect()->route('adiantamento.index');
    }

    public function deleteAll(Request $request){
        $ids = $request->ids;
        Adiantamento::whereIn('id', $ids)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Adiantamentos excluídos com sucesso!'
      ]);
        
    }

    public function dividirPDF($caminhoArquivoPDF)
{
    // Verifica se o caminho fornecido é um arquivo válido
    if (!is_file($caminhoArquivoPDF)) {
        // O caminho não é um arquivo válido
        // Tratar o erro ou exibir uma mensagem adequada
        return null;
    }

    // Define o diretório de destino para os arquivos divididos
    $diretorioDestino = public_path('pdf_dividido');

    // Verifica se o diretório de destino existe, senão cria
    if (!file_exists($diretorioDestino)) {
        mkdir($diretorioDestino, 0777, true);
    }

    // Carrega o arquivo PDF
    $pdf = new Fpdi();
    $totalPaginas = $pdf->setSourceFile($caminhoArquivoPDF);

    // Divide as páginas em arquivos individuais
    for ($pagina = 1; $pagina <= $totalPaginas; $pagina++) {
        $pdf->AddPage();
        $importedPage = $pdf->importPage($pagina);
        $pdf->useTemplate($importedPage);

        $nomeArquivo = 'pagina_' . $pagina . '.pdf';
        $caminhoArquivo = $diretorioDestino . '/' . $nomeArquivo;

        $pdf->Output($caminhoArquivo, 'F');
        // Fecha o documento atual
        $pdf->close();

        // Inicializa um novo objeto FPDI para a próxima página
        $pdf = new Fpdi();
        $pdf->setSourceFile($caminhoArquivoPDF);
    }

    // Retorna o diretório dos arquivos PDF gerados
    return $diretorioDestino;
}


public function cadastrarDadosEPdfPorUsuario(Request $request){
    // Obter o arquivo PDF
    $arquivo = $request->file('path');
    
    // Realizar o parsing do arquivo PDF
    $parser = new Parser();
    $pdf = $parser->parseFile($arquivo->getPathname());

    // Array para armazenar os usuários encontrados
    $usuarios = [];
    // Percorrer as páginas do PDF
    foreach ($pdf->getPages() as $numeroPagina => $pagina) {
        // Obter o texto da página
        $textoPagina = $pagina->getText();

        
        // Extrair o nome do usuário do texto da página
        $nomeUsuario = $this->extrairNomeUsuario($textoPagina);
        $emailUsuario = $this->extrairEmailUsuario($nomeUsuario);
        $fotoUsuario = $this->extrairFotoUsuario($nomeUsuario);
        $dataAtual = Carbon::now();
        $numeroMes = $dataAtual->format('m');
        $mes = $request->mes;
        $diretorioDestino = $nomeUsuario;

        // Verifica se o diretório de destino existe, senão cria
        if (!file_exists($diretorioDestino)) {
            mkdir($diretorioDestino, 0777, true);
        }
        //dd($numeroMes);
        if (!empty($nomeUsuario)) {
            $usuarios[] = $nomeUsuario;
            // Dividir a página em um novo arquivo PDF
            $novoPDF = new Fpdi();
            $novoPDF->AddPage();
            $novoPDF->setSourceFile($arquivo->getPathname());
            $novoPDF->useTemplate($novoPDF->importPage($numeroPagina + 1));

            // Gerar um nome único para o novo arquivo
$nomeArquivo = 'arquivo_' . ($numeroPagina + 1) . '.pdf';

// Criar o diretório com o nome do usuário dentro da pasta 'pdf_dividido'
$pastaDestino = $nomeUsuario; // Substitua pelo nome do usuário
$pastaUsuario = storage_path("app/public/$nomeUsuario/Adiantamento/$mes");
if (!file_exists($pastaUsuario)) {
    mkdir($pastaUsuario, 0777, true);
}

// Salvar o novo arquivo dentro da pasta do usuário
$caminhoArquivo = $pastaUsuario . '/' . $nomeArquivo;
$novoPDF->Output($caminhoArquivo, 'F');

            //dd($emailUsuario);
            $adiantamento = new Adiantamento();
            $adiantamento->colaborador = $nomeUsuario;
            $adiantamento->arquivo = $caminhoArquivo;
            $adiantamento->foto = $fotoUsuario;
            $adiantamento->email = $emailUsuario;
            $adiantamento->numeromes = $numeroMes;
            $adiantamento->mes = $mes;
            $adiantamento->status = "PENDENTE";
            $adiantamento->class_status = "badge badge-outline-warning";
            //dd($adiantamento->mes);
            $adiantamento->save();

            
        }
    }

    if($adiantamento->save()){
        alert()->success('Contracheques cadastrados com sucesso!');
        return redirect()->route('adiantamento.index');
    }
}

private function extrairNomeUsuario($textoPagina)
{
    // Suposição: O nome do usuário está precedido pela palavra "Nome:" ou "Nome do usuário:"
    $padroes = ['/Nome\s*(\w+)/', '/Nome do usuário:\s*(\w+)/'];

    foreach ($padroes as $padrao) {
        if (preg_match($padrao, $textoPagina, $matches)) {
            // O nome do usuário foi encontrado
            return $matches[1];
        }
    }

    // Caso nenhum nome de usuário seja encontrado
    return '';
}

private function extrairEmailUsuario($nomeUsuario){

    $emailUsuario = User::where('name', $nomeUsuario)->pluck('email')->first();

    return $emailUsuario;
}

private function extrairFotoUsuario($nomeUsuario){

    $fotoUsuario = User::where('name', $nomeUsuario)->pluck('image')->first();

    return $fotoUsuario;
}

}