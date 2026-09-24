<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $backup->exists ? __('Editar Conexão') : __('Nova Conexão') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8" style="max-width: 30%">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ $backup->exists ? route('backups.update', $backup->id) : route('backups.store') }}" class="space-y-6">
                    @csrf

                    @if($backup->exists)
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-3 gap-4">
                        <!-- Campo IP -->
                        <div class="col-span-2">
                            <x-input-label for="ip" :value="__('IP/Host')" />
                            <x-text-input id="ip" name="ip" type="text" class="mt-1 block w-full" placeholder="192.168.1.1" :value="old('ip', $backup->ip)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('ip')" />
                        </div>

                        <!-- Campo Porta -->
                        <div>
                            <x-input-label for="porta" :value="__('Porta')" />
                            <x-text-input id="porta" name="porta" type="text" class="mt-1 block w-full" placeholder="3306" :value="old('porta', $backup->porta)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('porta')" />
                        </div>
                    </div>

                    <!-- Campo Banco -->
                    <div>
                        <x-input-label for="banco" :value="__('Banco de Dados')" />
                        <x-text-input id="banco" name="banco" type="text" class="mt-1 block w-full" :value="old('banco', $backup->banco)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('banco')" />
                    </div>

                    <!-- Campo Frequência -->
                    <div>
                        <!-- Label do Breeze -->
                        <x-input-label for="frequencia" :value="__('Frequência do Backup')" />

                        <!-- O Novo Select Baseado no Padrão Breeze -->
                        <x-select-input id="frequencia" name="frequencia" class="block mt-1 w-full">
                            <option value="nunca" <?php echo old('frequencia', $backup->frequencia) == 'nunca' ? 'selected' : ''?>>Nunca</option>
                            <option value="trimestral" <?php echo old('frequencia', $backup->frequencia) == 'trimestral' ? 'selected' : ''?>>Trimestral, dia 1 à meia-noite</option>
                            <option value="mensal" <?php echo old('frequencia', $backup->frequencia) == 'mensal' ? 'selected' : ''?>>Mensal, todo dia 1 à meia-noite</option>
                            <option value="semanal" <?php echo old('frequencia', $backup->frequencia) == 'semanal' ? 'selected' : ''?>>Semanal, toda segunda-feira à meia-noite</option>
                            <option value="alternado" <?php echo old('frequencia', $backup->frequencia) == 'alternado' ? 'selected' : ''?>>Seg, Qua e Sex à meia-noite</option>                            
                        </x-select-input>

                        <!-- Mensagem de Erro do Breeze (opcional) -->
                        <x-input-error :messages="$errors->get('frequencia')" class="mt-2" />
                    </div>

                    <!-- Campo Usuário -->
                    <div>
                        <x-input-label for="usuario" :value="__('Usuário')" />
                        <x-text-input id="usuario" name="usuario" type="text" class="mt-1 block w-full" :value="old('usuario', $backup->usuario)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('usuario')" />
                    </div>

                    <!-- Campo Senha -->
                    <div>
                        <x-input-label for="senha" :value="__('Senha')" />
                        <x-text-input id="senha" name="senha" type="password" class="mt-1 block w-full" action="{{ $backup->exists ? '' : 'required' }}" />
                        
                        @if($backup->exists)
                            <p class="text-xs text-gray-500 mt-1">Deixe em branco para manter a senha atual.</p>
                        @endif
                        
                        <x-input-error class="mt-2" :messages="$errors->get('senha')" />
                    </div>

                    <div class="flex items-center justify-end gap-4 w-full">
                        <a href="{{ route('backups.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancelar</a>
                        <x-primary-button>{{ __('Salvar') }}</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>