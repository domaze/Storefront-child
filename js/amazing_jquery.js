'use strict';
function searchFormSubmit(){
  alert( "Recherche avec envoi de formulaire de Wordpress" );
  jQuery("#searchform").submit();
}

jQuery(document).ready(function() {

var topMenu1 = jQuery('#menu1').offset().top; //position Y par rapport au haut de page. .offset() renvoie un objet contenant les coordonnées X et Y */
function scrollActions(){
  var topPage = jQuery(window).scrollTop(); //retoune 0 si l'élément (ici window) n'est pas scrollable ou si il est au sommet de page scrollTop() 
	if (topPage > topMenu1) {
  jQuery(".woocommerce-ordering").css('display', 'none');
  jQuery("div.site-search").css('display', 'none');
  jQuery("#menu1").addClass('topmobile');
		 }
     else {
  jQuery(".woocommerce-ordering").css('display', 'block');
  jQuery("div.site-search").css('display', 'block');
  jQuery("#menu1").removeClass('topmobile');
      //console.log(topMenu1);
     }
   }
  
   jQuery(window).on('scroll',scrollActions);

  jQuery("#boutton_menu1").click(function(){
  jQuery(".menu1").slideToggle();
  jQuery(this).toggleClass('open');
    }); 

    /*
    if( jQuery(".menu1").css('display') == 'none' ) {
    } */
  


  });
