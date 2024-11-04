@extends('landing-page.layouts.app')

@section('title', 'Home | WebgisSekolah')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/fontawesome.min.css" integrity="sha512-RvQxwf+3zJuNwl4e0sZjQeX7kUa3o82bDETpgVCH2RiwYSZVDdFJ7N/woNigN/ldyOOoKw8584jM4plQdt8bhA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('dropify/css/dropify.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <script src="{{ asset('leaflet/js/leaflet.textpath.js') }}"></script>
    <style>
        #map { height: 40rem; }
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
    <script src="{{ asset('dropify/js/dropify.min.js') }}"></script>
    <script>
        const map = L.map('map').setView([-0.0239314,109.3424808], 15);
        var markers = new L.FeatureGroup();
        var popup = L.popup();
                googleStreets = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}',{
                    maxZoom: 20,
                    subdomains:['mt0','mt1','mt2','mt3']
                }).addTo(map);
        $.getJSON("{{ asset('json/smp.json') }}", function(data){
            $.each(data, function(index){
                marker = L.circleMarker([data[index].lat,data[index].lng],{
                    radius: 5,
                    weight: 2,
                    opacity: 0,
                    fillOpacity: 1,
                    color: "#ffff00"
                }).addTo(markers).on('click', function(e){
                    urlGambar = "{{asset('images/logo-fraksi')}}" + '/' + data[index].logo_partai;
                    konten_html = '<div>';
                        konten_html += '<div style="text-align:left">';
                            konten_html += '<label class="form-label">Nama Sekolah: </label><span>'+data[index].nama_sekolah+'</span>';
                            konten_html += '<br>'
                            konten_html += '<label class="form-label">Kecamatan: </label><span>'+data[index].kecamatan+'</span>';
                            konten_html += '<br>'
                            konten_html += '<label class="form-label">Jumlah Siswa: </label><span>'+data[index].peserta_didik+'</span>';
                        konten_html += '</div>';
                    konten_html += '</div>';
                    popup
                        .setLatLng(e.latlng)
                        .setContent(konten_html)
                        .openOn(map);
                });
            })
        });
        map.addLayer(markers);
    </script>
@endsection
