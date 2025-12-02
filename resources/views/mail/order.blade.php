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
        $hasKeoData = !empty($keoInfo) && (
            !empty($keoInfo['city']) ||
            !empty($keoInfo['company_name']) ||
            !empty($keoInfo['job_title']) ||
            !empty($keoInfo['object_address']) ||
            !empty($keoInfo['contact_method']) ||
            (!empty($keoInfo['request_features']) && is_array($keoInfo['request_features']) && count($keoInfo['request_features']) > 0) ||
            !empty($keoInfo['description'])
        ) || (!empty($attachments) && count($attachments) > 0);;
    @endphp
    @if ($hasKeoData)
        <h3>Расчет КЕО</h3>

        <ul>
            @if (!empty($keoInfo['city']))
                <li>
                    <strong>Город: </strong>{{ $keoInfo['city'] }}
                </li>
            @endif

            @if (!empty($keoInfo['company_name']))
                <li>
                    <strong>Наименование организации: </strong>{{ $keoInfo['company_name'] }}
                </li>
            @endif

            @if (!empty($keoInfo['job_title']))
                <li>
                    <strong>Должность: </strong>{{ $keoInfo['job_title'] }}
                </li>
            @endif

            @if (!empty($keoInfo['object_address']))
                <li>
                    <strong>Адрес местоположения объекта: </strong>{{ $keoInfo['object_address'] }}
                </li>
            @endif

            @if (!empty($keoInfo['contact_method']))
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

            @if (!empty($keoInfo['request_features']) && is_array($keoInfo['request_features']))
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

            @if (!empty($keoInfo['description']))
                <li>
                    <strong>Описание: </strong>{{ $keoInfo['description'] }}
                </li>
            @endif
        </ul>
        @if (!empty($attachments))
            <h3>Прикреплённые файлы</h3>
            <ul>
                @foreach ($attachments as $file)
                    @php
                        $url = asset('storage/' . $file['path']);
                        $name = $file['original_name'] ?? basename($file['path']);
                    @endphp

                    <li>
                        <a href="{{ $url }}" style="text-decoration: none; color: blue">
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
</body>
</html>
