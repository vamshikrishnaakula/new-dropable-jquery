<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->

    <style>
        body {
            background-color: lightblue;
        }

        canvas {
            border: 1px solid black;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    {{-- newscript --}}

    <title>Image Coordinates</title>
    <div class="container-fluid">
        <form id="myForm1" action="" type="post">
            @csrf
            <input type="hidden" id="custId" name="cusId" value={{ $document->id }}>
            <div class="col-md-4 " style="margin-left:605px">
                <label for="input">Input value:</label>
                <select id='input' class="form-control" multiple='multiple'>
                    <option value="0">Please select ...</option>
                    <option value="HallticketNumber">HallticketNumber</option>
                    <option value="Name">Name</option>
                    <option value="FatherName">FatherName</option>
                    <option value="Age">Age</option>
                    <option value="DOB">DOB</option>
                    <option value="email">email</option>
                    <option value="Petlocation">Petlocation</option>
                    <option value="Gender">Gender</option>
                </select>
            </div>

            <center>
                <canvas id="canvas"></canvas>
                <p id="Input" style="font-family:verdana;font-size:160%;"></p>
                <p hidden id="coordinat"></p>
                <p hidden id="Inputdata"></p>
                <p hidden id="coordinates"></p>
                <p hidden id="ycoordinates"></p><br>
                {{-- <button type="submit">Submit</button> --}}
                <input id="formSub1" type="submit" value="Submit" class="btn btn-default">
            </center>
            <input type="hidden" id="Input2" name='name2'>
            {{-- <input type="hidden"id="Xco" name="X">
            <input type="hidden"id="Yco" name="Y"> --}}
        </form>

    </div>


</body>

</html>

<script>
    // Get canvas element
    var canvas = document.getElementById('canvas');
    canvas.width = 1121;
    canvas.height = 789;
    var ctx = canvas.getContext('2d');

    // Create image object
    var img = new Image();
    img.src = '{{ url("images/$document->certificate_path") }}';

    // Draw image on canvas
    img.onload = function() {
        ctx.drawImage(img, 0, 0);
    }
</script>

{{-- //ajax --}}

<script>
    $(document).ready(function() {
        //var positions = {};
        var positions = [];
        var inputs = [];
        var data = [];
        var Mainarr = [];

        $(document).on('mousedown', 'select option', function(e) {
            var self = $(this);
            var selectedValue = self.val();
            if ($('#' + selectedValue).length) {
                return;
            }
            var draggableDiv = $('<div/>').prop('id', selectedValue).css({
                position: 'absolute',
                left: e.pageX,
                top: e.pageY,
                width: 100,
                height: self.height(),
                cursor: 'default',
                background: '#ff0',
                opacity: 1
            }).text(self.val());

            $('form').append(draggableDiv);

            draggableDiv.draggable({
                revert: false,
            });

            //new code
            $("canvas").droppable({
                drop: function(event, ui) {
                    var dragvar = ui.draggable.attr("id");
                    var offset = $(this).offset();
                    var xPos = ui.offset.left - offset.left;
                    var yPos = ui.offset.top - offset.top;

                    // Find the index of the selected value in the inputs array
                    var index = inputs.findIndex(function(item) {
                        return item.startsWith(dragvar);
                    });
                    //alert(index);

                    // If the selected value already exists in the array, update its position
                    if (index !== -1) {
                        var data = inputs[index].split(',');
                        data[1] = xPos;
                        data[2] = yPos;
                        inputs[index] = data.join(',');
                        //alert(data);
                    }
                    // If the selected value doesn't exist in the array, add it
                    else {
                        inputs.push(dragvar + "," + xPos + "," + yPos);
                    }

                    // Display all selected options and their positions in the #Input element
                    var inputText = "";
                    for (var i = 0; i < inputs.length; i++) {
                        var data = inputs[i].split(',');
                        inputText += data[0] + ": (" + data[1] + ", " + data[2] + ")\n";
                    }
                    $("#Input").text(inputText);
                    $("#Inputdata").text(Inputdata);
                    $("#coordinat").text(data[0]);
                    $("#coordinates").text(data[1]);
                    $("#ycoordinates").text(data[2]);
                    var Mainarr = [];
                    Mainarr.push(inputText);
                    //alert(typeof Mainarr);
                }
            });

        });
        //var cust_id = $('#custId').val();
        //var cus_Id = document.getElementById("custId").innerHTML
        //alert(cust_id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#formSub1').on('click', function(e) {
            var cust_id = $('#custId').val();
            //alert(cust_id);
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/run",
                data: {
                    
                    info : inputs,
                    cust_id:cust_id
                },
                /**data: {
                    name: "John",
                    age: 30,
                    city: "New York"
                },**/
                //data: { name: "Boston", location: "jhon" },
                success: function(result) {
                    debugger;
                    alert(result);
                    console.log(result)
                },
                error: function(xhr, status, error) {
                    alert("Error occured", error);
                }
            });
        })

    });
</script>

<script>
    /**var dataaa = JSON.stringify(name);
    alert(typeof dataaa);

 $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery('#myForm').submit(function(e) {
        debugger;
        var name = document.getElementById("Input").innerHTML;
        document.getElementById("Input2").value = name;
        e.preventDefault();
        infodata=name
        //infodata[0] = 'hi';
        //infodata[1] = 'hello';
        jQuery.ajax({
            url: '/run',
            type: 'POST',
            data: {
                    //info: jQuery('#myForm').serialize(),
                    info : infodata
            },
            //data: jQuery('#myForm').serialize(),
            success: function(result) {
                debugger;
                alert( result);
                console.log(result)
            },
            error: function(xhr, status, error) {
                alert("Error occured", error);
            }
        })
    }) **/
</script>

<script></script>
