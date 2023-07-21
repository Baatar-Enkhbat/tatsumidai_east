<?php
mb_language("ja");
mb_internal_encoding("UTF-8");
$tdfk = array();
$tdfk[0] = '--';
$tdfk[1] = '千葉県';
$tdfk[2] = '東京都';
$tdfk[3] = '神奈川県';
$tdfk[4] = '岩手県';
$tdfk[5] = '山形県';
$tdfk[6] = '宮城県';
$tdfk[7] = '福島県';
$tdfk[8] = '茨城県';
$tdfk[9] = '栃木県';
$tdfk[10] = '群馬県';
$tdfk[11] = '埼玉県';
$tdfk[12] = '秋田県';
$tdfk[13] = '北海道';
$tdfk[14] = '青森県';
$tdfk[15] = '山梨県';
$tdfk[16] = '長野県';
$tdfk[17] = '新潟県';
$tdfk[18] = '富山県';
$tdfk[19] = '石川県';
$tdfk[20] = '福井県';
$tdfk[21] = '岐阜県';
$tdfk[22] = '静岡県';
$tdfk[23] = '愛知県';
$tdfk[24] = '三重県';
$tdfk[25] = '滋賀県';
$tdfk[26] = '京都府';
$tdfk[27] = '大阪府';
$tdfk[28] = '兵庫県';
$tdfk[29] = '奈良県';
$tdfk[30] = '和歌山県';
$tdfk[31] = '鳥取県';
$tdfk[32] = '島根県';
$tdfk[33] = '岡山県';
$tdfk[34] = '広島県';
$tdfk[35] = '山口県';
$tdfk[36] = '徳島県';
$tdfk[37] = '香川県';
$tdfk[38] = '愛媛県';
$tdfk[39] = '高知県';
$tdfk[40] = '福岡県';
$tdfk[41] = '佐賀県';
$tdfk[42] = '長崎県';
$tdfk[43] = '熊本県';
$tdfk[44] = '大分県';
$tdfk[45] = '宮崎県';
$tdfk[46] = '鹿児島県';
$tdfk[47] = '沖縄県';
session_start();
$mode = 'input';
$errmessage = array();
if (isset($_POST['back']) && $_POST['back']) {
    // 何もしない
} else if (isset($_POST['confirm']) && $_POST['confirm']) {
    // 確認画面
    $_SESSION['contact'] = htmlspecialchars($_POST['contact'], ENT_QUOTES);

    //if (!$_POST['time'] && $_POST['contact'] == "見学予約（無料）") {
    //    $errmessage[] = "ご希望時間を入力してください";
    //} else if (mb_strlen($_POST['time']) > 100) {
    //    $errmessage[] = "ご希望時間は100文字以内にしてください";
    //}

    if (isset($_POST['time']) || $_POST['contact'] == "資料をもらう（無料）") {
        $_SESSION['time'] = htmlspecialchars($_POST['time'], ENT_QUOTES);
    }

    if (!$_POST['fullname']) {
        $errmessage[] = "名前を入力してください";
    } else if (mb_strlen($_POST['fullname']) > 100) {
        $errmessage[] = "名前は100文字以内にしてください";
    }
    $_SESSION['fullname'] = htmlspecialchars($_POST['fullname'], ENT_QUOTES);

    if (!$_POST['furigana']) {
        $errmessage[] = "ふりがなを入力してください";
    } else if (mb_strlen($_POST['furigana']) > 100) {
        $errmessage[] = "ふりがなは100文字以内にしてください";
    }
    $_SESSION['furigana'] = htmlspecialchars($_POST['furigana'], ENT_QUOTES);

    if (!$_POST['phone']) {
        $errmessage[] = "お電話番号を入力してください";
    } else if (mb_strlen($_POST['phone']) > 100) {
        $errmessage[] = "お電話番号は100文字以内にしてください";
    }
    $_SESSION['phone'] = htmlspecialchars($_POST['phone'], ENT_QUOTES);

    if (!$_POST['email']) {
        $errmessage[] = "Eメールを入力してください";
    } else if (mb_strlen($_POST['email']) > 200) {
        $errmessage[] = "Eメールは200文字以内にしてください";
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errmessage[] = "メールアドレスが不正です";
    }
    $_SESSION['email'] = htmlspecialchars($_POST['email'], ENT_QUOTES);

    if (!$_POST['zipcode']) {
        $errmessage[] = "郵便番号を入力してください";
    } else if (mb_strlen($_POST['zipcode']) > 8) {
        $errmessage[] = "郵便番号は7文字以内にしてください";
    }
    $_SESSION['zipcode'] = htmlspecialchars($_POST['zipcode'], ENT_QUOTES);

    if (!$_POST['address']) {
        $errmessage[] = "都道府県を入力してください";
    } else if (mb_strlen($_POST['address']) > 48) {
        $errmessage[] = "都道府県は100文字以内にしてください";
    }
    $_SESSION['address'] = htmlspecialchars($_POST['address'], ENT_QUOTES);

    if (!$_POST['addressLast']) {
        $errmessage[] = "市町村番地を入力してください";
    } else if (mb_strlen($_POST['addressLast']) > 100) {
        $errmessage[] = "市町村番地は100文字以内にしてください";
    }
    $_SESSION['addressLast'] = htmlspecialchars($_POST['addressLast'], ENT_QUOTES);

    $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

    if ($errmessage) {
        $mode = 'input';
    } else {
        //$token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)); // php5のとき
        $token = bin2hex(random_bytes(32)); // php7以降
        $_SESSION['token'] = $token;
        $mode = 'confirm';
    }
} else if (isset($_POST['send']) && $_POST['send']) {
    // 送信ボタンを押したとき
    if (!$_POST['token'] || !$_SESSION['token'] || !$_SESSION['email']) {
        $errmessage[] = '不正な処理が行われました';
        $_SESSION = array();
        $mode = 'input';
    } else if ($_POST['token'] != $_SESSION['token']) {
        $errmessage[] = '不正な処理が行われました';
        $_SESSION = array();
        $mode = 'input';
    } else {
        $message = "お問い合わせ内容: " . $_SESSION['contact'] . "\r\n"
        . "ご希望時間: " . $_SESSION['time'] . "\r\n"
        . "名前: " . $_SESSION['fullname'] . "\r\n"
        . "ふりがな: " . $_SESSION['furigana'] . "\r\n"
        . "お電話番号: " . $_SESSION['phone'] . "\r\n"
        . "email: " . $_SESSION['email'] . "\r\n"
        . "郵便番号: " . $_SESSION['zipcode'] . "\r\n"
        . "都道府県: " . $tdfk[$_SESSION['address']] . "\r\n"
        . "市町村番地: " . $_SESSION['addressLast'] . "\r\n"
        . "その他、ご要望:\r\n"
        . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
        $thanks = 'お問い合わせありがとうございます';
        $to = "next1-market@withmama.net";
        $headers = "From: auto-sender@withmama.net";
        $headers .= "\r\n";
        $headers .= "Cc: reserve@withmama.net";

        if ($_SESSION['contact'] == "見学予約（無料）") {
            mb_send_mail($_SESSION['email'], '【with Mamaの家】: 見学予約（無料）', $thanks);
            mb_send_mail($to, '【with Mamaの家】辰巳台東の見学予約を受付ました', $message, $headers);
        } else {
            mb_send_mail($_SESSION['email'], '【with Mamaの家】: 資料をもらう（無料）', $thanks);
            mb_send_mail($to, '【with Mamaの家】辰巳台東の資料請求を受付ました', $message, $headers);
        }
        $_SESSION = array();
        $mode = 'send';
    }
} else {
    $_SESSION['contact'] = "";
    $_SESSION['time'] = "";
    $_SESSION['fullname'] = "";
    $_SESSION['furigana'] = "";
    $_SESSION['phone'] = "";
    $_SESSION['email'] = "";
    $_SESSION['zipcode'] = "";
    $_SESSION['address'] = "";
    $_SESSION['addressLast'] = "";
    $_SESSION['message'] = "";
}

