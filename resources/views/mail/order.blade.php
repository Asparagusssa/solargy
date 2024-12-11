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
            <strong>Название:</strong> {{ $item['name'] }} <br>
            <strong>Цена:</strong> {{ $item['price'] }} руб.
            @if (isset($item['options']))
                <ul>
                    @foreach ($item['options'] as $option)
                        <li>
                            <strong>Опция:</strong> {{ $option['name'] }}
                            <ul>
                                @foreach ($option['values'] as $value)
                                    <li>
                                        <strong>Значение:</strong> {{ $value['value'] }}: {{ $value['price'] }} руб.
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
</body>
</html>
