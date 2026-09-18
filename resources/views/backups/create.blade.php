<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nova Conexão') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <!-- <div class="max-w-2xl mx-auto sm:px-6 lg:px-8"> -->
        <div class="w-full mx-auto sm:px-6 lg:px-8" style="max-width: 30%">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('backups.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-3 gap-4">
                        <!-- Campo IP -->
                        <div class="col-span-2">
                            <x-input-label for="ip" :value="__('IP/Host')" />
                            <x-text-input id="ip" name="ip" type="text" class="mt-1 block w-full" placeholder="192.168.1.1" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('ip')" />
                        </div>

                        <!-- Campo Porta -->
                        <div>
                            <x-input-label for="porta" :value="__('Porta')" />
                            <x-text-input id="porta" name="porta" type="text" class="mt-1 block w-full" placeholder="3306" required />
                            <x-input-error class="mt-2" :messages="$errors->get('porta')" />
                        </div>
                    </div>

                    <!-- Campo Banco -->
                    <div>
                        <x-input-label for="banco" :value="__('Banco de Dados')" />
                        <x-text-input id="banco" name="banco" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('banco')" />
                    </div>

                    <!-- Campo Usuário -->
                    <div>
                        <x-input-label for="usuario" :value="__('Usuário')" />
                        <x-text-input id="usuario" name="usuario" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('usuario')" />
                    </div>

                    <!-- Campo Senha -->
                    <div>
                        <x-input-label for="senha" :value="__('Senha')" />
                        <x-text-input id="senha" name="senha" type="password" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('senha')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Salvar Conexão') }}</x-primary-button>
                        <a href="{{ route('backups.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
