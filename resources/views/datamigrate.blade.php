<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Migration</title>
</head>

<body>
    <div>Count <span><b id="count"></b></span></div>
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha2256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>
    <script>
        var num = 1000;
        var counter = 1;
        var offs = 0;
        var total = 0;
        var csrf = "{{ csrf_token() }}";
        recursively_ajax(limit = num)

        function recursively_ajax(offset, limit = num) {
            $.ajax({
                type: "GET",
                // async: false, // set async false to wait for previous response
                url: "{{ route('dbupdate') }}",
                data: {
                    limit: limit,
                    offset: offset
                },
                success: function(data) {
                    // console.log(data.data)
                    if (limit == data.count) {
                        total += data.count
                        offs += data.count
                        recursively_ajax(offs, num)
                        counter++
                    }
                    csrf = "{{ csrf_token() }}";
                    $('#count').html(total)
                }
            });
        }
        recursively_ajax();
    </script>
</body>

</html>
