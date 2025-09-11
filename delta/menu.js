

function toggleAll() {
var myMain = document.getElementById('main');
var mySwallow = document.getElementById('swallow');
var mySides = document.getElementById('sides');

var displayAll = myMain.style.display;
var displaySwallow = mySwallow.style.display;
var displaySides = mySides.style.display;



      myMain.style.display = 'block';
      mySwallow.style.display = 'none';
      mySides.style.display = 'none';
    
  
}
	
	
function toggleSwallow() {
var myMain = document.getElementById('main');
var mySwallow = document.getElementById('swallow');
var mySides = document.getElementById('sides');



var displayAll = myMain.style.display;
var displaySwallow = mySwallow.style.display;
var displaySides = mySides.style.display;



      myMain.style.display = 'none';
      mySwallow.style.display = 'block';
      mySides.style.display = 'none';
    
  
}



function toggleSides() {
var myMain = document.getElementById('main');
var mySwallow = document.getElementById('swallow');
var mySides = document.getElementById('sides');



var displayAll = myMain.style.display;
var displaySwallow = mySwallow.style.display;
var displaySides = mySides.style.display;


      myMain.style.display = 'none';
      mySwallow.style.display = 'none';
      mySides.style.display = 'block';
    
  
}

