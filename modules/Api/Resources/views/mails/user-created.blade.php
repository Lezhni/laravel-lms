<p>Вы успешно зарегистрированы на обучающей платформе «{{ $siteName }}». Ваши данные для входа:</p>
<ul>
    <li>Адрес платформы: <a href="{{ route('home') }}">{{ route('home') }}</a></li>
    <li>Логин: {{ $user->email }}</li>
    <li>Пароль: {{ $rawPassword }}</li>
</ul>