?>
<!doctype html>
<html lang="ja">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- google fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Archivo:wght@100;200;300;400;500;600;700;800;900&family=Heebo:wght@100;200;300;400;500;600;700;800;900&family=Noto+Serif+JP:wght@200;300;400;500;600;700;900&family=Sawarabi+Gothic&display=swap"
        rel="stylesheet">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <!-- css -->
    <link rel="stylesheet" href="css/style.css?52">
    <link rel="stylesheet" href="css/hamburger.css">

    <title>Withmama</title>
    <!-- fontawesome -->
    <script src="https://kit.fontawesome.com/8053d09693.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body class="font_roboto" style="color: #231815;">
    <!-- ====== SCROLL TO TOP BUTTON ====== -->
    <button class="scrollToTopBtn position-fixed border-0 bg-transparent">
        <i class="fas fa-arrow-circle-up bg-transparent"></i>
    </button>
    <section class="row g-0 position relative">
        <div class="sidebtn">
            <a href="#contact">資料請求</a>
            <a href="#contact">今すぐ見学予約</a>
        </div>
        <div class="d-none d-md-block">
            <div class="d-flex justify-content-between top-ln">
                <div class="text-start">
                    <a href="index.php"><img class="top-logo" src="images/logo.png" alt=""></a>
                </div>
                <div class="text-end">
                    <img class="top-no" src="images/header.png" alt="">
                </div>
            </div>
        </div>
        <div id="carouselExampleDark" class="carousel carousel-dark slide order-1 order-md-0" data-bs-ride="carousel">
            <div class="d-none d-md-block">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                </div>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10000">
                    <picture>
                        <!-- <source class="d-block w-100" media="(max-width: 767.98px)" srcset="images/"> -->
                        <img src="images/top/slide/01.png" class="d-block w-100" alt="...">
                    </picture>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <picture>
                        <!-- <source class="d-block w-100" media="(max-width: 767.98px)" srcset="images/top_slide02.png"> -->
                        <img src="images/top/slide/02.png" class="d-block w-100" alt="...">
                    </picture>
                </div>
                <div class="carousel-item">
                    <picture>
                        <!-- <source class="d-block w-100" media="(max-width: 767.98px)" srcset="images/top_slide03.png"> -->
                        <img src="images/top/slide/03.png" class="d-block w-100" alt="...">
                    </picture>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="container-fluid p-0 m-0 order-0 order-md-1">
            <div class="header w-100">
                <a class="p-2 w-50 d-block d-md-none" href="#"><img class="sp-logo" src="images/logo.png" alt=""></a>
                <input class="menu-btn" type="checkbox" id="menu-btn" />
                <label class="menu-icon ms-auto" for="menu-btn"><span class="navicon"></span></label>
                <ul
                    class="menu container mx-auto d-flex flex-column flex-md-row justify-content-md-between align-items-center m-0 text-start text-md-center">
                    <li><a class="py-4 main-txt a-OTFFolkProB" href="#news">ニュース・イベント</a></li>
                    <li><a class="py-4 main-txt a-OTFFolkProB" href="#concept">コンセプト</a></li>
                    <li><a class="py-4 main-txt a-OTFFolkProB" href="#location">周辺環境</a></li>
                    <li><a class="py-4 main-txt a-OTFFolkProB" href="#kukaku">区画図</a></li>
                    <li><a class="py-4 main-txt a-OTFFolkProB" href="#plan">建築プラン</a></li>
                    <li><a class="py-4 main-txt a-OTFFolkProB" href="#access">アクセス</a></li>
                    <li><a class="py-4 main-txt a-OTFFolkProB" href="#gaiyo">概要</a></li>
                </ul>
            </div>
        </div>
    </section>
    <section class="position-relative" id="news">
        <div class="news_inner ms-auto">
            <p class="title a-OTFFolkProR">ニュース ・ イベント</p>
            <div class="sawarabiGothic new_box">
                <dl>
                    <dt>2023.8.1</dt>
                    <dd class="notoSerifJP">ウィズママヴィレッジ 辰巳台東　全16区画販売開始</dd>
                </dl>
                <dl>
                    <dt>2023.8.1</dt>
                    <dd class="notoSerifJP">ウィズママヴィレッジ 辰巳台東　全16区画販売開始</dd>
                </dl>
                <dl>
                    <dt>2023.8.1</dt>
                    <dd class="notoSerifJP">ウィズママヴィレッジ 辰巳台東　全16区画販売開始</dd>
                </dl>
            </div>
        </div>
    </section>
    <section class="position-relative" id="concept">

        <div class="container">
            <div class="text-center ts-main">
                <img style="max-width: 100%;" src="./images/top/line.jpg" alt="">
                <h5 class="sawarabiGothic mt-3 line_txt"><b>プライバシーポリシーをお読みいただき同意の上、「LINEで更新通知を受け取る」ボタンを押してください。</b>
                </h5>
            </div>
            <div class="ts-main text-center">
                <p class="title-main a-OTFFolkProR">CONCEPT
                    <br><span class="title-main-line"></span>
                </p>
            </div>
            <div class="concept_inner a-OTFFolkProR text-center">
                <div>
                    <p class="concept-sub-comment">利便性と子育て環境の整う街、辰巳台の新しいステージ</p>
                    <p class="concep-txt mt-5">
                        交通の利便性・飲食店などが多い地域でありながら、<br>駅から少し離れると住宅地があり、学校も徒歩圏にあるためファミリーにおすすめの地域です。</p>
                </div>
                <div>
                    <img class="w-100 mt-4 pb-4" src="images/image01.png" alt="">
                    <p class="text-start">程よく田舎で、自然遊びもできるのが魅力です。治安なども良く子育て環境良好。</p>
                </div>
                <div>
                    <img class="w-100 mt-4" src="images/image02.png" alt="">
                </div>
            </div>
        </div>
    </section>

    <!-- <section class="position-relative" id="built-up">
        <div>
            <div class="gray-bg mb-5 ts-main-second text-center">
                <div class="title-build">
                    <p class="title-build-text">建物紹介
                    </p>
                </div>
            </div>
        </div>
        <div class="built-up_inner">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2">
                    <div class="build-box">
                        <div class="build-box-txt">
                            <p class="build-box-title mb-1 fw-bold">with mama</p>
                            <p class="build-box-sub">スマートスタイルプラン</p>
                            <p class="build-box-desc">with mama スマートスタイルの家。
                                <br>それは、広すぎず、狭すぎず、
                                <br class="d-block d-md-none">必要な収納スペースや暮らしやすい
                                <br>アイデアがたっぷり詰まったおうちです。
                                <br>設計士がそれぞれ土地に
                                <br class="d-block d-md-none">最適な間取りプランがいいのかを監修。
                                <br>安心してお選びくださいませ。
                                <br class="d-block d-md-none">全区画ご用意しております！
                            </p>
                        </div>
                    </div>
                    <div class="pt-3 pt-md-0">
                        <img class="w-100" src="images/image03.png" alt="スマートスタイルプラン">
                    </div>
                </div>
            </div>
            <div class="container py-3 py-md-5">
                <div class="row row-cols-1 row-cols-lg-2 py-md-5 align-items-center">
                    <div class="text-center">
                        <img class="madori-img" src="images/madori01.png" alt="間取り図">
                    </div>
                    <div class="madori-text-box">
                        <p class="madori-text">区画：　<span class="main-txt">No.1</span>
                            <br>状況：　ご提案可能
                            <br>坪数：　26.05坪
                        </p>
                        <p class="madori-cost d-flex align-items-end"><span class="madori-plan">セットプラン価格</span>
                            <span
                                class="madori-cost-number m-0 sawarabiGothic main-txt mx-2">2,780</span><span>万円〈税込〉</span>
                        </p>
                        <p class="month-cost"><span class="main-txt">月々参考返済例</span> <span
                                class="madori-cost-number m-0 sawarabiGothic main-txt mx-2">72,780</span><span
                                class="yen">円</span></p>
                        <p class="madori-text-2">（土地費用、建物本体価格、建物諸費用の合計金額）
                            <br>※除くものは、外構・諸費用
                            <br>・金利0.55％、期間35年、ボーナスなし、
                            <br class="d-block d-md-none">頭金含まずで住宅ローンは計算になります。
                        </p>
                        <ul class="madori-m">
                            <li>敷地面積 186.2㎡（56.3坪）</li>
                            <li>建物面積 86.1㎡（26.05坪）</li>
                        </ul>
                        <p><span class="ldk sawarabiGothic"><span class="ldk-no">3</span>LDK</span><span
                                class="oboi archivo">＋ウォークインクローゼット</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="gallery position-relative mb-5">
                <p class="text-center gallery-title">GALLERY</p>
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img class="w-100" src="images/slider/gallery-01.png" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img class="w-100" src="images/slider/gallery-02.png" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img class="w-100" src="images/slider/gallery-03.png" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img class="w-100" src="images/slider/gallery-04.png" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img class="w-100" src="images/slider/gallery-05.png" alt="">
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section> -->
    <section class="position-relative mt-5 pt-5" id="location">
        <div class="container">
            <div class="text-center">
                <p class="title-main a-OTFFolkProR">周辺環境
                    <br><span class="title-main-line"></span>
                </p>
                <p class="a-OTFFolkProB pb-5 fs_loc"><strong>こんなところがやさしいまち。</strong></p>
            </div>
        </div>
        <div class="container row row-cols-1 row-cols-md-3 g-3 g-5 mx-auto">
            <div>
                <img class="w-100" src="./images/top/hosp.png" alt="">
            </div>
            <div>
                <img class="w-100" src="./images/top/sch.png" alt="">
            </div>
            <div>
                <img class="w-100" src="./images/top/sh.png" alt="">
            </div>
        </div>
        <div class="container py-5">
            <img class="w-100" src="images/top/area.png" alt="">
        </div>

        <div class="text-center a-OTFFolkProR pt-5">
            <div class="mx-auto">
                <div class="row row-cols-1 row-cols-lg-3 container mx-auto g-3 g-md-5">
                    <div class="loc-banner-box">
                        <div class="position-relative">
                            <img class="w-100" src="images/area01.png" alt="辰巳台東小学校">
                            <span class="loc-time">
                                <span class="loc-w">徒歩</span>
                                <br><span class="loc-wmin sawarabiGothic txt_blue">10</span>
                                <span class="loc-t">分</span>
                            </span>
                        </div>
                        <p class="text-start ps-4 pt-3 bg_blue loc-banner-text">辰巳台東小学校</p>
                    </div>
                    <div class="loc-banner-box">
                        <div class="position-relative">
                            <img class="w-100" src="images/area02.png?1" alt="辰巳台中学校">
                            <span class="loc-time">
                                <span class="loc-w">徒歩</span>
                                <br><span class="loc-wmin sawarabiGothic txt_blue">22</span>
                                <span class="loc-t">分</span>
                            </span>
                        </div>
                        <p class="text-start ps-4 pt-3 bg_blue loc-banner-text">辰巳台中学校</p>
                    </div>
                    <div class="loc-banner-box">
                        <div class="position-relative">
                            <img class="w-100" src="images/area03.png" alt="光の子幼稚園">
                            <span class="loc-time">
                                <span class="loc-w">徒歩</span>
                                <br><span class="loc-wmin sawarabiGothic txt_blue">14</span>
                                <span class="loc-t">分</span>
                            </span>
                        </div>
                        <p class="text-start ps-4 pt-3 bg_blue loc-banner-text">光の子幼稚園</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center a-OTFFolkProR pt-5">
            <div class="mx-auto">
                <div class="row row-cols-1 row-cols-lg-3 justify-content-center container mx-auto g-3 g-md-5">
                    <div class="loc-banner-box">
                        <div class="position-relative">
                            <img class="w-100" src="images/area04.png" alt="マックスバリュ">
                            <span class="loc-time">
                                <span class="loc-w">徒歩</span>
                                <br><span class="loc-wmin sawarabiGothic txt_orange">17</span>
                                <span class="loc-t">分</span>
                            </span>
                        </div>
                        <p class="text-start ps-4 pt-3 bg_orange loc-banner-text">マックスバリュ</p>
                    </div>
                    <div class="loc-banner-box">
                        <div class="position-relative">
                            <img class="w-100" src="images/area05.png" alt="ケーヨーデイツー">
                            <span class="loc-time">
                                <span class="loc-w">徒歩</span>
                                <br><span class="loc-wmin sawarabiGothic txt_orange">14</span>
                                <span class="loc-t">分</span>
                            </span>
                        </div>
                        <p class="text-start ps-4 pt-3 bg_orange loc-banner-text">ケーヨーデイツー</p>
                    </div>
                    <div class="loc-banner-box">
                        <div class="position-relative">
                            <img class="w-100" src="images/area06.png" alt="ダイソー辰巳台店">
                            <span class="loc-time">
                                <span class="loc-w">徒歩</span>
                                <br><span class="loc-wmin sawarabiGothic txt_orange">15</span>
                                <span class="loc-t">分</span>
                            </span>
                        </div>
                        <p class="text-start ps-4 pt-3 bg_orange loc-banner-text">ダイソー辰巳台店</p>
                    </div>
                    <div class="loc-banner-box">
                        <div class="position-relative">
                            <img class="w-100" src="images/area10.png" alt="ダイソー辰巳台店">
                            <span class="loc-time">
                                <span class="loc-w">車で</span>
                                <br><span class="loc-wmin sawarabiGothic txt_orange">9</span>
                                <span class="loc-t">分</span>
                            </span>
                        </div>
                        <p class="text-start ps-4 pt-3 bg_orange loc-banner-text">ユニモちはら台</p>
                    </div>
                    <div class="loc-banner-box">
                        <div class="position-relative">
                            <img class="w-100" src="images/area09.png" alt="ダイソー辰巳台店">
                            <span class="loc-time">
                                <span class="loc-w">徒歩</span>
                                <br><span class="loc-wmin sawarabiGothic txt_orange">15</span>
                                <span class="loc-t">分</span>
                            </span>
                        </div>
                        <p class="text-start ps-4 pt-3 bg_orange loc-banner-text">マツモトキヨシ</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center a-OTFFolkProR py-5">
            <div class="mx-auto">
                <div class="row row-cols-1 row-cols-lg-3 justify-content-center container mx-auto g-3 g-md-5">
                    <div class="loc-banner-box">
                        <div class="position-relative">
                            <img class="w-100" src="images/area07.png" alt="千葉労災病院">
                            <span class="loc-time">
                                <span class="loc-w">徒歩</span>
                                <br><span class="loc-wmin sawarabiGothic txt_pink">23</span>
                                <span class="loc-t">分</span>
                            </span>
                        </div>
                        <p class="text-start ps-4 pt-3 bg_pink loc-banner-text">千葉労災病院</p>
                    </div>
                    <div class="loc-banner-box">
                        <div class="position-relative">
                            <img class="w-100" src="images/area08.png" alt="辰巳病院">
                            <span class="loc-time">
                                <span class="loc-w">徒歩</span>
                                <br><span class="loc-wmin sawarabiGothic txt_pink">2</span>
                                <span class="loc-t">分</span>
                            </span>
                        </div>
                        <p class="text-start ps-4 pt-3 bg_pink loc-banner-text">辰巳病院</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container p-md-5">
        <a class="text-decoration-none text-dark" href="#contact">
            <img class="w-100 px-md-5" src="images/top/line.jpg" alt="LINE">
            <h5 class="sawarabiGothic mt-3 mb-0 text-center line_txt2">
                <b>プライバシーポリシーをお読みいただき同意の上、「LINEで更新通知を受け取る」ボタンを押してください。</b>
            </h5>
        </a>
    </section>
    <!-- <section class="position-relative mb-5" id="access">
        <div class="container">
            <div class="text-center">
                <p class="title-main a-OTFFolkProR">アクセス
                    <br><span class="title-main-line"></span>
                </p>
            </div>
        </div>
        <div class="map container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12990.32271668077!2d140.14321305693335!3d35.51464542636212!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x602299b1c0c35f2d%3A0x3b073e8d2aead400!2z44CSMjkwLTAwMDMg5Y2D6JGJ55yM5biC5Y6f5biC6L6w5bez5Y-w5p2x!5e0!3m2!1sja!2sjp!4v1689209150627!5m2!1sja!2sjp"
                style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section> -->
    <section>
        <div class="container" id="kukaku">
            <div class="ts-main-second text-center">
                <p class="title-main a-OTFFolkProR">区画図
                    <br><span class="title-main-line"></span>
                </p>
            </div>
            <div class="kukaku_inner">
                <img class="mb-5 pb-md-5" src="images/kukaku01.png" alt="">
            </div>
        </div>
    </section>
    <section class="position-relative pt-5" id="gaiyo">
        <div class="container bs-main">
            <div class="text-center">
                <p class="title-main a-OTFFolkProR">概要
                    <br><span class="title-main-line"></span>
                </p>
            </div>
            <div class="row row-cols-2 a-OTFFolkProR">
                <div class="table-bukken_inner">
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">所在地</div>
                        <div class="col-8">千葉県市原市辰巳台東</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">交　通</div>
                        <div class="col-8">R内房線「八幡宿」駅車で13分</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">販売価格</div>
                        <div class="col-8">980万円〜1650万円</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">最多価格</div>
                        <div class="col-8">1280万円・1390万円</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">販売区画数</div>
                        <div class="col-8">16区画</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">土地面積</div>
                        <div class="col-8">153.00㎡（46.28坪）〜184.05㎡（55.68坪）</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">地　目</div>
                        <div class="col-8">雑種地</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">土地権利</div>
                        <div class="col-8">所有権</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">都市計画</div>
                        <div class="col-8">市街化区域</div>
                    </div>
                </div>

                <div class="table-bukken_inner">
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">用途地域</div>
                        <div class="col-8">第一種中高層住居専用地域</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">建ぺい率</div>
                        <div class="col-8">60%</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">容積率</div>
                        <div class="col-8">200%</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">接道状況</div>
                        <div class="col-8">北側・西側・南側</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">設　備</div>
                        <div class="col-8">公営水道・プロパンガス・浄化槽</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">現　状</div>
                        <div class="col-8">更地</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">引渡時期</div>
                        <div class="col-8">相談</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">取引形態</div>
                        <div class="col-8">売主</div>
                    </div>
                    <div class="table-item row py-3">
                        <div class="col-4 text-center">その他</div>
                        <div class="col-8">建築条件付き
                        </div>
                    </div>
                    <!-- <div class="table-item row py-3">
                        <div class="col-4 text-center">広告公開予告</div>
                        <div class="col-8">本広告を行い取引を開始するまでの間は、契約または予約の申し込み、
                            及び申し込みの順位の確保は一切できません。予めご了承ください。 販売開始予定／令和5年9月</div>
                    </div> -->
                </div>
            </div>
    </section>
    <section class="gray-bg ts-main-second bs-main" id="contact">
        <div class="container contact-w mx-auto">

            <div>
                <p class="text-center contact-title a-OTFFolkProR">資料請求／見学予約フォーム</p>
            </div>
            <div class="pt-3 pb-5">
                <img class="w-100" src="images/top/line.jpg" alt="line">
                <p class="sawarabiGothic mt-3 text-center line_txt2">
                    <b>プライバシーポリシーをお読みいただき同意の上、「LINEで更新通知を受け取る」ボタンを押してください。</b>
            </div>
            <!--div>
                <p class="text-danger">※予約受付時間 午前10時から17時までです。</p>
            </div-->
            <div class="container">
                <?php if ($mode == 'input'): ?>
                <!-- 入力画面 -->
                <?php if ($errmessage): ?>
                <div class="alert alert-danger" role="alert">
                    <?=print implode('<br>', $errmessage)?>
                </div>
                <?php endif;?>
            </div>
            <form action="index.php#contact" method="post">
                <table class="table table-bordered bg-white border-dark a-OTFFolkProR">
                    <tbody>
                        <tr id="timesh" class="">
                            <th class="text-center py-3" scope="row">予約店舗</th>
                            <td class="py-3 lh-lg">withmama 市原店
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center py-3" scope="row">お問い合わせ内容</th>
                            <td class="py-3">
                                <div class="d-flex flex-column flex-md-row lh-lg">
                                    <div class="me-3">
                                        <input type="radio" id="show" name="contact" value="見学予約（無料）" checked />
                                        <label for=" show">見学予約（無料）</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="hide" name="contact" value="資料をもらう（無料）" />
                                        <label for="hide">資料をもらう（無料）</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr id="timesh2" class="">
                            <th class="text-center py-3" scope="row">ご希望時間<span class="text-danger">*</span></th>
                            <td class="py-3"><input name="time" class="text-dark"
                                    value="<?php echo $_SESSION['time'] ?>" type="datetime-local"></td>
                        </tr>
                        <tr>
                            <th class="text-center py-3" scope="row">お名前<span class="text-danger">*</span></th>
                            <td class="py-3"><input name="fullname" value="<?php echo $_SESSION['fullname'] ?>"
                                    class="w-100" type="text"></td>
                        </tr>
                        <tr>
                            <th class="text-center py-3" scope="row">ふりがな<span class="text-danger">*</span></th>
                            <td class="py-3"><input name="furigana" value="<?php echo $_SESSION['furigana'] ?>"
                                    class="w-100" type="text"></td>
                        </tr>
                        <tr>
                            <th class="text-center py-3" scope="row">お電話番号<span class="text-danger">*</span></th>
                            <td class="py-3"><input name="phone" value="<?php echo $_SESSION['phone'] ?>" class="w-100"
                                    type="text"></td>
                        </tr>
                        <tr>
                            <th class="text-center py-3" scope="row">メールアドレス<span class="text-danger">*</span></th>
                            <td class="py-3"><input name="email" value="<?php echo $_SESSION['email'] ?>" class="w-100"
                                    type="text"></td>
                        </tr>
                        <tr>
                            <th class="text-center py-3" scope="row">郵便番号<span class="text-danger">*</span></th>
                            <td class="py-3">
                                <input name="zipcode" class="w-100" type="text"
                                    value="<?php echo $_SESSION['zipcode'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center py-3" scope="row">都道府県<span class="text-danger">*</span></th>
                            <td class="py-3">
                                <select name="address" class="text-dark">
                                    <?php foreach ($tdfk as $i => $v): ?>
                                    <?php if ($_SESSION['address'] == $i): ?>
                                    <option value="<?php echo $i ?>" selected><?php echo $v ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $i ?>"><?php echo $v ?></option>
                                    <?php endif?>
                                    <?php endforeach;?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center py-3" scope="row">市町村番地<span class="text-danger">*</span></th>
                            <td class="py-3"><input name="addressLast" value="<?php echo $_SESSION['addressLast'] ?>"
                                    class="w-100" type="text"></td>
                        </tr>
                        <tr>
                            <th class="text-center py-3" scope="row">その他、<br class="d-block d-md-none">ご要望など
                                <br>ご自由に<br class="d-block d-md-none">ご記入ください
                            </th>
                            <td class="py-3">
                                <textarea class="w-100" name="message"
                                    rows="5"><?php echo $_SESSION['message'] ?></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center pt-4">
                    <input name="confirm" class="submit py-3 text-dark sawarabiGothic" type="submit"
                        value="注意事項に同意して送信">
                </div>
            </form>
            <?php elseif ($mode == 'confirm'): ?>
            <!-- 確認画面 -->
            <form action="index.php#contact" method="post">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                <div class="row g-3 p-3 bg-white rounded">
                    <div class="col-4">お問い合わせ内容</div>
                    <div class="col-8"><?php echo $_SESSION['contact'] ?></div>
                    <?php if (!empty($_SESSION['time'])): ?>
                    <div class="col-4">ご希望時間</div>
                    <div class="col-8"><?php echo $_SESSION['time'] ?></div>
                    <?php endif;?>
                    <div class="col-4">名前</div>
                    <div class="col-8"><?php echo $_SESSION['fullname'] ?></div>
                    <div class="col-4">ふりがな</div>
                    <div class="col-8"><?php echo $_SESSION['furigana'] ?></div>
                    <div class="col-4">名前</div>
                    <div class="col-8"><?php echo $_SESSION['phone'] ?></div>
                    <div class="col-4">Eメール</div>
                    <div class="col-8"><?php echo $_SESSION['email'] ?></div>
                    <div class="col-4">郵便番号</div>
                    <div class="col-8"><?php echo $_SESSION['zipcode'] ?></div>
                    <div class="col-4">都道府県</div>
                    <div class="col-8"><?php echo $tdfk[$_SESSION['address']] ?></div>
                    <div class="col-4">市町村番地</div>
                    <div class="col-8"><?php echo $_SESSION['addressLast'] ?></div>
                    <div class="col-12">
                        <div>
                            その他、ご要望などご自由にご記入ください
                        </div>
                        <div>
                            <?php echo nl2br($_SESSION['message']) ?>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <input type="submit" name="back" value="戻る" class="btn btn-secondary btn-md px-5 me-3" />
                        <input type="submit" name="send" value="送信" class="btn btn-secondary btn-md px-5" />
                    </div>
                </div>
            </form>
            <?php else: ?>
            <!-- 完了画面 -->
            <div class="text-center py-5 bg-white rounded">
                <h4 class="main-txt lh-lg">
                    送信しました。
                    <br>お問い合わせ
                    <br class="d-block d-md-none">ありがとうございました。
                </h4>
            </div>
            <?php endif;?>
            <div class="container mt-4">
                <ul class="warning notoSerifJP">
                    <p class="fw-bold a-OTFFolkProR text-center">ご注意</p>
                    <p>
                        <br>当日のご来場予約についてはお電話いただきますようご協力をお願いいたします。
                        <br>
                        <br>■携帯メールアドレスのドメイン指定受信に関するお願い
                        <br>携帯メールのドメイン指定受信や、指定拒否をしている場合、
                        <br>当サイトからの予約完了通知などを受信できない場合があります。
                        <br>当社からのメールは【withmama.net】ドメインで配信しております。
                        <br>該当のドメインからのメールを受信いただけるよう設定願います。
                    </p>
                </ul>
            </div>
        </div>
    </section>
    <section class="main-bg">
        <div class="footer">
            <img class="w-100" src="images/footer.png" alt="with mama 市原店　フリーダイヤル0120-360-599">
        </div>
        <div class="text-center copy-right text-white py-2">
            © with Mama's house.
        </div>
    </section>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    <script src="js/script.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <!-- Initialize Swiper -->
    <script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 2,
        loop: true,
        freeMode: true,
        autoplay: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            767.98: {
                slidesPerView: 4,
            },
        },
    });
    </script>
</body>

</html>