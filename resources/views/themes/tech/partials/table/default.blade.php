{{--
    Table — Tech Variant
    Fields: title, description,
            rows[]: cells[] (simple string array)
--}}
<section class="py-5 bg-white">
    <div class="container py-4 max-w-lg mx-auto">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-5">
                @if(!empty($data['title']))
                    <h2 class="display-6 font-serif text-primary mb-3">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
                @if(!empty($data['description']))
                    <p class="text-muted font-sans mt-3">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['rows']))
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="table-responsive rounded shadow-sm border border-accent border-opacity-25 bg-main p-3">
                        <table class="table table-hover table-borderless mb-0 font-sans align-middle">
                            @foreach($data['rows'] as $rowIndex => $row)
                                @php
                                    $cells = is_array($row['cells'] ?? null) ? $row['cells'] : [];
                                @endphp
                                @if($rowIndex === 0)
                                    {{-- Baris pertama sebagai header --}}
                                    <thead style="border-bottom: 2px solid var(--color-accent);">
                                        <tr>
                                            @foreach($cells as $cell)
                                                <th class="py-3 px-4 font-serif text-primary fs-5">
                                                    {{ is_array($cell) ? ($cell['value'] ?? '') : $cell }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                @else
                                    <tr style="border-bottom: 1px solid rgba(212,175,55,0.15);">
                                        @foreach($cells as $cellIndex => $cell)
                                            <td class="py-3 px-4 text-secondary {{ $cellIndex === 0 ? 'fw-semibold text-primary' : '' }}">
                                                {{ is_array($cell) ? ($cell['value'] ?? '') : $cell }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
