<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding regiestverify-left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">輸入驗證碼</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="regiestverify">

            {{ Form::open(array('url' => $regiest.'/verify/'.$parameter, 'method' => 'post', 'class' => 'verify-form')) }}
            {{-- {{ Form::open(array('url' => '/verify/'.$parameter, 'method' => 'post', 'class' => 'verify-form')) }} --}}
            <!-- 內容 -->
            <div class="page-content registered-content animated fadeIn">
                <div class="font_style">請輸入驗證碼</div>
                <div class="mobile-check">
                    <div class="content-block">

                        <div class="verifycode-block">
                            <input class="verifycode" name="verifycode" type="number" placeholder="" onkeydown="input_limit(this, 6);" onkeyup="input_limit(this, 6);">
                        </div>
                        <a href="#" class="button button-big confirm">確認</a>

                        <a href="#" class="button button-big re-send">重新發送</a>

                        <div class="shortly"></div>

                        <a href="#" class="button button-big not-send">重新發送</a>


                    </div>
                </div>

            </div>

            {{ Form::close() }}

        </div>
    </div>
</div>
