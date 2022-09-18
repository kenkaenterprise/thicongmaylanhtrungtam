// Code GaConIT:Nguyễn Ngọc Tân
// Email yahoo:nguyentanit_91@yahoo.com OR Email Gmail:tanit91.nina@gmail.com




/*******************************************START BACK-TOP *********************************/

 $(document).ready(function() {
           
          $(window).scroll(function() {
            if($(window).scrollTop() != 0) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').fadeOut();
            }
          });
          $('#back-top').click(function() {
            $('html, body').animate({scrollTop:0},500);
          });
        });


/*******************************************END BACK-TOP *********************************/


