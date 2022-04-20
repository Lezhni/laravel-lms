<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Мир Трейдинга - Обучающая платформа</title>
    <style>
        body {
            overflow-y: scroll;
        }
    </style>
</head>
<body>
<div id="app"></div>

<script src="{{ mix('js/main.js') }}"></script>
<script>
    (function(w,d,u){
        var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
        var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
    })(window,document,'https://new.crmcreacept.ru/upload/crm/site_button/loader_15_qm09td.js');
</script>
</body>
</html>