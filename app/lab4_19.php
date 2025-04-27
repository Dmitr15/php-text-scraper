<?php
error_reporting(E_ERROR | E_PARSE);


function clear_formatting($html) {//sanitizeHtmlRegex
    //echo "Задание 19. “Чистка форматирования”. Убрать из исходного html все виды визуального форматирования, оставить только функциональные и структурные элементы, теги таблиц и ссылок.";
    
    // Список разрешенных структурных и функциональных тегов
    $allowedTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 
                   'table', 'tr', 'td', 'th', 'thead', 'tbody', 'tfoot', 'a'];
    
    // Удаляем все теги, кроме разрешенных. Второй параметр strip_tags принимает строку вида "<tag1><tag2>..."
    $html = strip_tags($html, '<' . implode('><', $allowedTags) . '>');
    
    // // Обрабатываем оставшиеся теги для очистки их атрибутов
    $html = preg_replace_callback(
        // Ищем все открывающие/закрывающие теги и захватываем:
        // 1. Имя тега (с возможным / для закрывающих)
        // 2. Все атрибуты после имени тега
        // Шаблон:
        // \w+ : буквы, цифры, подчеркивания
        '/<(\/?\w+)([^>]*)>/i', 
    function($matches) {
        $tag = strtolower($matches[1]);
        $attrs = '';
        
        // Для ссылок сохраняем только href
        if ($tag === 'a' || $tag === '/a') {
            // Ищем атрибут href в формате href="..." или href='...'
            if (preg_match('/href=(["\'])(.*?)\1/i', $matches[2], $hrefMatch)) {
                $href = htmlspecialchars($hrefMatch[2], ENT_QUOTES);
                $attrs = ' href="' . $href . '"';
            }
        }
        
        // Для всех остальных тегов удаляем все атрибуты
        return "<{$tag}{$attrs}>";
    }, $html);
    
    return $html;
}

function start_with_dash($html) {//findParagraphsStartingWithDash
   // echo htmlspecialchars("Задание 3. Вывести только прямую речь (абзацы <p>, начинающиеся с длинного тире)")."</br>";

    // Шаблон:
    // <p\b[^>]*> : находит открывающий тег <p> с возможными атрибутами
    // (\s*—.*?) : ищет пробельные символы (\s*) перед тире (—), затем любой контент до закрывающего тега
    // <\/p> : закрывающий тег </p>
    $pattern = '/<p\b[^>]*>(\s*—.*?)<\/p>/si';

    // Ищем все совпадения с шаблоном в переданном HTML
    preg_match_all($pattern, $html, $matches);

    // Собираем найденные абзацы в одну строку
    $response = "";
    foreach($matches[0] as $m){
        $response = $response.$m;
    }
    return $response;
}

function insert_commas($text) {//autoFormatText

    //echo "Задание 6. Автоматически расставить запятые перед “а” и “но”. Заменить три точки на спецзнак многоточия.</br>";
    // Заменяем три точки на многоточие (UTF-8 символ)
    $text = preg_replace('/\.{3}/u', '&hellip;', $text);
    
    // Добавляем запятые перед "а" и "но", если их нет
    // Шаблон:
    // (?<!,) : Проверяет, что перед совпадением нет запятой
    // \s+ : один или больше пробельных символов
    // (а|но) : группа, которая ищет союзы "а" или "но" 
    // (\s+) : группа, захватывающая пробелы после союза
    $text = preg_replace('/(?<!,)\s+(а|но)(\s+)/u', ', $1$2', $text);
    
    // Убираем лишние пробелы после запятой
    $text = preg_replace('/,\s+/u', ', ', $text);
    
    return $text;
}

