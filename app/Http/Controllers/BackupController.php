<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $backup = Backup::all();
        return view('backups.index', compact('backup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ip'      => 'required|string|max:255',
            'porta'   => 'required|string|max:10',
            'banco'   => 'required|string|max:255',
            'usuario' => 'required|string|max:255',
            'senha'   => 'required|string',
        ]);
        Backup::create($request->all());

        return redirect()->route('backups.index')->with('success', 'Conexão criada com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(Backup $backup)
    {
        return view('backups.show', compact('backup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Backup $backup)
    {
        return view('backups.edit', compact('backup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Backup $backup)
    {
        $request->validate([
            'ip'      => 'required|string|max:255',
            'porta'   => 'required|string|max:10',
            'banco'   => 'required|string|max:255',
            'usuario' => 'required|string|max:255',
            'senha'   => 'nullable|string',
        ]);

        $dados = $request->all();
        
        if (empty($dados['senha'])){
            unset($dados['senha']);
        }

        $backup->update($dados);
        return redirect()->route('backups.index')->with('success', 'Dados atualizados com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Backup $backup)
    {
        $backup->delete();
            return redirect()->route('backups.index')->with('success', 'Deletado com sucesso');
    }
}
