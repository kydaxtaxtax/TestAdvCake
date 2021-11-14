<?php
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    $text = $_POST['text'];

    $text = trim($text); /*Удаляем ошибочные пробелы в начале и в конце строки*/

    $words = explode(" ", $text); /*Получпем массив со словами из текста*/
    $NumberWords = count($words); /*Получпем длинну массива*/

    function SavePunct($WordNoPunct) // Переворачивает слово с сохранением регистра
    {
      $ReplaceWord = ''; /*Стираем промежуточное слово*/

      $LengthWordNoPunct = mb_strlen($WordNoPunct); /*Узнаем длинну слова*/
      $j = 0; // счетчик
      for ($i = $LengthWordNoPunct - 1; $i >= 0; $i--) /*Проходим по всем буквам начиная с конца*/
      {
        $symbol = mb_substr($WordNoPunct, $i, 1); /*Записываем символ*/
        $ReSymbol = mb_substr($WordNoPunct, $j, 1); /*Записываем отраженный символ*/

        if(mb_strtoupper($ReSymbol, "UTF-8") != $ReSymbol) //Проверка регистра буквы
        {
          $ReplaceWord .= mb_strtolower($symbol);
        }
        else
        {
          $ReplaceWord .= mb_strtoupper($symbol);
        }
        $j++;
      }
      return $ReplaceWord;
    }

    function InsertPunctMarks($ReplaceWord, $MarkPunct) // Вставляет знаки пунктуации в слово
    {
      $lengthMarkPunct = count($MarkPunct); //Узнаем длину массива
      if($lengthMarkPunct != 0) //
      {
        for ($i = 0; $i <= $lengthMarkPunct - 1; $i++) /*Проходит по всем знакам пунктуации*/
        {
          $pos = $MarkPunct[$i][0]; //Узнаем позицию знака
          $IntermediateWord = mb_substr($ReplaceWord, 0, $pos) .$MarkPunct[$i][1]. mb_substr($ReplaceWord, $pos); //разделяем слово и вставляем знак пунктуации на место
          $ReplaceWord = $IntermediateWord;
        }
      }
      return $ReplaceWord;
    }


    function FlipString($word) //Переворачивает слово с сохранением Пунктуации и регистра
    {
      $WordNoPunct = ''; /*Стираем промежуточное слово*/
      $MarkPunct = []; /*Стираем массив со знакоми пунктуации и их позицией*/

      $LengthWord = mb_strlen($word); /*Узнаем длинну слова*/

      for ($i = 0; $i <= $LengthWord; $i++) // Удаляем знаки пунктуации и записываем их в массив
      {
        $symbol = mb_substr($word, $i, 1); /*Записываем символ*/

        if(ctype_punct($symbol)) /*Проверяем евляется ли символ знаком пунктуации*/
        {
          array_push($MarkPunct, array($i, $symbol));/*Записываем символ в вспомогательный массив*/
        }
        else
        {
          $WordNoPunct .= $symbol;
        }
      }

      $ReplaceWord = SavePunct($WordNoPunct);
      $ReplaceWord = InsertPunctMarks($ReplaceWord, $MarkPunct);

      return $ReplaceWord;
    }

    for ($i = 0; $i < $NumberWords; $i++) /*Проходим по всем словам*/
    {
      $res .= ' '.FlipString($words[$i]); /*Переворачиваем и записываем слова с пробелами*/
    }
    $res = trim($res); /*Удаляем лишний пробел в начале*/

    echo "Исходная строка: ".$text."<br>";
    echo "Измененная строка: ".$res;
  }
?>
