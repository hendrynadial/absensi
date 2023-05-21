@extends('layouts/app',['title'=>'Lokasi Sekolah'])
@section('content')

<style>
    #map {
        height: 500px;
        width: 100%;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <!-- start page title -->
        <div class="page-title-box bg-success">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h4>Lokasi</h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item active">Lokasi</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="container-fluid">
            <div class="page-content-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Lokasi</h4>
                                <p class="card-title-dsec">Sekolah Sultan Iskandar Muda</p>
                                <div id="map"></div>
                                <form id="form-location" action="">
                                @csrf
                                     <div class="row mt-3">
                                         <div class="col-12">
                                             <label for="radius">Radius</label>
                                             <input type="number" id="radius" name="radius" value="{{ $modul->radius }}" min="1" oninput="validity.valid||(value='');" /> m
                                         </div>

                                         <div class="col-12">
                                            <div>Latitude: <span id="lat"></span></div>
                                            <div>Longitude: <span id="lng"></span></div>
                                            <input type="hidden" id="latitude" name="latitude" value="" />
                                            <input type="hidden" id="longitude" name="longitude" value="" />
                                         </div>

                                         <div class="col-md-4 mt-3">
                                             <button type="submit" class="btn btn-success">Simpan</button>
                                         </div>
                                     </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=" defer></script>
<script>
    window.onload = () => {
        initMap()
    }

    // Initialize and add the map
    const lat = document.getElementById("lat");
    const lng = document.getElementById("lng");
    const latForm = document.getElementById("latitude");
    const lngForm = document.getElementById("longitude");

    const getLocation = () => {
        if(navigator.geolocation){
            // timeout at 60000 milliseconds (60 seconds)
            var options = {timeout:60000};
            navigator.geolocation.getCurrentPosition(updateLocation, errorHandler, options);
        } else{
            alert("Sorry, browser does not support geolocation!");
        }
    }

    const initMap = () => {
        const latValue = parseFloat('{{ $modul->latitude }}');
        const lngValue = parseFloat('{{ $modul->longitude }}');
        const coordinate = { lat: latValue, lng: lngValue }
        lat.innerHTML = coordinate.lat;
        lng.innerHTML = coordinate.lng;
        latForm.value = coordinate.lat
        lngForm.value = coordinate.lng


        map = new google.maps.Map(document.getElementById("map"),{
            zoom: 16,
            center: coordinate
        })
        let marker = new google.maps.Marker({
            position: coordinate,
            map,
            animation: google.maps.Animation.DROP
        })

        let loginRadius = new google.maps.Circle({
            map: map,
            radius: {{ $modul->radius }},
            strokeColor: '#000000',
            strokeOpacity: 0.5,
            strokeWeight: 2,
            fillColor: '#000000',
            fillOpacity: 0.2,
            center: coordinate
        })

        // Configure the click listener.
        map.addListener("click", (mapsMouseEvent) => {
            const newCoordinate = mapsMouseEvent.latLng.toJSON();
            marker.setMap(null);

            marker = new google.maps.Marker({
                position: newCoordinate,
                map,
                animation: google.maps.Animation.DROP
            })
            loginRadius.setCenter(newCoordinate);
            lat.innerHTML = newCoordinate.lat;
            lng.innerHTML = newCoordinate.lng;
            latForm.value = newCoordinate.lat
            lngForm.value = newCoordinate.lng
        });

        // radius listener
        document.getElementById("radius").addEventListener("input", (ev) => {
            loginRadius.setRadius(parseInt(ev.target.value))
        })
    }
    window.initMap = initMap;


    let target = document.head;
    let observer = new MutationObserver(function(mutations) {
        for (let i = 0; mutations[i]; ++i) { // notify when script to hack is added in HTML head
        if(mutations[i].addedNodes.length > 0) {
            if (mutations[i].addedNodes[0].nodeName == "SCRIPT" && mutations[i].addedNodes[0].src !== null && (mutations[i].addedNodes[0].src.match(/\/AuthenticationService.Authenticate?/g) || mutations[i].addedNodes[0].src.match(/\/QuotaService.RecordEvent?/g))) {
                let str = mutations[i].addedNodes[0].src.match(/[?&]callback=.*[&$]/g);
                if (str) {
                    if (str[0][str[0].length - 1] == '&') {
                        str = str[0].substring(10, str[0].length - 1);
                    } else {
                        str = str[0].substring(10);
                    }
                    let split = str.split(".");
                    let object = split[0];
                    let method = split[1];
                    window[object][method] = null; // remove censorship message function _xdc_._jmzdv6 (AJAX callback name "_jmzdv6" differs depending on URL)
                    window[object] = {}; // when we removed the complete object _xdc_, Google Maps tiles did not load when we moved the map with the mouse (no problem with OpenStreetMap)
                }
                // observer.disconnect();
            }
        }
        }
    });
    let config = { attributes: true, childList: true, characterData: true }
    observer.observe(target, config);

    $("#form-location").submit(function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: '{!! url("/lokasi/store/") !!}',
            type: "POST",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(e) {
                if (e.status == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: e.message,
                        timer: 1000,
                    })
                    location.href = "/lokasi";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Opss...',
                        text: e.message,
                        timer: 2000,
                    })
                }
            }
        });
    });
</script>
@endpush


