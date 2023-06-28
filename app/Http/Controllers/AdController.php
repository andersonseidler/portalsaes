<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Mail\SendEmail;
use App\Models\Adiantamento;
use Illuminate\Http\Request;
use App\Models\User;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;

use setasign\Fpdi\PdfParser\PdfParser;

class AdController extends Controller
{   

    protected $model;

    public function __construct(Adiantamento $pag)
    {
        $this->model = $pag;
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
        //dd($data);
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


public function lerConteudoPDF(Request $request)
{
    $arquivo = $request->file('path');
    $palavraProcurada = 'Fernando';

    $parser = new Parser();
    $pdf = $parser->parseFile($arquivo->getPathname());

    $pages = $pdf->getPages();
    $paginaEncontrada = null;

    foreach ($pages as $numeroPagina => $pagina) {
        $text = $pagina->getText();

        if (strpos($text, $palavraProcurada) !== false) {
            $paginaEncontrada = $numeroPagina + 1;
            break;
        }
    }

    if ($paginaEncontrada !== null) {
        $pdf = new Fpdi();
        $pdf->setSourceFile($arquivo->getPathname());
        $templateId = $pdf->importPage($paginaEncontrada);
        $pdf->AddPage();
        $pdf->useTemplate($templateId);

        // Gerar o conteúdo do novo PDF
        ob_start();
        $pdf->Output();
        $conteudoPdf = ob_get_clean();

        // Definir o cabeçalho Content-Type para PDF
        header('Content-Type: application/pdf');

        // Exibir o PDF na página
        echo $conteudoPdf;
    } else {
        echo "A palavra '$palavraProcurada' não foi encontrada no PDF.";
    }
}
}