function table_of_contents($html) {//processHtml
    //echo "Задание 11. Автоматически сформировать работающее оглавление по заголовкам 1-3 уровня.";
    
    $tocData = [];
    $usedIds = []; // Для отслеживания уникальности ID

    // Ищем все заголовки h1-h6 в HTML
    // Шаблон:
    // <h : ищет открывающий тег h, за которым следует цифра от 1 до 6 
    // ([1-6]) : захватывающая группа, сохраняющая номер заголовка
    // ([^>]*) : любые символы, кроме >, повторённые 0 или более раз
    // (.*?) : захватывает содержимое тега
    // .*? : нежадное совпадение любых символов
    // <\/h\1> : закрывающий тег, где \1 ссылается на номер открывающего тега
    preg_match_all('/<h([1-6])([^>]*)>(.*?)<\/h\1>/si', $html, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $level = intval($m[1]); // Уровень заголовка (1-6)
        $text = trim(strip_tags($m[3]));// Текст без HTML-тегов
        $attributes = $m[2];// Атрибуты тега

        if ($level > 3) {
            continue; // Игнорируем заголовки выше 3 уровня
        }

        // Извлекаем существующий ID
        $id = '';
        // Шаблон:
        // id : ищет строку id
        // \s* : пробельные символы 0 или более раз после id
        // \s* : пробельные символы 0 или более раз после =
        // ["\'] : двойная или одинарная кавычка с экранированием, внутри которых указано значение атрибута
        // [^"\']* : любые символы, кроме кавычек, повторённые 0 или более раз
        // ["\'] : Закрывающая кавычка
        if (preg_match('/id\s*=\s*["\']([^"\']*)["\']/i', $attributes, $idMatches)) {
            $id = $idMatches[1];
        } else {
            
            $slug = $text;
            $slug = preg_replace('/[^\w\s-]/u', '', $slug);// Удаляем спецсимволы
            $slug = preg_replace('/[\s_-]+/u', '-', $slug);// Заменяем пробелы на дефисы
            $slug = strtolower($slug);// Приводим к нижнему регистру
            $slug = trim($slug, '-'); // Убираем дефисы по краям

            // Генерируем уникальный ID из текста
            $originalSlug = $slug;
            $counter = 1;
            while (isset($usedIds[$slug])) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $id = $slug;
            $usedIds[$slug] = true;
        }

        $tocData[] = [
            'level' => $level,
            'text' => $text,
            'original' => $m[0],
            'id' => $id
        ];
    }

    // Заменяем исходные заголовки в HTML, добавляя ID
    $tocIndex = 0; // Индекс для отслеживания текущего элемента оглавления

    $processedHtml = preg_replace_callback(
        // Шаблон:
        // <h : Ищет открывающий тег h, за которым следует цифра от 1 до 6
        // ([1-6]) : захватывающая группа, сохраняющая номер тега
        // [^>]* : любой символ, кроме >, повторенный 0 или более раз
        // (.*?) : содержимое тега - любые символы, включая переносы строк
        // <\/h\1> : закрывающий тег, где \1 ссылается на номер открывающего тега
        '/<h([1-6])([^>]*)>(.*?)<\/h\1>/si',
        function ($matches) use (&$tocIndex, $tocData) {
            $level = intval($matches[1]);
            $attributes = $matches[2];
            $content = $matches[3];
            $cleanContent = trim(strip_tags($content));

            // Обрабатываем только заголовки для оглавления (уровень 1-3)
            if ($level <= 3) {
                $tocEntry = $tocData[$tocIndex];
                $id = $tocEntry['id'];

                // Добавляем ID, если его нет
                // Шаблон:
                // \s* : любое количество пробелов 
                if (!preg_match('/\bid\s*=/i', $attributes)) {
                    $attributes .= ' id="' . htmlspecialchars($id) . '"';
                }

                $tocIndex++;
            }

            return "<h{$matches[1]}{$attributes}>{$cleanContent}</h{$matches[1]}>";
        },
        $html
    );

    
    // Формируем HTML для оглавления
    $tocHtml = '';
    $currentLevel = 0;// Текущий уровень вложенности

    foreach ($tocData as $item) {

        // Обрезаем длинные названия пунктов
        if (strlen($item['text']) > 50 ) {
            $tmp = substr($item['text'], 0, 50)."...";
            $item['text'] = $tmp;
        }

        $newLevel = $item['level'];

        if ($newLevel > $currentLevel) {
            $tocHtml .= str_repeat('<ul>', $newLevel - $currentLevel);
        } else {
            $tocHtml .= '</li>' . str_repeat('</ul></li>', $currentLevel - $newLevel);
        }

        // Добавляем ссылку с якорем
        $tocHtml .= '<li><a href="#' . htmlspecialchars($item['id']) . '">' 
                  . htmlspecialchars($item['text']) . '</a>';

        $currentLevel = $newLevel;
    }

    // Закрываем оставшиеся теги списков
    if ($currentLevel > 0) {
        $tocHtml .= str_repeat('</ul></li>', $currentLevel);
    }

    // Объединяем оглавление и обработанный HTML
    return '<div class="toc"><ul>' . $tocHtml . '</ul></div>' . $processedHtml;
}