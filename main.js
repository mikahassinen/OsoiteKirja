
    /* Show 'add', 'edit' or 'view' page depending on hash or 'add' page without hash */
    if (window.location.hash == '#add') { // add page is block and has active navi
		showAddPage();
    }
    else if (window.location.hash == '#view') { // view page is block and has active navi
        showViewPage();
    }
    else { // add page is block and has active navi (no hash)
		showDefaultPage();
    }

    function showAddPage() {
        document.getElementById('addPage').style.display = 'block';
        document.getElementById('viewPage').style.display = 'none';
        document.getElementById('liAdd').setAttribute('class', 'naviActiveAdd');
        document.getElementById('liView').setAttribute('class', 'naviLI');
	}

    function showViewPage() {
        document.getElementById('addPage').style.display = 'none';
        document.getElementById('viewPage').style.display = 'block';
        document.getElementById('liAdd').setAttribute('class', 'naviLI');
        document.getElementById('liView').setAttribute('class', 'naviActiveView');
    }

    function showDefaultPage() {
        document.getElementById('liAdd').setAttribute('class', 'naviActiveAdd');
        document.getElementById('liView').setAttribute('class', 'naviLI');
        document.getElementById('addPage').style.display = 'block';
        document.getElementById('viewPage').style.display = 'none';
    }

    /* Navigation links: display different page depending on clicked link */
    document.querySelector('nav > ul > li:nth-child(1)').onclick = function () {
    	changeNavStyleAdd();
    };

    function changeNavStyleAdd() {
        window.location.hash = "add"; // Hash is #add
        document.getElementById('viewPage').style.display = 'none';
        document.getElementById('addPage').style.display = 'block';
        document.getElementById("liAdd").setAttribute("class", "naviActiveAdd");
        document.getElementById("liView").setAttribute("class", "naviLI");
    }

    document.querySelector('nav > ul > li:nth-child(2)').onclick = function () {
    	changeNavStyleView();
    };

    function changeNavStyleView() {
        window.location.hash = "view"; // Hash is #view
        document.getElementById('addPage').style.display = 'none';
        document.getElementById('viewPage').style.display = 'block';
        document.getElementById("liAdd").setAttribute("class", "naviLI");
        document.getElementById("liView").setAttribute("class", "naviActiveView");
    }

/* SUBMIT */

var autocompLat;
var autocompLon;
var baseUrl='http://localhost/Osoitekirja/public/index.php/api/';
var jsonArrayCount=0;

function formToJSON() {
	if (document.getElementById('postal_code').value !== null && document.getElementById('postal_code').value !== '') {
		var firstname = document.getElementById('firstname');
		var lastname = document.getElementById('lastname');
		var street = document.getElementById('route');
		var streetNumber = document.getElementById('street_number');
		var city = document.getElementById('locality');
		var postalCode = document.getElementById('postal_code');
		var country = document.getElementById('country');
		document.getElementById('respond').innerHTML = firstname.value + " " + lastname.value + ", </br>" + street.value + " " + streetNumber.value + ", " + city.value + " " + postalCode.value + " </br>" + country.value;
		var json = JSON.stringify ({"firstname":firstname.value, "lastname":lastname.value, "street":street.value, "streetNumber":streetNumber.value, "city":city.value, "postalCode":postalCode.value, "country":country.value, "lat":autocompLat, "lon":autocompLon});
		postJson(json);
		showViewPage();
		changeNavStyleView();
		clearAddFields();
		//document.getElementById('respond').innerHTML = json;
	} else {
		alert("Postinumero puuttuu. Tietoja ei lähetetty. ")
	}
}

function clearAddFields() {
    document.getElementById('firstname').value = '';
    document.getElementById('lastname').value = '';
    document.getElementById('autocomplete').value = '';
    document.getElementById('route').value = '';
    document.getElementById('street_number').value = '';
    document.getElementById('locality').value = '';
    document.getElementById('postal_code').value = '';
    document.getElementById('country').value = '';
}

function clearViewFields() {
    document.getElementById('search').value = '';
}

function getPersonsData(data) {
	var word = /^[A-Za-zÄÖäö]{3,16}$/;
	var url="";
	var zip = "";
	var lastname="";
	var firstname="";
	var anyname;
	if(data.length==3){
		firstname=data[0];
		lastname=data[1];
		zip=data[2];
		url="persons/byfullnameandzip?firstname="+firstname+"&lastname="+lastname+"&zip="+zip;
		
	}
	else if(data.length==2){
		//onko molemmat nimia
		if(data[0].match(word)!==null&data[1].match(word)!==null){
			firstname=data[0];
			lastname=data[1];
			url="persons/byfirstandlast?firstname="+firstname+"&lastname="+lastname;
		}else{
			//toinen on postinumero
			for (var i = 0; i < data.length; i++) {
		
				if(data[i].match(word) !== null) {			
					anyname=data[i];
				}else{
					zip=data[i];
				}
			}
			url="persons/byanynameandzip?firstname="+anyname+"&zip="+zip;
		}		
	}else{
		if(data[0].match(word) !== null) {
			//ainoastaan yksi nimi
			anyname=data[0];
			if(anyname=="all"){
				url="persons";
			}else{
				url="persons/byanyname?anyname="+anyname;	
			}
				
		}else{
			//ainoastaan postinumero
			zip=data[0];
			url="persons/byzip?zip="+zip;	
		}
	}
	 fetchRequest(url);	
}



