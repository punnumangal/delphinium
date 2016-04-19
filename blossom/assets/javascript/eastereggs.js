var eggs = ['harlem_shake','ripples','asteroids','katamari','bombs','ponies','my_little_pony','snow'];
var eggsIcons = ['bolt','bullseye','rocket','fa fa-soccer-ball-o','fa fa-bomb','linux','github-alt','gears'];
var comands = ['Press "h" and "a" at the same time', 'Press "r" and "i" at the same time and move the mouse', 'Press "a" and "s" at the same time, space to shoot, arrow keys to move', 'Press "k" and "a" at the same time, follow instructions', 'Press "b" and "o" at the same time, click mouse in text to drop them', 'Press "p" and "o" at the same time, space to plant, arrow keys to move', 'Press "m" and "y" at the same time, watch and enjoy','Press "s" and "n" at the same time, watch and enjoy'];
var keys = {};
var count = 65;
var str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
for(var i=0; i<str.length; i++){
  var nextChar = str.charAt(i);
  keys[nextChar] = count;
  count++;
}

var harlemShake = {}, ripple = {}, asteroid = {}, katamari = {}, bomb = {}, pony = {}, myLittlePony = {}, snow = {};
harlemShake[keys.H] = false;
harlemShake[keys.A] = false;
harlemShake['loaded'] = false;
ripple[keys.R] = false;
ripple[keys.I] = false;
ripple['loaded'] = false;
asteroid[keys.A] = false;
asteroid[keys.S] = false;
asteroid['loaded'] = false;
katamari[keys.K] = false;
katamari[keys.A] = false;
katamari['loaded'] = false;
bomb[keys.B] = false;
bomb[keys.O] = false;
bomb['loaded'] = false;
pony[keys.P] = false;
pony[keys.O] = false;
pony['loaded'] = false;
myLittlePony[keys.M] = false;
myLittlePony[keys.Y] = false;
myLittlePony['loaded'] = false;
snow[keys.S] = false;
snow[keys.N] = false;
snow['loaded'] = false;

