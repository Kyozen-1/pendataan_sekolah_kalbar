@extends('landing-page.layouts.app')

@section('title', 'Home | WebgisSekolah')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/fontawesome.min.css" integrity="sha512-RvQxwf+3zJuNwl4e0sZjQeX7kUa3o82bDETpgVCH2RiwYSZVDdFJ7N/woNigN/ldyOOoKw8584jM4plQdt8bhA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('dropify/css/dropify.min.css') }}">
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <!-- home start -->
    <section class="bg-home bg-gradient p-0 m-0" id="home">
        <div id="map"></div>
    </section>
    <!-- home end -->
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/fontawesome.min.js" integrity="sha512-j3gF1rYV2kvAKJ0Jo5CdgLgSYS7QYmBVVUjduXdoeBkc4NFV4aSRTi+Rodkiy9ht7ZYEwF+s09S43Z1Y+ujUkA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('API_KEY_GOOGLEMAPS') }}&callback=initMap" async defer></script>
    <script>
        function initMap() {
            // Center the map on West Kalimantan
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 8,
                center: { lat: -0.203649, lng: 110.677010 }, // Adjust as needed
            });

            // Load GeoJSON from the public directory
            map.data.loadGeoJson('/geojson/kalimantan_barat.json');

            // Style the polygons
            map.data.setStyle({
                fillColor: '#FF0000',
                strokeColor: '#000000',
                strokeWeight: 1,
                fillOpacity: 0.1,
            });

            // Add info windows on polygon click
            const infoWindow = new google.maps.InfoWindow();

            map.data.addListener('click', (event) => {
                // Access the properties directly
                const name3 = event.feature.getProperty('NAME_3') || "N/A";
                const name1 = event.feature.getProperty('NAME_1') || "N/A";
                const country = event.feature.getProperty('COUNTRY') || "N/A";

                const content = `
                    <div>
                        <strong>Kelurahan / Desa: </strong>${name3}<br>
                        <strong>Kecamatan: </strong>${name1}<br>
                        <strong>Kabupaten: </strong>${country}<br>
                    </div>
                `;
                infoWindow.setContent(content);
                infoWindow.setPosition(event.latLng);
                infoWindow.open(map);
            });

            fetch("{{ route('marker.sekolah') }}")
                .then(response => response.json())
                .then(data => {
                    data.forEach(markerData => {
                        const marker = new google.maps.Marker({
                            position: { lat: parseFloat(markerData.lat), lng: parseFloat(markerData.lng) },
                            map: map,
                            title: markerData.nama,
                            icon: {
                                url: markerData.ikon_peta,
                                scaledSize: new google.maps.Size(50, 50)
                            }
                        });
                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div class="row p-3">
                                    <div class="col-md-4 justify-content-center align-self-center text-center">
                                        <img style="height:10rem" src="${markerData.logo}" class="text-center">
                                    </div>
                                    <div class="col-md-8">
                                        <h4>${markerData.nama}</h4>
                                        <p>${markerData.alamat}</p>
                                    </div>
                                </div>
                            `,
                        });

                        marker.addListener('click', () => {
                            infoWindow.open(map, marker);
                        });
                    });
                })
                .catch(error => console.error('Error fetching marker data:', error));
        }
    </script>
@endsection
