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
    @php
    $hasKeoData =
        (
            isset($keoInfo) &&
            (
                (isset($keoInfo['city']) && $keoInfo['city'] !== '') ||
                (isset($keoInfo['company_name']) && $keoInfo['company_name'] !== '') ||
                (isset($keoInfo['job_title']) && $keoInfo['job_title'] !== '') ||
                (isset($keoInfo['object_address']) && $keoInfo['object_address'] !== '') ||
                (isset($keoInfo['contact_method']) && $keoInfo['contact_method'] !== '') ||
                (
                    isset($keoInfo['request_features']) &&
                    is_array($keoInfo['request_features']) &&
                    count($keoInfo['request_features']) > 0
                ) ||
                (isset($keoInfo['description']) && $keoInfo['description'] !== '')
            )
        )
        ||
        (
            isset($attachment) &&
            is_array($attachment) &&
            count($attachment) > 0
        )
@endphp
    @if ($hasKeoData)
        <h3>Расчет КЕО</h3>

        <ul>
            @if (isset($keoInfo['city']) && $keoInfo['city'] !== '')
                <li>
                    <strong>Город: </strong>{{ $keoInfo['city'] }}
                </li>
            @endif

            @if (isset($keoInfo['company_name']) && $keoInfo['company_name'] !== '')
                <li>
                    <strong>Наименование организации: </strong>{{ $keoInfo['company_name'] }}
                </li>
            @endif

            @if (isset($keoInfo['job_title']) && $keoInfo['job_title'] !== '')
                <li>
                    <strong>Должность: </strong>{{ $keoInfo['job_title'] }}
                </li>
            @endif

            @if (isset($keoInfo['object_address']) && $keoInfo['object_address'] !== '')
                <li>
                    <strong>Адрес местоположения объекта: </strong>{{ $keoInfo['object_address'] }}
                </li>
            @endif

            @if (isset($keoInfo['contact_method']) && $keoInfo['contact_method'] !== '')
                @php
                    $methods = [
                        'telegram' => 'Telegram',
                        'whatsapp' => 'WhatsApp',
                        'phone'    => 'Звонок',
                        'email'    => 'Email',
                    ];
                @endphp

                <li>
                    <strong>Способ связи: </strong>
                    {{ $methods[$keoInfo['contact_method']] ?? $keoInfo['contact_method'] }}
                </li>
            @endif

            @if (isset($keoInfo['request_features']) && is_array($keoInfo['request_features']) && count($keoInfo['request_features']) > 0)
                @php
                    $featuresMap = [
                        'before_expertise' => 'Перед сдачей в экспертизу',
                        'after_expertise'  => 'Проект после экспертизы',
                        'keo_calc'         => 'Расчёт КЕО',
                        'system_selection' => 'Подбор системы',
                    ];
                @endphp

                <li>
                    <strong>Особенности запроса: </strong>
                    <ul>
                        @foreach ($keoInfo['request_features'] as $feature)
                            <li>{{ $featuresMap[$feature] ?? $feature }}</li>
                        @endforeach
                    </ul>
                </li>
            @endif

            @if (isset($keoInfo['description']) && $keoInfo['description'] !== '')
                <li>
                    <strong>Описание: </strong>{{ $keoInfo['description'] }}
                </li>
            @endif
        </ul>

        @if (isset($attachment) && is_array($attachment) && count($attachment) > 0)
            <h3>Прикреплённые файлы</h3>
            <ul>
                @foreach ($attachment as $file)
                    <li>{{ $file['original_name'] ?? basename($file['path']) }}</li>
                @endforeach
            </ul>
        @endif
    @endif
</body>
</html>
