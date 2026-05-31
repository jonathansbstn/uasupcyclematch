@props(['item'])
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 p-6 mb-4">
    <div class="flex justify-between items-center">
        <h4 class="text-xl font-bold text-gray-800">{{ $item->title }}</h4>
        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded uppercase">{{ $item->fabric_type }}</span>
    </div>
    <p class="text-gray-600 mt-2 italic">"{{ $item->description }}"</p>
    <div class="mt-4 flex justify-between items-center text-sm text-gray-400">
        <span>Diposting oleh: {{ $item->user->name }}</span>
        <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-4 rounded transition">Klaim Limbah</button>
    </div>
</div>