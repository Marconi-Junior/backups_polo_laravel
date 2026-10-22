<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Monitoramento de Conexão Internet') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                
                @if(session('success'))
                    <div class="mb-4 text-green-600 font-medium text-sm">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="mb-4 text-red-600 font-medium text-sm">{{ session('error') }}</div>
                @endif                

                <div class="flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-700 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Estado Atual da Rede</h3>
                    
                    @if(session('internet_test_result'))
                        @php $result = session('internet_test_result'); @endphp
                        
                        <div class="flex items-center space-x-4 mb-4">
                            <span class="flex h-4 w-4 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $result['online'] ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 {{ $result['online'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            </span>
                            <span class="text-2xl font-bold uppercase tracking-wider">
                                {{ $result['online'] ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                        
                        <div class="text-sm text-gray-600 dark:text-gray-400 grid grid-cols-2 gap-x-8 gap-y-1">
                            <div><strong>Última Verificação:</strong> {{ $result['checked_at'] }}</div>
                            <div><strong>Latência/Ping:</strong> {{ $result['latency'] }} ms</div>
                            <div><strong>Host Testado:</strong> {{ $result['host'] }}</div>
                        </div>
                    @else
                        <div class="text-gray-500 py-4">Nenhum teste executado recentemente. Clique no botão abaixo para testar.</div>
                    @endif

                    <form action="{{ route('internet.test') }}" method="POST" class="mt-6">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-emerald-500 text-base font-semibold uppercase tracking-widest rounded-md shadow-sm transition hover:underline">
                            Executar Teste de Conexão
                        </button>
                    </form>
                </div>

                <!-- Painel de Ações de Relatórios -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    
                    <div class="flex gap-4 justify-end">
                        
                        <!-- Botão Email -->
                        <form action="{{ route('internet.notify.email') }}" method="POST">
                            @csrf
                            <button type="submit" @if(!session('internet_test_result')) disabled @endif class="inline-flex items-center px-4 py-2 bg-emerald-800 dark:bg-emerald-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md shadow-sm hover:bg-emerald-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Enviar por E-mail
                            </button>
                        </form>

                        <!-- Botão Telegram (Próxima etapa) -->
                        <button type="button" disabled class="inline-flex items-center px-4 py-2 bg-gray-400 bg-emerald-800 dark:bg-emerald-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md shadow-sm cursor-not-allowed opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m20.665 3.717-17.73 6.837c-1.21.486-1.203 1.161-.222 1.462l4.552 1.42 10.532-6.645c.498-.303.953-.14.579.192l-8.533 7.701h-.002l.002.001-.314 4.692c.46 0 .663-.211.921-.46l2.211-2.15 4.599 3.397c.848.467 1.457.227 1.668-.785l3.019-14.228c.309-1.239-.473-1.8-1.282-1.434z"/>                
                            </svg>
                            Enviar por Telegram (Pendente)
                        </button>

                    </div>
                </div>

            </div>
            
            <div class="flex items-center justify-end mt-4 w-full">
                <a href="{{ route('dashboard') }}" class="text-base text-gray-600 dark:text-gray-400 hover:underline">Voltar ao Painel</a>
            </div>
        </div>        
    </div>
</x-app-layout>
