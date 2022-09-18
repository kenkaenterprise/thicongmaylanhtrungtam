var combomain,slidec2,clipmain,tinnbmain;
function hover_menu_left() {
  var firstTime = true;
  var top = -1;

  var timer;
  var delay = 100;

  $('.col_menu_cap1').menuAim({
      rowSelector: ".sub_item_menu",
      submenuDirection: "right",
      activate: function (a) {
          if (firstTime) {
              $(a).addClass('active').children('.conten_hover_submenu').css({display: 'block'});
          } else {
              $(a).addClass('active').children('.conten_hover_submenu').show();
          }
      },
      deactivate: function (a) {
          $(a).removeClass('active').children('.conten_hover_submenu').hide();
          //top = -1;
      },
      exitMenu: function () {
          firstTime = true;
          $('.conten_hover_submenu').hide();
          $('.sub_item_menu').removeClass('active');
          // top = -1;
          return true;
      }
  });
}
function masonry(){
	$('.masoryz').masonry({
		  itemSelector: '.col-me1',
		  horizontalOrder: true
	})
} 

$(document).ready(function() {
  $('.giohang-cl').click(function(event) {
		$('#giohang').removeClass('active');
	});
  $('.ttmh').click(function(event) {
		$('#giohang').removeClass('active');
	});
  $('.box-sp-cart').click(function(event) {
        
        /* Xu ly gio hang*/
        var id=$(this).attr('data-id');
        console.log(id);
        var sl=1;
        $.ajax({
            url: 'ajax/giohang.php',
            type: 'POST',
            dataType: 'json',
            data: {id: id,sl:sl},
            success:function(res){
                $('.ajax_cart').html('Thêm vào giỏ hàng');
                $('.giohang-left-cont').html(res.thongtin);
                $('.giohang-left-tit span').html(res.isoluong);
                $('.banner-ab-gh span,.giohang-right-tit span').html(res.soluong);
                $('.giohang-thanhtien span, .giohang-right-tt .ghajax, .giohang-right-total .ghajax').html(res.tongtien);
                $('#giohang').addClass('active');
            }
        })
    });

	$('.ajax_cart').click(function(event) {

        /* Xu ly gio hang*/
        var id=$(this).attr('data-id');
        var sl=$('#qty').val();
        $.ajax({
            url: 'ajax/giohang.php',
            type: 'POST',
            dataType: 'json',
            data: {id: id,sl:sl},
            beforeSend:function(){
                $('.ajax_cart').html('Vui lòng đợi');
            },
            success:function(res){
                $('.ajax_cart').html('Thêm vào giỏ hàng');
                $('.giohang-left-cont').html(res.thongtin);
                $('.giohang-left-tit span').html(res.isoluong);
                $('.banner-ab-gh span,.giohang-right-tit span').html(res.soluong);
                $('.giohang-thanhtien span, .giohang-right-tt .ghajax, .giohang-right-total .ghajax').html(res.tongtien);
                $('#giohang').addClass('active');
            }
        })
    });
  // hover_menu_left();
  $('.chonsosanh').click(function(){
    var id=$(this).attr('data-id');
    var type='add';
    if($(this).hasClass('active')){
       $(this).removeClass('active');
       type='del';
    }else{
       $(this).addClass('active');
       
    }
    console.log(type);
        $.ajax({
            url: 'ajax/sosanh.php',
            type: 'POST',
            dataType: 'json',
            data: {id: id,type: type},
            beforeSend:function(){
                
            },
            success:function(res){
               
                if(res.kq > 0) {    
                  $.notify({
                   title: '<strong>Thông báo</strong>',
                   message: '<div class="abc">'+res.msg+'</div>',
                   url: 'so-sanh.html',
                 },{
                     type: 'success',
                     timer: 100,
                     delay: 2000,
                     newest_on_top: true,
                 });  
                     
                }else{
                   $.notify({
                   title: '<strong>Thông báo</strong>',
                   message: '<div class="abc">'+res.msg+'</div>',
                   url: 'so-sanh.html',
                 },{
                     type: 'danger',
                     timer: 100,
                     delay: 2000,
                     newest_on_top: true,
                 }); 
                   
                   
                }
            }
        })
    });
  
 setTimeout(function(){
		 masonry();
	},1000)
    $('.web-slider-main').slick({
      dots: false,
      autoplay:true,
      arrows:false,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      slidesToScroll: 1,
    });
   
    $('.hinhkhac-main').slick({
      dots: false,
      autoplay:true,
      arrows:false,
      infinite: true,
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 2,
          }
        },{
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    });       
    $('.tinnb-main').slick({
     
      infinite: true,
      vertical: true,
      slidesToShow: 3,
      slidesToScroll: 1,
      autoplay: false,
      arrows: false,
      dots: false,
      autoplaySpeed: 5000,
    });
    
    $('.dichvunb-main').slick({
     dots: false,
      autoplay:false,
      arrows:false,
      infinite: true,
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 375,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    });
    $('.spnoibat-main').slick({
     dots: false,
      autoplay:false,
      arrows:true,
      infinite: true,
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    });
    
    // tinmain.slick({
      // infinite: true,
      // vertical: true,
      // slidesToShow: 3,
      // slidesToScroll: 1,
      // autoplay: true,
      // dots: false,
      // arrows: false,
      // autoplaySpeed: 5000,
      // 'autoplaySpeed': 1,
      // 'speed':5000,
      // 'cssEase':'linear',
    // });
    // hinhanhmain.slick({
      // infinite: true,
      // vertical: true,
      // slidesToShow: 4,
      // slidesToScroll: 1,
      // autoplay: true,
      // dots: false,
      // arrows: false,
      // autoplaySpeed: 5000,
      // responsive: [
        // {
          // breakpoint: 800,
          // settings: {
            // slidesToShow: 4,
          // }
        // },{
          // breakpoint: 480,
          // settings: {
            // vertical: false,
          // }
        // }
      // ]
    // });
  
   
  
 $('.thuvien').click(function(e){
    var id=$(this).data('id');
    var name=$(this).data('name');
    var dt;
    $.get("gallery_load.php?id="+id+"&name="+name, function (data) {
        dt = data;
        $.fancybox.open(JSON.parse(dt));
        // console.log(JSON.parse(dt));
        // console.log(dt);
    });
    
    
    return false;
  });
  

$('#lstvideo').change(function(){
  $("#iframe").attr("src","https://www.youtube.com/embed/"+$(this).val()+"?autoplay=1");
});
  $('.video-khac a').click(function(e){
    $("#iframe").attr("src","https://www.youtube.com/embed/"+$(this).data("val")+"?autoplay=1");
    e.preventDefault();
  });
   $('.video-khac-main').slick({
      dots: false,
      autoplay:true,
      arrows:false,
      infinite: true,
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 1,
      // responsive: [
        // {
          // breakpoint: 800,
          // settings: {
            // slidesToShow: 4,
          // }
        // },{
          // breakpoint: 480,
          // settings: {
            // slidesToShow: 4,
          // }
        // }
      // ]
    }); 
    $('#registerMail').click(function() {
      var el = $('#txtMail');
      var elname = $('#txtName');
      var elphone = $('#txtPhone');
      var elcomment = $('#txtcomment');
      var emailRegExp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]){2,4})$/;
      if (el.val() == '') {
        el.focus();
        alert('Xin vui lòng nhập địa chỉ email của bạn');
        return false;
      }
      if (!emailRegExp.test(el.val())) {
        el.focus();
        alert('Email không đúng');
      } else {
        $.ajax({
          type: 'POST',
          url: 'ajax/dangky_email.php',
          data: {'email' : el.val(),'ten' : elname.val(),'phone' : elphone.val(),'comment' : elcomment.val()},
          success: function(result) {
            if(result==1) alert("Email này đã được đăng ký");
            else if(result==0) alert("Chúc mừng bạn đã đăng ký thành công"); 
            else alert("Xảy ra lỗi đăng ký"); 
          }
        });
      }
      return false;
    });
    
    $('#lstvideo').change(function(){
      $("#iframe").attr("src","https://www.youtube.com/embed/"+$(this).val()+"?autoplay=1");
    });
  // $(".ngoisao").each(function(){
        
        // $(this).raty({score:$(this).data("score"),path: 'js/raty/images', click: function(score, evt) {
          // $xid = $(this).data("id");
          // $.post("ajax/rate.php",{id:$xid,score:score},function(data){
            // data = parseInt(data);
            // if(!data){
                // alert("Bạn đã bình luận sản phẩm này");
                // $(this).raty('cancel', true); 
            // }
       
            
          // })
    // }});

    // });
  // initOpenFormMember();
  $(".various,.zoombtn").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: true,
		width		: '90%',
		height		: '90%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		wrapCSS	: 'myfan',
		closeEffect	: 'none'
	});
  $('.disable-link').click(function(e){
    e.preventDefault();
  })
  function smoothScrolling() { /*-------------------------------------------------*/
  /* =  smooth scroll in chrome
    /*-------------------------------------------------*/
    try {
      $.browserSelector();
      // Adds window smooth scroll on chrome.
      if ($("html").hasClass("chrome")) {
        $.smoothScroll();
      }
    } catch (err) {

    }

  }
  $("#scroller").simplyScroll({orientation:'vertical',customClass:'vert',auto:'false'});
  smoothScrolling() ;
});
