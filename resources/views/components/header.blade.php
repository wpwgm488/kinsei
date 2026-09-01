<header style="
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 64px;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    box-sizing: border-box;
    z-index: 10;
">

    {{-- 左端：トップページへのリンク --}}
    <a
        href="/"
        style="
            color: #374151;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
        "
    >
        {{ __('messages.app_name') }}
    </a>

    {{-- 右端：ユーザーメニュー --}}
    @auth
        <details style="position: relative;">

            <summary style="
                list-style: none;
                cursor: pointer;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #374151;
                color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                user-select: none;
            ">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </summary>

            <div style="
                position: absolute;
                top: 48px;
                right: 0;
                width: 220px;
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.12);
                overflow: hidden;
            ">

                <div style="
                    padding: 14px 16px;
                    border-bottom: 1px solid #e5e7eb;
                ">
                    <div style="
                        font-weight: bold;
                        color: #111827;
                    ">
                        {{ auth()->user()->name }}
                    </div>

                    <div style="
                        margin-top: 4px;
                        font-size: 12px;
                        color: #6b7280;
                    ">
                        {{ auth()->user()->email }}
                    </div>
                </div>

                <a
                    href="{{ \App\Filament\Pages\ChangePassword::getUrl() }}"
                    style="
                        display: block;
                        padding: 12px 16px;
                        color: #374151;
                        text-decoration: none;
                        font-size: 14px;
                    "
                >
                    パスワード変更
                </a>

                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf

                    <button
                        type="submit"
                        style="
                            width: 100%;
                            padding: 12px 16px;
                            background: #ffffff;
                            color: #dc2626;
                            border: none;
                            border-top: 1px solid #f3f4f6;
                            text-align: left;
                            font-size: 14px;
                            cursor: pointer;
                        "
                    >
                        ログアウト
                    </button>
                </form>

            </div>

        </details>
    @endauth

</header>
