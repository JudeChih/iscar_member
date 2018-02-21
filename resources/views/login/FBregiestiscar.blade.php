<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding FBregiestiscar-left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">FB會員註冊</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="FBregiestiscar">

            {{-- {{ Form::open(array('url' => 'FBregiestiscar/'.$parameter, 'method' => 'post', 'class' => 'FBregiestiscar-form')) }} --}}

            <!-- 內容 -->
            <div class="page-content FBregiestiscar-content animated fadeIn">

                <div class="list-block">
                    <ul>
                        <li class="align-top account-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="account" class="account" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top nickname-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-user" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="nickname" class="nickname" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top countrycode-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-globe" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input class="countrycode-picker" type="text" placeholder="地區" readonly>
                                        <input name="countrycode" class="countrycode" type="hidden" placeholder="地區" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top cellphone-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-mobile" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="cellphone" class="cellphone" type="number" placeholder="請先選擇地區" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="noUse">

                        </li>
                    </ul>
                </div>

            </div>

            <div class="toolbar toolbar-bottom registered animated fadeInUp">
                <div class="toolbar-inner">
                    <a>註冊</a>
                </div>
            </div>

            {{-- {{ Form::close() }} --}}

        </div>
    </div>
</div>
