<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Новый заказ</title>
</head>
<body>

<h1>Спасибо за заказ!</h1>

<p>Вы оформили заказ на сумму {{ $userInfo['price'] ?? '' }}.</p>

<ul>
    @foreach ($items as $item)
        <li>
            <strong>Название:</strong> {{ $item['name'] }} (<a style="text-decoration: none; color: blue" href="{{ $item['url'] }}">Ссылка на товар</a>)<br>
            @if (isset($item['photo']))
                <img src="{{ $item['photo'] }}" alt="{{ $item['name'] }}" style="max-width: 200px;"><br>
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