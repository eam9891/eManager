/**
 * Created by Ethan on 10/2/2016.
 */
$(document).ready(function() {
    window.location = window.location.href + '#tab1';
});
$('.tabHdr').on('click',function(){
    $('.selected').removeClass('selected');
    $(this).toggleClass('selected');
});





//window.addEventListener("load", function() {
//    window.location = window.location.href + '#tab1';
//});

//var tabHdr = document.getElementsByClassName("tabHdr").addEventListener("click", showTab);

//function showTab() {
 //   tabHdr = document.getElementsByClassName("tabHdr").classList;
  //  var selectedTab = document.getElementsByClassName("selected").classList;

 //   if (!tabHdr.contains("selected")) {
 //       selectedTab.remove("selected");
 //       tabHdr.add("selected");
  //  }
//}