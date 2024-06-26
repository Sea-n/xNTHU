@extends('layouts.rabbit')

@section('title', '首頁')

@section('head')
    <script src="/assets/js/index.js"></script>
@stop

@section('content')
    <h2 class="ts header">成立沿革</h2>
    <p>2020 年 5 月初，靠北清大 A 在快達到 30,000 篇文章時，突然遭到 Facebook <a target="_blank" href="/post/1">無預警下架</a>。</p>
    <p>當天早上 <a target="_blank" href="https://x.nctu.app/">靠北交大 2.0</a> 開發者將同套系統移植為清大版本，由於校方未提供 NTHU OAuth 服務，初期主要由交大學生代為審核投稿。</p>
    <p>當天下午有人成立了靠北清大 B，雖然仍使用 Crush Ninja 系統，但小編每小時審文維持不錯的發文頻率，因此在兩週後靠北清大 B 達到 1,000 人按讚時，本平台 <a target="_blank" href="/post/108">宣布退為備用板面</a>。</p>
    <br>

    <p>2020 年 9 月，靠北清大 B <a target="_blank" href="/post/113">遭 Facebook 下架</a>，本平台隨即恢復運作</p>
    <p>為了讓簡化驗證程序，本平台開始支援清大帳號自動化驗證，讓更多清大同學可以參與審核工作。</p>
    <br>

    <p>比起傳統 Crush Ninja 平台，靠北清大 2.0 做出了數項改變避免重蹈覆徹：</p>
    <ul>
        <li>為了維持更新頻率，將審核工作下放至所有交清師生，任何師生只要驗證過清大信箱即可自助審文；除緊急刪除明顯違法文章外，所有師生與管理者票票等值。</li>
        <li>為了達到審文透明化，雖然所有審核者皆為自由心證，未經過訓練也不強制遵從統一標準；但透過保留所有審核紀錄、被駁回的投稿皆提供全校師生檢視，增加審核標準的透明度。</li>
        <li>除了 Facebook 以外，也在 Telegram、Instagram、網站本身、Web Archive 等處永久備份文章，未來不幸遭檢舉下架時還可快速恢復以往繁榮。</li>
    </ul>

    <p>此專案收錄於 <a target="_blank" href="https://awesome.nctu.app/">Awesome NCTU</a>
        網站，也可參考 <a target="_blank" href="https://x.nctu.app/">靠北交大 2.0</a> 系統。</p>

    <h2 class="ts header">社群平台</h2>
    <p>除了本站文章列表外，您可以在以下 5 個社群媒體平台追蹤{{ env('APP_CHINESE_NAME') }} 帳號。</p>
    <div class="icon-row">
        <a id="telegram-icon" class="ts link tiny rounded image" target="_blank"
           href="https://t.me/{{ env('TELEGRAM_USERNAME') }}">
            <img src="/assets/img/social-telegram.svg" alt="Telegram"></a>
        <a id="twitter-icon" class="ts link tiny rounded image" target="_blank"
           href="https://twitter.com/{{ env('TWITTER_USERNAME') }}">
            <img src="/assets/img/social-twitter.svg" alt="Twitter"></a>
        <a id="plurk-icon" class="ts link tiny rounded image" target="_blank"
           href="https://www.plurk.com/{{ env('PLURK_USERNAME') }}">
            <img src="/assets/img/social-plurk.svg" alt="Plurk"></a>
        <a id="discord-icon" class="ts link tiny rounded image" target="_blank"
           href="https://discord.gg/{{ env('DISCORD_INVITE_LINK') }}">
            <img src="/assets/img/social-discord.svg" alt="Discord"></a>
        <!--<a id="facebook-icon" class="ts link tiny rounded image" target="_blank"
           href="https://www.facebook.com/{{ env('FACEBOOK_USERNAME') }}">
            <img src="/assets/img/social-facebook.svg" alt="Facebook"></a>-->
        <a id="instagram-icon" class="ts link tiny rounded image" target="_blank"
           href="https://www.instagram.com/{{ env('INSTAGRAM_USERNAME') }}">
            <img src="/assets/img/social-instagram.svg" alt="Instagram"></a>
    </div>

    <h2 class="ts header">審文機制</h2>
    <div id="review-content" style="height: 320px; overflow-y: hidden;">
        <p>新版{{ env('APP_CHINESE_NAME') }} 採自助式審文，所有交大師生皆可加入審核者的行列，以下是系統判斷標準</p>

        <h4>(A) 具名投稿</h4>
        <p>點擊右上角 Login 登入後，可用師生身份具名投稿，即使無人審核也會在 10 分鐘內自動發出，詳細判斷條件如下：</p>
        <ul>
            <li>10 分鐘以內：達到 4 個&nbsp;<button class="ts vote positive button">通過</button>
                &nbsp;且無&nbsp;<button class="ts vote negative button">駁回</button>
            </li>
            <li>10 分鐘至 1 小時：&nbsp;<button class="ts vote positive button">通過</button>
                &nbsp;比&nbsp;<button class="ts vote negative button">駁回</button>&nbsp;多 2 個
            </li>
            <li>1 小時以後：&nbsp;<button class="ts vote positive button">通過</button>
                &nbsp;不少於&nbsp;<button class="ts vote negative button">駁回</button>
            </li>
        </ul>

        <h4>(B) 交清 IP 位址</h4>
        <p>使用 113 或 114 位址投稿者，滿足以下條件即發出：</p>
        <ul>
            <li>10 分鐘以內：達到 6 個&nbsp;<button class="ts vote positive button">通過</button>
                &nbsp;且無&nbsp;<button class="ts vote negative button">駁回</button>
            </li>
            <li>10 分鐘至 1 小時：&nbsp;<button class="ts vote positive button">通過</button>
                &nbsp;比&nbsp;<button class="ts vote negative button">駁回</button>&nbsp;多 4 個
            </li>
            <li>1 小時以後：&nbsp;<button class="ts vote positive button">通過</button>
                &nbsp;比&nbsp;<button class="ts vote negative button">駁回</button>&nbsp;多 2 個
            </li>
        </ul>

        <h4>(C) 使用台灣 IP 位址</h4>
        <p>熱門投稿會快速登上版面，審核者們也有足夠時間找出惡意投稿，滿足以下條件即發出：</p>
        <ul>
            <li>10 分鐘以內：達到 8 個&nbsp;<button class="ts vote positive button">通過</button>
                &nbsp;且無&nbsp;<button class="ts vote negative button">駁回</button>
            </li>
            <li>10 分鐘至 1 小時：&nbsp;<button class="ts vote positive button">通過</button>
                &nbsp;比&nbsp;<button class="ts vote negative button">駁回</button>&nbsp;多 6 個
            </li>
            <li>1 小時以後：&nbsp;<button class="ts vote positive button">通過</button>
                &nbsp;比&nbsp;<button class="ts vote negative button">駁回</button>&nbsp;多 4 個
            </li>
        </ul>

    </div>
    <div id="hide-box">
        <big onclick="more();" style="cursor: pointer; color: black;">展開完整規則 <i class="dropdown icon"></i></big>
    </div>

    <div class="ts horizontal divider">現在開始</div>
    <div class="ts fluid stackable buttons"><a class="ts massive positive button" href="/submit">我要投稿</a>
        <a class="ts massive info button" href="/review">我想審核</a></div>

    @if (Auth::guest())
    <h2 class="ts header">使用 Telegram 登入</h2>
    <p>只要您曾綁定交清帳號，點擊下面按鈕即可以 Telegram 登入服務。</p>
    <script async src="https://telegram.org/js/telegram-widget.js?7" data-telegram-login="{{ env('APP_NAME') }}bot"
            data-size="large" data-auth-url="{{ $app['url']->to('/') }}/login/tg"
            data-request-access="write"></script>
    @elseif (!Auth::user()->tg_name)
    <h2 class="ts header">使用 Telegram 快速審核</h2>
    <p>點擊下面按鈕即可綁定 Telegram 帳號，讓您收到最即時的投稿通知，並快速通過/駁回貼文。</p>
    <script async src="https://telegram.org/js/telegram-widget.js?7" data-telegram-login="{{ env('APP_NAME') }}bot"
            data-size="large" data-auth-url="{{ $app['url']->to('/') }}/login/tg"
            data-request-access="write"></script>
    @elseif (Auth::user()->name == Auth::user()->stuid)
    <h2 class="ts header">使用 Telegram 快速審核</h2>
    <div class="ts positive message">
        <div class="header">您已連結成功！</div>
        <p>Tips: 使用 /name 指令即可修改您的暱稱</p>
    </div>
    @endif

    <h2 class="ts header">排行榜</h2>
    <p>排名積分會依時間遠近調整權重，正確的駁回 <a href="/deleted">已刪投稿</a> 將大幅提升排名。</p>
    <p>您可以在 <a href="/ranking">這個頁面</a> 查看排行榜。</p>

    <h2 class="ts header">服務聲明</h2>
    <p>感謝您使用「{{ env('APP_CHINESE_NAME') }}」（以下簡稱本網站），本網站之所有文章皆為不特定使用者自行投稿、不特定師生進行審核，並不代表本網站立場。</p>
    <p>如有侵害您權益之貼文，麻煩寄信至服務團隊，將在最短時間協助您撤下貼文或進行澄清。</p>
    <p>投稿者如散播不實訊息而遭司法單位追究，在司法機關提供調取票等充分條件下，本網站將依法提供 IP 位址配合偵辦，並公開於 <a href="/transparency">透明度報告</a> 頁面，切勿以身試法。</p>
@stop