function fetchRequest(url){
	var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
						var myArr = JSON.parse(xhttp.responseText);
						var resText = xhttp.responseText;
						//console.log(myArr[0].latitude);
						//console.log(resText);											
			showResultCount(myArr);
			if(myArr.length>0){
				parseResponse(myArr);				
			}
		}
	};
	console.log(baseUrl+url);
	xhttp.open("GET", baseUrl+url, true);
	//xhttp.withCredentials = false;
	xhttp.send();
}

function showResultCount(arr){
	var arrLength=arr.length;
	if (arrLength>0) {
		/* Jos tulos löytyi, niin asetetaan löytynyt paikkatieto lat ja lon muuttujiin, lisätään merkki kartalle. */
		// TODO
		//var lat = autocompLat;
		//var lon = autocompLon;			
		//addMapMarker(lat,lon);
		document.getElementById('searchphrase').innerHTML = "Haulla löytyi "+arrLength+" tulosta";
	} else {
		/* Tuloksia ei löytynyt, ilmoitetaan käyttäjälle. */
		// TODO
		document.getElementById('searchphrase').innerHTML = "Haulla ei löytynyt tuloksia";
	}
}

function parseResponse(data){

	for(i = 0; i < data.length; i++) {
		var lat = parseFloat(data[i].latitude);
		var lng = parseFloat(data[i].longitude);
		var name = data[i].First_name;
		var last = data[i].Last_name;
		var zip = data[i].zip;
		
		addMapMarker(lat,lng,name,last,zip);
		console.log(lat);
		//console.log(data);
		
		}

}

// Change class to 'showBlock'
function showBlock(e) {
	alert(e.tagName);
	e.setAttribute("class", "showBlock");
}

// Change class to 'hideBlock'
function hideBlock (e) {
	alert(e.tagName);
	e.parentNode.setAttribute("value", "hideBlock");
}

/* AUTO COMPLETE powered by Google */ 

      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
			autocompLat = place.geometry.location.lat();
			autocompLon = place.geometry.location.lng();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = true;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
	  
/* VIEW MAP */ 

var map;
var markersArray = [];

	function clearOverlays() {
		for (var i = 0; i < markersArray.length; i++ ) {
			markersArray[i].setMap(null);
		}
		markersArray.length = 0;
	}

	function initMap() {
	var deflocat = {lat: 60.16985569999999, lng: 24.938379};
	map = new google.maps.Map(document.getElementById('map'), {
		zoom: 14,
		center: deflocat
	});
	}
	  
/* ADD MAP MARKER */

	function search() {
		clearOverlays();
		var word = /^[A-Za-zÄÖäö]{3,16}$/;
		var digit = /^\d{5}$/;
		var patt = (word|digit);
		
		var output = "<b>Hakutermit:</b> </br>";
		var searcharray = document.getElementById('search').value.split(" ");
		for (var i = 0; i < searcharray.length; i++) {
			/* Etsitään jokaisella hakutermillä (esim. etunimi, sukunimi, postinumero), 
			asetetaan result todeksi, jos hakuehtoa vastaava tulos löytyi */
			if(searcharray[i].match(word) !== null | searcharray[i].match(digit) !== null) {
				/* Etsitään tietokannasta searcharray[i] hakuehdolla nimeä, sukunimeä tai postinumeroa. 
				Haetaan tietokannasta latitude & longitude löydetylle hakutulokselle. */ 
				getPersonsData(searcharray);
				
				output = output + searcharray[i] + "</br>";
				
			}
		}
		document.getElementById('searchphrase').innerHTML = output;
        clearViewFields();
	}
	
	function addMapMarker(lat,lon,name,lastname,zip) {
	//	clearOverlays();
		var lt = lat;
		var ln = lon;
		var marker = new google.maps.Marker({
			position: {lat: lt, lng: ln},
		//	animation:google.maps.Animation.BOUNCE,
			map: map,
			title:name
				});
				
		var infowindow = new google.maps.InfoWindow({
			content: name+" "+lastname+" "+zip
		});
		markersArray.push(marker);
        marker.addListener('click', function() {
					infowindow.open(map, marker);
					setAnimation(marker);
        });
		google.maps.event.addListener(marker,"click",function(){});
		/* document.getElementById('search').value 	= lat + ", " + lon */
	}

	function setAnimation(marker) {	
		marker.setAnimation(google.maps.Animation.BOUNCE);		
		for(var i=0;i<markersArray.length;i++){
			if(markersArray[i]!==marker){
				markersArray[i].setAnimation(null);
			}
		}
	}

function postJson(json) {
	/* Lähetetään json XMLHttpRequestilla */
	var xhr = new XMLHttpRequest();
	var url = baseUrl + "create";
	console.log(url);
	xhr.open("POST", url, true);

//Send the proper header information along with the request
xhr.setRequestHeader("Content-type", "application/json");
xhr.onreadystatechange = function() {//Call a function when the state changes.
    if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
        // Request finished. Do processing here.
    }
}
		console.log(json);

//xhr.withCredentials = false;
xhr.send(json);
}

window.onload = function(){
	initAutocomplete();
	initMap();
};