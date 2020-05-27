@extends('layouts.master')

@section('title', 'Giới Thiệu')

@section('content')

  <section class="bread-crumb">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home_page') }}">{{ __('header.Home') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Giới Thiệu</li>
      </ol>
    </nav>
  </section>

  <div class="site-about">
    <section class="section-advertise">
      <div class="content-advertise">
        <div id="slide-advertise" class="owl-carousel">
          @foreach($advertises as $advertise)
            <div class="slide-advertise-inner" style="background-image: url('{{ Helper::get_image_advertise_url($advertise->image) }}');" data-dot="<button>{{ $advertise->title }}</button>"></div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="section-about">
      <div class="section-header">
        <h2 class="section-title">Giới Thiệu</h2>
      </div>
      <div class="section-content">
        <div class="row">
          <div class="col-md-9 col-sm-8">
            <div class="content-left">
              <div class="note">
                <div class="note-icon"><i class="fas fa-info-circle"></i></div>
                <div class="note-content">
                  <p>website <strong>LaptopStore</strong> là một website. Mọi hoạt động mua sắm trên website đều không có giá trị !</p>
                </div>
              </div>
              <div class="content">
                <p>- Nhà em có nuôi một con chó. Nó đen như cục than. Bụng nó bự như cục thịt heo bán ở ngoài chợ. Mắt nó như hai viên hột xoàn. Bốn chân nó như cái cột đình.
                <p>- Mẹ em đang nuôi một con lợn rất béo, bụng nó to như bụng ông bán phở đầu ngõ.</p>
                <p>- Nhà em có nuôi một con lợn con. Mình nó to như cái cánh cửa, tai như cái cánh quạt điện. Em rất yêu quý con lợn nhà em. Rồi một hôm mấy bác hàng giáp đến, con lợn không còn nữa, nó đi để lại cho em một đĩa lòng.</p>
                <p>- Gia đình em có một con gà, hai mắt nó tròn như hai nắp chai. Mào nó đỏ như màu đỏ. Mỗi buổi sáng nó thường hay gáy Ò... Ó ó ó... O... Ò ò ò ò... đánh thức em nhưng nhà em cứ đóng kín cửa cho nên không nghe được tiếng gà kêu, vì thế em luôn đi học trễ.</p>
                <p>- Nhà em có nuôi một con gà trống trông rất hùng dũng. Đầu nó tròn như trái ổi, cái mỏ nhọn như ngòi bút mực và cái mào đỏ nhấp nhô như sóng biển. Em rất yêu quý chú gà trống vì hàng ngày nó đẻ trứng cho ba mẹ và em ăn để tăng cường sức khỏe.</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-4">
            <div class="content-right">
              <div class="online_support">
                <h2 class="title">CHÚNG TÔI LUÔN SẴN SÀNG<br>ĐỂ GIÚP ĐỠ BẠN</h2>
                <img src="{{ asset('images/support_online.jpg') }}">
                <h3 class="sub_title">Để được hỗ trợ tốt nhất. Hãy gọi</h3>
                <div class="phone">
                  <a href="tel:18006750" title="1800 6750">1800 6750</a>
                </div>
                <div class="or"><span>HOẶC</span></div>
                <h3 class="title">Chat hỗ trợ trực tuyến</h3>
                <h3 class="sub_title">Chúng tôi luôn trực tuyến 24/7.</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

@endsection

@section('css')
  <style>
    .slide-advertise-inner {
      background-repeat: no-repeat;
      background-size: cover;
      padding-top: 21.25%;
    }
    #slide-advertise.owl-carousel .owl-item.active {
      -webkit-animation-name: zoomIn;
      animation-name: zoomIn;
      -webkit-animation-duration: .6s;
      animation-duration: .6s;
    }
  </style>
@endsection

@section('js')
  <script>
    $(document).ready(function(){

      $("#slide-advertise").owlCarousel({
        items: 2,
        autoplay: true,
        loop: true,
        margin: 10,
        autoplayHoverPause: true,
        nav: true,
        dots: false,
        responsive:{
          0:{
            items: 1,
          },
          992:{
            items: 2,
            animateOut: 'zoomInRight',
            animateIn: 'zoomOutLeft',
          }
        },
        navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>']
      });
    });
  </script>
@endsection
