<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestão de Backup') }}
            </h2>
            <a href="{{ route('backups.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition ease-in-out duration-150">
                + Nova Conexão
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                
                @if(session('success'))
                    <div class="mb-4 text-green-600 font-medium text-sm">{{ session('success') }}</div>
                @endif                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="py-2">IP/Host</th>
                                <th class="py-2">Porta</th>
                                <th class="py-2">Banco de Dados</th>
                                <th class="py-2">Usuário</th>
                                <th class="py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($backup as $item)
                                <tr class="border-b text-center dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-3">{{ $item->ip }}</td>
                                    <td class="py-3">{{ $item->porta }}</td>
                                    <td class="py-3">{{ $item->banco }}</td>
                                    <td class="py-3">{{ $item->usuario }}</td>
                                    <td class="py-3 flex justify-center gap-4">
                                        <a href="{{ route('backups.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Editar</a>
                                        
                                        <form action="{{ route('backups.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta conexão?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500">Nenhum backup configurado ainda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div> 

            </div>
        </div>
    </div>
</x-app-layout>
