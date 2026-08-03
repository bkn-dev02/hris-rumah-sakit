<li>
    <div class="flex items-center justify-between rounded-lg px-3 py-2 hover:bg-slate-50" style="padding-left: {{ $depth * 24 + 12 }}px">
        <div class="flex items-center gap-2">
            <i class="fa {{ $department->children->isNotEmpty() ? 'fa-folder text-amber-500' : 'fa-folder-open text-slate-300' }} text-sm"></i>
            <span class="text-sm font-medium text-slate-700">{{ $department->name }}</span>
            <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-500">{{ $department->code }}</code>
            @unless($department->is_active)
            <x-shared::badge variant="secondary" size="sm">Nonaktif</x-shared::badge>
            @endunless
        </div>
        <a href="{{ route('master.departments.edit', $department->id) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
    </div>

    @if($department->children->isNotEmpty())
    <ul class="space-y-1">
        @foreach($department->children as $child)
        @include('master::departments._tree-node', ['department' => $child, 'depth' => $depth + 1])
        @endforeach
    </ul>
    @endif
</li>