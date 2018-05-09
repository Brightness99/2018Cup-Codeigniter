$(function() {
	// function proverka(input) {
	//  input.innerHTML = input.innerHTML.replace(/[^\d,]/g, '');
	// };
	if ($('.topHint>p').length) {
		$('.topHint>p').animate({
    scrollTop: $('.topHint>p').get(0).scrollHeight
}, 10000);
}
	
	$('.roundred').on("keypress" , function(e){
		if (e.keyCode < 48 || e.keyCode > 57) {
		 return false;
		}
		$(this).text("");
	});
	/*$(".score-small p span").on("focus" , function(){
		$(this).text("");
	});*/
	$(".score-small p span").on("keypress" ,function(e){
		f = navigator.userAgent.search("Firefox");
		if (f) {
			 var keyID = (e.charCode) ? e.charCode : ((e.which) ? e.which : e.keyCode);
			
		 	if (keyID >= 48 && keyID < 58) {
		 		$(this).text("");
		 		return true;
		 	} else {
		 		return false;
		 		$(this).text("");
		 	}
		}
		// if (e.keyCode < 48 || e.keyCode > 57) {
		//  return false;
		// }
		// $(this).text("");
	});

	$(".login-Page").on('submit' , function(e){
		e.preventDefault();

	});


	$(window).on("resize" , function(){
		if ($(this).width() > 991) {
			$(".menu-open").on("click" , function(){
		if ($(".menu-big-delta").hasClass('activeMenu')) {
			$(".menu-big-delta").addClass('activeMenu');
		} else {
			$(".menu-big-delta").addClass('activeMenu');
			$(".menu-overlay").fadeIn(300);
		}
	});
	$(".menu-overlay").on("click" , function(){
		$(this).fadeOut(300);
		$(".menu-delta").removeClass("activeMenu");
	});	
		} else {
		$(".left-side-mobile").on("click" , function(){
		if ($(".menu-small-delta").hasClass('activeMenu')) {
			$(".menu-small-delta").addClass('activeMenu');
		} else {
			$(".menu-small-delta").addClass('activeMenu');
			$(".menu-overlay").fadeIn(300);
		}
	});
		}
	});
	if ($(window).width() >  991) {
		$(".menu-open").on("click" , function(){
		if ($(".menu-big-delta").hasClass('activeMenu')) {
			$(".menu-big-delta").addClass('activeMenu');
		} else {
			$(".menu-big-delta").addClass('activeMenu');
			$(".menu-overlay").fadeIn(300);
		}
	});
	$(".menu-overlay").on("click" , function(){
		$(this).fadeOut(300);
		$(".menu-delta").removeClass("activeMenu");
	});
	
	} else {
		$(".left-side-mobile").on("click" , function(){
		if ($(".menu-small-delta").hasClass('activeMenu')) {
			$(".menu-small-delta").addClass('activeMenu');
		} else {
			$(".menu-small-delta").addClass('activeMenu');
			$(".menu-overlay").fadeIn(300);
		}
	});
	
	
	}

	$(".menu-overlay").on("click" , function(){
		$(this).fadeOut(300);
		$(".menu-delta , .menu-small-delta").removeClass("activeMenu");
	});

	$('.trivias-slider').slick({
		slidesToShow : 1 ,
		arrows:false,
		dots:true,
		infinite: false,
	});
	$(".slider-wrap-block").slick({
		slidesToShow : 1,
		arrows:true
	});
	if ($("#timer").length) {
		var countDownDate = new Date("Mar 18 , 2018 15:37:22");
		var x = setInterval(function() {
  		var now = new Date().getTime();
  		var distance = countDownDate - now;
  		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
 		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
 		var dateCount = days*24;
 		hours = hours+dateCount;
  		document.getElementById("timer").innerHTML =  hours + ":"
  		+ minutes + ":" + seconds;
  		// document.getElementById("trivias-timer").innerHTML  =  hours + ":"
  		// + minutes + ":" + seconds;
   	// 	document.getElementById("small-timer-trivias").innerHTML  =  hours + ":"
  		// + minutes + ":" + seconds;
 
  		if (distance < 0) {
   		 		clearInterval(x);
   		 		document.getElementById("timer").innerHTML = "EXPIRED";
  		}
		});	
}

	if ($("#trivias-timer").length) {
		date = $('#end-pc-trivia-date').val();
		var countDownDate = new Date(date);
		var x = setInterval(function() {
		var now = new Date().getTime();
		var distance = countDownDate - now;
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
		var dateCount = days*24;
		hours = hours+dateCount;
		document.getElementById("trivias-timer").innerHTML  =  hours + ":"
		+ minutes + ":" + seconds;
		if (distance < 0) {
		clearInterval(x);
		document.getElementById("timer").innerHTML = "EXPIRED";
		}
	  });
	}
	

	if ($("#small-timer-trivias").length) {
		date = $('#end-pc-trivia-date').val();
		var countDownDate = new Date(date);
		var x = setInterval(function() {
  		var now = new Date().getTime();
  		var distance = countDownDate - now;
  		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
 		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
 		var dateCount = days*24;
 		hours = hours+dateCount;
   			document.getElementById("small-timer-trivias").innerHTML  =  hours + ":"
  		+ minutes + ":" + seconds;
 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("timer").innerHTML = "EXPIRED";
 	}
  });
}
	

$(".image-inner-slider").slick({
	infinite:true,
	slidesToShow:1
});
$(".small-image-slider").slick({
	infinite:true,
	slidesToShow:1,
	arrows:true
});
});
