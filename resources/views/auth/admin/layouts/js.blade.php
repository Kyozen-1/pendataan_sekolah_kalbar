<!-- Vendor js -->
<script src="{{asset('/adminto/assets/js/vendor.min.js')}}"></script>

<!-- App js -->
<script src="{{asset('/adminto/assets/js/app.min.js')}}"></script>
{{-- sweetaler --}}
<script src="{{asset('/adminto/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
    $("#sa-title").click(function(){Swal.fire({title:"Lupa Password?",text:"Hubungi Super Admin Untuk Reset Password",type:"question",confirmButtonClass:"btn btn-confirm mt-2"})})

    $("#ba-title").click(function(){Swal.fire({title:"Ingin Buat Akun Sekolah?",text:"Hubungi Admin Dinas Pendidikan dan Kebudayaan",type:"question",confirmButtonClass:"btn btn-confirm mt-2"})})
    $('.toggle-password').click(function(){
        $(this).toggleClass("fa-eye fa-eye-slash");

        var input = $('#password');
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
@yield('js')
