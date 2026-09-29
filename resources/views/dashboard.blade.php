<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Polo de Inovação do IFCE') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Grelha de Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Card de Gestão de Backups -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center space-x-3 mb-4">
                            <!-- Ícone Visual de Base de Dados / Backup -->
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s8-1.79 8-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold">Gestão de Backups</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Cadastre conexões e altere frequências dos dumps SQL dos bancos de dados.
                        </p>
                    </div>
                    
                    <!-- Botão de Ação direcionando para o Index -->
                    <div class="p-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                        <a href="{{ route('backups.index') }}" class="inline-flex items-center px-6 py-2 bg-emerald-800 hover:bg-emerald-600 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
                            Acessar
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

