@if(Session::has('success'))
<script>
    notyf.success(`{{ Session::get('success') }}`);
</script>
@elseif (Session::has('error'))
  <script>
      notyf.error(`{{ Session::get('error') }}`);
  </script>
@endif