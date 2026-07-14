{{--
    Table — Default Variant
    Fields: title, description,
            rows[]: cells[] (simple string array)
--}}
<section class="table-block table-block--default py-16 bg-white">
    <div class="container mx-auto px-6 max-w-4xl">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="mb-8">
                @if(!empty($data['title']))
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-500">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['rows']))
            <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
                <table class="w-full text-sm text-left">
                    @foreach($data['rows'] as $rowIndex => $row)
                        @php
                            $cells = is_array($row['cells'] ?? null) ? $row['cells'] : [];
                        @endphp
                        @if($rowIndex === 0)
                            {{-- Baris pertama sebagai header --}}
                            <thead class="bg-indigo-600 text-white">
                                <tr>
                                    @foreach($cells as $cell)
                                        <th class="px-5 py-3 font-semibold whitespace-nowrap">
                                            {{ is_array($cell) ? ($cell['value'] ?? '') : $cell }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                        @else
                            <tr class="{{ $rowIndex % 2 === 0 ? 'bg-white' : 'bg-slate-50' }} border-t border-slate-100 hover:bg-indigo-50 transition">
                                @foreach($cells as $cell)
                                    <td class="px-5 py-3 text-slate-700">
                                        {{ is_array($cell) ? ($cell['value'] ?? '') : $cell }}
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
