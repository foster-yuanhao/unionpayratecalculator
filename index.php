<?php
// 初始化 cURL 会话
$curl = curl_init();

$hour = date("G");

// 根据当前小时判断是取昨天还是今天
if ($hour >= 0 && $hour < 11) {

    $date = date("Ymd", strtotime("-1 day"));
} else {

    $date = date("Ymd");
}
$today = $date;
$url = "地址";//替换为实际请求的地址
// 设置 cURL 选项
curl_setopt($curl, CURLOPT_URL,$url); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);
// 发送请求并获取响应
$str = curl_exec($curl);

// 关闭 cURL 会话
curl_close($curl);
$str = substr($str, 20);

// 然后截掉剩余字符串的后24个字符
// 使用strlen获取字符串长度，然后减去24来获取新的长度
$newStr = substr($str, 0, strlen($str) - 24);


// 解码 JSON 响应
$jsonResponse = json_decode($newStr, true);
// 检查是否有解码错误
if (json_last_error() != JSON_ERROR_NONE) {
    echo "JSON解码错误: " . json_last_error_msg();
} else {
    // 检查解码结果是否是数组
    if (is_array($jsonResponse)) {
        // 检查是否包含特定的键
        // 搜索 transCur 为 "MYR" 且 baseCur 为 "CNY" 的条目
$rateData = null;
foreach ($jsonResponse as $item) {
    if (is_array($item) && $item['transCur'] == 'MYR' && $item['baseCur'] == 'CNY') {
        $rateData = $item['rateData'];
        break;
    }
}

// 输出 rateData 的值
if ($rateData !== null) {
    echo "现在汇率1MYR = ".$rateData."CNY";
    echo "</br>";
    echo "本汇率跟随银联每天11am进行更新";
} else {
    echo "无数据";
}
        
    } else {
        echo "解码成功，但结果不是数组.";
    }
}

?>



<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>银联汇率计算器-马来西亚</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        .container {
            max-width: 100%;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            margin-top: 10px;
            display: block;
            color: #666;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="number"]:focus {
            border-color: #0056b3;
            outline: none;
        }

        footer {
            margin-top: 30px;
            color: #666;
        }

        /* Media Queries for larger screens */
        @media (min-width: 600px) {
            .container {
                max-width: 400px; /* Fixed width for larger screens */
            }
        }
    </style>
   <script>
        var rate =<?php
        echo $rateData;
        ?>; 

        function convertToMYR() {
            var cny = document.getElementById("cny").value;
            var myr = cny * rate;
            document.getElementById("myr").value = myr.toFixed(2);
        }

        function convertToCNY() {
            var myr = document.getElementById("myr").value;
            var cny = myr / rate;
            document.getElementById("cny").value = cny.toFixed(2);
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>银联汇率计算器-马来西亚</h2>
        <label for="cny">人民币 (CNY):</label>
        <input type="number" id="cny" oninput="convertToMYR()">
        <br><br>
        <label for="myr">马来西亚令吉 (MYR):</label>
        <input type="number" id="myr" oninput="convertToCNY()">
    </div>
    <footer>
        <p>&copy; 2023 银联汇率计算器-马来西亚 BY <a href="https://github.com/foster-yuanhao">foster_lee</a>. 所有权利保留。</p>
    </footer>
</body>
</html>
