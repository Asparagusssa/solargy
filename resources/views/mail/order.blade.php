<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Новый заказ</title>
</head>
<body>
<h1>Новый заказ</h1>

<h2>Информация о пользователе</h2>
@foreach ($userInfo as $user)
    <p><strong>Имя:</strong> {{ $user['name'] }}</p>
    <p><strong>Телефон:</strong> {{ $user['phone'] }}</p>
    <p><strong>Цена:</strong> {{ $user['price'] }}</p>
@endforeach

<h2>Товары</h2>
<ul>
    @foreach ($items as $item)
        <li>
            <strong>Название:</strong> {{ $item['name'] }} (<a style="text-decoration: none; color: blue" href="{{ $item['url'] }}">Ссылка на товар</a>)<br>
            @if (isset($item['photo']))
                <img src="{{ $item['photo_url'] }}" alt="{{ $item['name'] }}" style="max-width: 200px;"><br>
            @endif
            @if (isset($item['options']))
                <ul>
                    @foreach ($item['options'] as $option)
                        <li>
                            <strong>{{ $option['name'] }}:</strong> {{ $option['values'][0]['value'] }}
                        </li>
                    @endforeach
                </ul>
            @endif
            <strong>Цена:</strong> {{ $item['price'] }} руб. <br>
            <strong>Количество:</strong> {{ $item['quantity'] ?? 1 }}
            @if(!$loop->last)
                <div>------------------</div>
            @endif
        </li>
    @endforeach
</ul>
</body>
</html>
