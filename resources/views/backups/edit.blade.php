<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Conexão') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8" style="width: 30%">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('backups.update', $backup->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-3 gap-4">
                        <!-- Campo IP -->
                        <div class="col-span-2">
                            <x-input-label for="ip" :value="__('IP/Host')" />
                            <x-text-input id="ip" name="ip" type="text" class="mt-1 block w-full" :value="old('ip', $backup->ip)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('ip')" />
                        </div>

                        <!-- Campo Porta -->
                        <div>
                            <x-input-label for="porta" :value="__('Porta')" />
                            <x-text-input id="porta" name="porta" type="text" class="mt-1 block w-full" :value="old('porta', $backup->porta)" required />
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
                            <option value="" disabled selected class="text-gray-400 dark:text-gray-500">
                                {{ __('Selecionar...') }}
                            </option>    
                            <option value="trimestral">Trimestral</option>
                            <option value="mensal">Mensal. Todo dia 1</option>
                            <option value="semanal">Semanal. Toda segunda-feira</option>
                            <option value="alternado">Seg, Qua e Sex</option>
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
                        <x-text-input id="senha" name="senha" type="password" class="mt-1 block w-full" />
                        <p class="text-xs text-gray-500 mt-1">Deixe em branco para manter a senha atual.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('senha')" />
                    </div>

                    <div class="flex items-center justify-end gap-4 w-full">
                        <a href="{{ route('backups.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancelar</a>
                        <x-primary-button>{{ __('Atualizar Conexão') }}</x-primary-button>            
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
