<table>
    <thead>
    <tr>
        <th style="width: 90px">Имя параметра</th>
        <th style="width: 120px">Значение параметра</th>
        <th style="width: 90px">Цена параметра</th>
        <th style="width: 70px">Порядок</th>
        <th style="width: 510px">Путь до фото</th>
    </tr>
    </thead>
    <tbody>
    @foreach($option->values as $value)
        <tr>
            @if($loop->first)
                <td>{{ $option->name }}</td>
            @else
                <td></td>
            @endif
            <td>{{ $value->value }}</td>
            <td>{{ $value->price ?? 0}}</td>
            <td>{{ $value->order ?? 0}}</td>
            <td>{{ $value->image ?? ''}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
