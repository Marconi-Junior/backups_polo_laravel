<?php

namespace App\Http\Controllers;

use App\Models\Backups;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $backups = Backups::all();
        return view('backups.index', compact('backups'));
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

        return redirect()->route('backups.index')->with('success', 'Configuração');
    }

    /**
     * Display the specified resource.
     */
    public function show(Backups $backups)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Backups $backups)
    {
        return view('backups.edit', compact('backup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Backups $backups)
    {
        $request->validate([
            'ip'      => 'required|string|max:255',
            'porta'   => 'required|string|max:10',
            'banco'   => 'required|string|max:255',
            'usuario' => 'required|string|max:255',
            'senha'   => 'nullable|string',
        ]);

        $dados = $request->all();
        return redirect()->route('backups.index')->with('success', 'Configuração');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Backups $backups)
    {
        $backup->delete();
            return redirect()->route('backups.index')->with('success', 'Configuração');
    }
}
