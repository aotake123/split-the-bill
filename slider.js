var main_slider = (function(){
    var currentItemNum = 1;
    var $slideContainer = $('.slider__container');
    var sliderItemNum = $('.slider__item').length;
    var slideItemWidth = $('.slider__item').innerWidth();
    var slideContainerWidth = slideItemWidth * sliderItemNum;
    var DURATION = 500;

    return {
        slidePrev: function(){
            if(currentItemNum > 1){
                $slideContainer.animate({left: '+='+slideItemWidth+'px'}, DURATION);
                    currentItemNum--;
            }
        },
        slideNext: function(){
            if(currentItemNum < sliderItemNum){
                $slideContainer.animate({left: '-='+slideItemWidth+'px'}, DURATION);
                    currentItemNum++;
            }        
        },
        init: function(){
            $slideContainer.attr('style', 'width:' + slideContainerWidth + 'px');
            var that = this;
            $('.js-slide-next').on('click', function(){
                that.slideNext();
            });
            $('.js-slide-prev').on('click', function(){
                that.slidePrev();
            });
        }
//        slideCountUp: function(){
//            setTimeout(this.slideNext(), 25000);
//      }
    }
}());

main_slider.init();