$(document).keydown(function(e) {
	//Harlem Shake
  if(current_grade >= config.harlem_shake){
    if (e.keyCode in harlemShake) {
      harlemShake[e.keyCode] = true;
      if (harlemShake[keys.H] && harlemShake[keys.A]) {
        if(!harlemShake["loaded"]){
          harlemShake["loaded"] = true;
          var s = document.createElement('script');
          s.setAttribute('src', path + 'plugins/delphinium/blossom/assets/javascript/harlem-shake.js');
          document.body.appendChild(s);
        }else{
          for (var L = 0; L < C.length; L++) {
            var A = C[L];
            if (v(A)) {
              if (E(A)) {
                k = A;
                break
              }
            }
          }
          if (A === null) {
            console.warn("Could not find a node of the right size. Please try a different page.");
          }
          c();
          S();
          var O = [];
          for (var L = 0; L < C.length; L++) {
            var A = C[L];
            if (v(A)) {
              O.push(A)
            }
          }
        }
      }
    }
  }

  //Page Ripple
  if(current_grade >= config.ripples){
    if (e.keyCode in ripple) {
      ripple[e.keyCode] = true;
      if (ripple[keys.R] && ripple[keys.I]) {
        if(!ripple["loaded"]){
          ripple["loaded"] = true;
          var s = document.createElement('script');
          s.setAttribute('src', path + "plugins/delphinium/blossom/assets/javascript/jquery.ripples.js");
          document.body.appendChild(s);
          $('body').css('backgroundImage', 'url(' + path + 'plugins/delphinium/blossom/assets/images/pebbles.png)');
        } else {
          setInterval(function() {
            var $el = $('body');
            var x = Math.random() * $el.outerWidth();
            var y = Math.random() * $el.outerHeight();
            var dropRadius = 20;
            var strength = 0.04 + Math.random() * 0.04;

            $el.ripples('drop', x, y, dropRadius, strength);
          }, 2000);
        }        
      }
    }
  }

  //Asteroids 
  if(current_grade >= config.asteroids){
    if (e.keyCode in asteroid) {
      asteroid[e.keyCode] = true;
      if (asteroid[keys.A] && asteroid[keys.S]) {
        if(!asteroid["loaded"]){
          asteroid["loaded"] = true;
          var s = document.createElement('script');
          s.setAttribute('src', path + "plugins/delphinium/blossom/assets/javascript/kickass.js");
          document.body.appendChild(s);
        }
      }
    }
  }

  //Katamari 
  if(current_grade >= config.katamari){
    if (e.keyCode in katamari) {
      katamari[e.keyCode] = true;
      if (katamari[keys.K] && katamari[keys.A]) {
        if(!katamari["loaded"]){
          katamari["loaded"] = true;
        	var s = document.createElement('script');
    			s.setAttribute('src', path + "plugins/delphinium/blossom/assets/javascript/kh.js");
    			document.body.appendChild(s);
        }
      }
    }
  }

  //Bombs 
  if(current_grade >= config.bombs){
    if (e.keyCode in bomb) {
      bomb[e.keyCode] = true;
      if (bomb[keys.B] && bomb[keys.O]) {
        if(!bomb["loaded"]){
          bomb["loaded"] = true;
        	window.FONTBOMB_HIDE_CONFIRMATION = true;
        	var s = document.createElement('script');
    			s.setAttribute('src', path + "plugins/delphinium/blossom/assets/javascript/bomb.js");
    			document.body.appendChild(s);
        }
      }
    }
  }

  //Ponies 
  if(current_grade >= config.ponies){
    if (e.keyCode in pony) {
      pony[e.keyCode] = true;
      if (pony[keys.P] && pony[keys.O]) {
        if(!pony["loaded"]){
          pony["loaded"] = true;
        	var s = document.createElement('script');
    			s.setAttribute('src', path + "plugins/delphinium/blossom/assets/javascript/loader.js");
    			document.body.appendChild(s);
        }
      }
    }
  }

  //MyLittlePony
  if(current_grade >= config.my_little_pony){
    if (e.keyCode in myLittlePony) {
      myLittlePony[e.keyCode] = true;
      if (myLittlePony[keys.M] && myLittlePony[keys.Y]) {
        if(!myLittlePony["loaded"]){
          myLittlePony["loaded"] = true;
        	var b = document.createElement('script');
        	b.setAttribute('src', path + "plugins/delphinium/blossom/assets/javascript/browserponies.js");
        	document.body.appendChild(b);
        }
      }
    }
  }

  //Snow
  if(current_grade >= config.snow){
    if (e.keyCode in snow) {
      snow[e.keyCode] = true;
      if (snow[keys.S] && snow[keys.N]) {
        if(!snow["loaded"]){
          snow["loaded"] = true;
          var s = document.createElement('script');
          s.setAttribute('src', path + "plugins/delphinium/blossom/assets/javascript/snowstorm.js");
          document.body.appendChild(s);
          document.body.style.backgroundColor = "black";
        }
      }
    }
  }

}).keyup(function(e) {
  if (e.keyCode in harlemShake) {
    harlemShake[e.keyCode] = false;
  }
  if (e.keyCode in ripple) {
    ripple[e.keyCode] = false;
  }
  if (e.keyCode in asteroid) {
    asteroid[e.keyCode] = false;
  }
  if (e.keyCode in katamari) {
    katamari[e.keyCode] = false;
  }
  if (e.keyCode in bomb) {
    bomb[e.keyCode] = false;
  }
  if (e.keyCode in pony) {
    pony[e.keyCode] = false;
  }
  if (e.keyCode in myLittlePony) {
    myLittlePony[e.keyCode] = false;
  }
  if (e.keyCode in snow) {
    snow[e.keyCode] = false;
  }
});