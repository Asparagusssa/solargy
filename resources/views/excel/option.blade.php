@php use App\Models\Option; @endphp
<table>
    <thead>
    <tr>
        <th>id</th>
        <th>option_id</th>
        <th>option_name</th>
        <th>value</th>
        <th>price</th>
    </tr>
    </thead>
    <tbody>
    @foreach($values as $value)
            <?php
                $option_name = Option::find($value->option_id)->name;
            ?>
        <tr>
            <td>{{ $value->id }}</td>
            <td>{{ $value->option_id }}</td>
            <td>{{ $option_name }}</td>
            <td>{{ $value->value }}</td>
            <td>{{ $value->price }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
