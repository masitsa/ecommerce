	var gmarkers = [];
	var map = null;
	var infobox = null;
	var html = null;
	var links = '';
	var myOptions = null;
	var cent = new google.maps.LatLng(38.65119833229951,-90.17578125);
	var infowindow = new google.maps.InfoWindow(
	{
		size: new google.maps.Size(50,50)
	});
	var iconShadow = new google.maps.MarkerImage(CI.base_url+'img/icons/map/shadow.png',
	// The shadow image is larger in the horizontal dimension\
    // while the position and offset are the same as for the main image.
    new google.maps.Size(50, 40),
    new google.maps.Point(0,0),
    new google.maps.Point(9, 34));
	// Shapes define the clickable region of the icon.
	// The type defines an HTML &lt;area&gt; element 'poly' which
	// traces out a polygon as a series of X,Y points. The final
	// coordinate closes the poly by connecting to the first
	// coordinate.
	var iconShape = {
		coord: [9,0,6,1,4,2,2,4,0,8,0,12,1,14,2,16,5,19,7,23,8,26,9,30,9,34,11,34,11,30,12,26,13,24,14,21,16,18,18,16,20,12,20,8,18,4,16,2,15,1,13,0],
		type: 'poly'
	};

	// A function to create the marker and set up the event window
	function createMarker(latlng,name,zipCode,html) {
		var ele = document.getElementById("test");
		var text = document.getElementById("show_hide");
		var contentString = html;
		var marker = new google.maps.Marker({
			position: latlng,
			icon: CI.base_url+'img/icons/map/construction.png',
			shadow: iconShadow,
			map: map,
			title: name,
			zIndex: Math.round(latlng.lat()*-100000)<<5
		});
		
		// === Store the name and info as marker properties ===
		marker.myname = name;
		marker.zipCode = zipCode;
		marker.setAnimation(google.maps.Animation.BOUNCE);
		gmarkers.push(marker);
		
		google.maps.event.addListener(marker, 'click', function() {
			myOptions = {
				content: contentString
				,disableAutoPan: false
				,maxWidth: 0
				,pixelOffset: new google.maps.Size(-610, -200)
				,zIndex: null
				,boxStyle: {
					background: "url('images/infowindow.png') no-repeat"
					,width: "100px"
					,height: "100px"
				}
				,closeBoxMargin: "5px 10px 2px 2px"
				,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
				,infoBoxClearance: new google.maps.Size(1, 1)
				,isHidden: false
				,pane: "floatPane"
				,enableEventPropagation: false
			};
			infobox = new InfoBox(myOptions);
			infowindow.setContent(contentString);
			infowindow.open(map,marker);
			map.panTo(latlng);
		});
		
		google.maps.event.addListener(infowindow, 'closeclick', function() { 
			map.panTo(cent);
			makeSidebar();
		});
	}

	function myclick(i) {
		google.maps.event.trigger(gmarkers[i],"click");
	}

	// Build the sidebar projects list
	function makeSidebar() {
		links = '';
		step = 4;
		pagination = '';
		pages = Math.ceil(gmarkers.length/step);
		for (var i=0; i<step; i++) {
			if (gmarkers[i].getVisible()) {
				links += '<a href="javascript:myclick(' + i + ')">' + gmarkers[i].myname + '</a><br>';
			}
		}
		for (var j=1; j<=pages; j++) {
			if(j == 1){
				pagination += '<a href="#" style="color:black;">' + j + '</a>&nbsp&nbsp;';
			} else {
				pagination += '<a href="javascript:page(' + j + ')">' + j + '</a>&nbsp&nbsp;';
			}
		}
		links += '<br\><br\> Pages: '+pagination;
		var projects = document.getElementById('list');
		if (projects != null) {
			projects.innerHTML = links;
		}
	}
	
	function page(pageNo) {
		links = '';
		step = 4;
		pagination = '';
		pages = Math.ceil(gmarkers.length/step);
		if (pageNo == 1) {
			offset = 0;
			number = step;
		} else {
			offset = (pageNo - 1) * step;
			number = pageNo*step;
			if (number > gmarkers.length) { number = gmarkers.length; }
		}
		for (var i=offset; i<number; i++) {
			if (gmarkers[i].getVisible()) {
				links += '<a href="javascript:myclick(' + i + ')">' + gmarkers[i].myname + '</a><br>';
			}
		}
		for (var j=1; j<=pages; j++) {
			if(j == pageNo){
				pagination += '<a href="#" style="color:black;">' + j + '</a>&nbsp&nbsp;';
			} else {
				pagination += '<a href="javascript:page(' + j + ')">' + j + '</a>&nbsp&nbsp;';
			}
		}
		links += '<br\><br\> Pages: '+pagination;
		var projects = document.getElementById('list');
		if (projects != null) {
			projects.innerHTML = links;
		}
	}
	
	// Rebuid the sidebar projects list
	function remakeSidebar(zip) {
		links = '';
		for (var i=0; i<gmarkers.length; i++) {
			if (gmarkers[i].getVisible() && gmarkers[i].zipCode === zip) {
				links += '<a href="javascript:myclick(' + i + ')">' + gmarkers[i].myname + '</a><br>';
			}
		}
		if (links == '') {
			links = 'No projects in this zip code.';
		}
		var projects = document.getElementById('list');
		if (projects != null) {
			projects.innerHTML = links;
		}
	}
	
	function confirm_delete() {
		return confirm('Do you really want to delete this location?');
	}
	
	function initialize() {
		var text = document.getElementById("show_hide"); 
		var styles = [
			{
				featureType: "all",
				stylers: [
					{ saturation: +50 }
				]
			},{
				featureType: "road",
				elementType: "labels",
				stylers: [
					{ visibility: "on" }
				]
			},{
				featureType: "road.local",
				elementType: "geometry",
				stylers: [
					{ visibility: "on" }
				]
			},{
				featureType: "road.arterial",
				elementType: "geometry",
				stylers: [
					{ color: '#D8D503' },
					{ visibility: "on" }
				]
			},{
				featureType: "road.highway",
				elementType: "geometry",
				stylers: [
					{ color: '#D0CC03' },
					{ visibility: "on" }
				]
			}
		];
	
		// Create a new StyledMapType object, passing it the array of styles,
		// as well as the name to be displayed on the map type control.
		var styledMap = new google.maps.StyledMapType(styles,{name: "CMS"});
		// Create a map object, and include the MapTypeId to add
		// to the map type control.
		var mapOptions = {
			zoom: 12,
			panControl: true,
			panControlOptions: 
			{
				position: google.maps.ControlPosition.RIGHT_TOP
			},
			zoomControl: true,
			zoomControlOptions: {
				style: google.maps.ZoomControlStyle.SMALL,
				position: google.maps.ControlPosition.RIGHT_TOP
			},
			
			center: cent,
			
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
				position: google.maps.ControlPosition.TOP_RIGHT,
				mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style',google.maps.MapTypeId.SATELLITE]
			}
		};
		map = new google.maps.Map(document.getElementById('map_canvas'),mapOptions);
		
		//Associate the styled map with the MapTypeId and set it to display.
		map.mapTypes.set('map_style', styledMap);
		map.setMapTypeId('map_style');
	 
		var search = document.getElementById('search');
		var options = {
			types: ['(regions)']
		};
		autocomplete = new google.maps.places.Autocomplete(search, options);
		
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			var place = autocomplete.getPlace();
			if (!place.geometry) {
				alert('Cannot find location on map.');
				return;
			}
			// Otherwise use the location and set a chosen zoom level.
			map.setCenter(place.geometry.location);
			map.setZoom(12);
			
			var image = new google.maps.MarkerImage(
				place.icon, new google.maps.Size(71, 71),
				new google.maps.Point(0, 0), new google.maps.Point(17, 34),
				new google.maps.Size(35, 35)
			);
			var marker = new google.maps.Marker({
				position: place.geometry.location,
				map: map
			});
			marker.setIcon(image);
			marker.setPosition(place.geometry.location);
			infowindow.setContent(place.formatted_address);
			infowindow.open(map, marker);
			var address = place.address_components;
			var zip = address[0].long_name
			remakeSidebar(zip);
		});
		
		// Read the data from the database
		downloadUrl(CI.base_url+"site/map/create_markers", function(doc) {
			var xml = xmlParse(doc);
			var markers = xml.documentElement.getElementsByTagName("marker");
			for (var i = 0; i < markers.length; i++) {
				// obtain the attribues of each marker
				var lat = parseFloat(markers[i].getAttribute("lat"));
				var lng = parseFloat(markers[i].getAttribute("lng"));
				var point = new google.maps.LatLng(lat,lng);
				var address = markers[i].getAttribute("address");
				var info = markers[i].getAttribute("project_info");
				var date = markers[i].getAttribute("project_date");
				var name = markers[i].getAttribute("location_name");
				var zipCode = markers[i].getAttribute("zip_code");
				html = document.createElement("div");
				html.style.cssText = "margin: 5px 0 0 5px; padding: 5px;";
				html.innerHTML = '<div><b>'+name+'</b>&nbsp;:&nbsp;<br/>'+address+'<br /></div>';
				var marker = createMarker(point,name,zipCode,html);
			}
			// Create the initial sidebar
			if (links == '') {
				makeSidebar();
			}
		});
	}

	function toggle() {
		var ele = document.getElementById("side_panel");
		var text = document.getElementById("show_hide"); 
		if(ele.style.display == "none") {
			ele.style.display = "block";
			text.style.background = "white";
			text.style.color = "black";
			text.innerHTML = "Hide Locations";
		} else {
			ele.style.display = "none";
			text.style.background = "#1FAEFF";
			text.style.color = "white";
			text.innerHTML = "Show Locations";
		}
	}